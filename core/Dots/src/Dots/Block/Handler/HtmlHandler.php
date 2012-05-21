<?php
namespace Dots\Block\Handler;
use Zend\EventManager\EventManagerInterface,
    Zend\EventManager\Event,
    Zend\EventManager\ListenerAggregate,

    Dots\Module,
    Dots\Db\Entity\Block,
    Dots\Db\Entity\HtmlBlock,
    Dots\Form\MultiForm,
    Dots\Form\Block\HtmlContentForm,
    Dots\Block\ContentHandler,
    Dots\Block\HandlerAware;

/**
 * Html Handler block handler
 */
class HtmlHandler implements HandlerAware
{
    /**
     * Block type
     */
    const TYPE = 'html_content';
    /**
     * Listeners
     * @var array
     */
    protected $listeners = array();
    /**
     * Handler
     * @var ContentHandler
     */
    protected $handler = null;

    /**
     * Attach events to the application and listen for the dispatch event
     * @param \Zend\EventManager\EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events, $priority = null)
    {
        $this->listeners[] = $events->attach('initHeaders', array($this, 'initHeaders'), $priority);
        $this->listeners[] = $events->attach('listHandlers', array($this, 'getHandler'), $priority);
        $this->listeners[] = $events->attach('renderBlock/' . static::TYPE, array($this, 'renderBlock'), $priority);
        $this->listeners[] = $events->attach('editBlock/' . static::TYPE, array($this, 'editBlock'), $priority);
        $this->listeners[] = $events->attach('saveBlock/' . static::TYPE, array($this, 'saveBlock'), $priority);
        $this->listeners[] = $events->attach('removeBlock/' . static::TYPE, array($this, 'removeBlock'), $priority);
    }

    /**
     * Detach all the event listeners from the event collection
     * @param \Zend\EventManager\EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $key => $listener) {
            $events->detach($listener);
            unset($this->listeners[$key]);
            unset($listener);
        }
    }

    /**
     * Get Content Handler
     * @return ContentHandler
     */
    public function getHandler()
    {
        if (!$this->handler){
            $this->handler = new ContentHandler(static::TYPE, 'Html Content');
        }
        return $this->handler;
    }

    /**
     * Add code in the header section of the page
     * @param \Zend\EventManager\Event $event
     */
    public function initHeaders(Event $event)
    {
        $view = $event->getParam('view');
        $view->plugin('headScript')->appendFile('/assets/tiny_mce/tiny_mce.js');
        $view->plugin('headScript')->appendFile('/assets/tiny_mce/jquery.tinymce.js');
        $view->plugin('headScript')->appendFile('/assets/tiny_mce/default_settings.js');
    }

    /**
     * Render html block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $block = $event->getTarget();
        $page = $event->getParam('page');
        $locator = Module::locator();
        $model = $locator->get('Dots\Db\Model\HtmlBlock');
        $htmlBlock = $model->getByBlockId($block->id);
        return $htmlBlock->content;
    }

    /**
     * Render edit html block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function editBlock(Event $event)
    {
        $locator = Module::locator();
        $view = $locator->get('view');
        $block = $event->getTarget();
        $page = $event->getParam('page');
        $section = $event->getParam('section');
        if ($block){
            $model = $locator->get('Dots\Db\Model\HtmlBlock');
            $htmlBlock = $model->getByBlockId($block->id);
        }else{
            $block = new Block();
            $block->type = static::TYPE;
            $block->section = $section;
            $htmlBlock = new HtmlBlock();
        }
        $form = new MultiForm(array(
            'html_content' => new HtmlContentForm()
        ));
        $form->addButtons();
        $form->populate(array(
            'content' => $htmlBlock->content,
        ));
        return $view->render('dots/blocks/html/edit', array(
            'page' => $page,
            'block' => $block,
            'htmlBlock' => $htmlBlock,
            'form' => $form,
        ));
    }

    /**
     * Save html block
     * @param \Zend\EventManager\Event $event
     * @return array|\Dots\Db\Entity\Block|object|string
     */
    public function saveBlock(Event $event)
    {
        $locator = Module::locator();
        $modelBlock = $locator->get('Dots\Db\Model\Block');
        $modelHtmlBlock = $locator->get('Dots\Db\Model\HtmlBlock');
        $view = $locator->get('view');
        $block = $event->getTarget();
        $page = $event->getParam('page');
        $section = $event->getParam('section');
        $position = $event->getParam('position', 1);
        $form = new MultiForm(array(
            'html_content' => new HtmlContentForm()
        ));
        if ($form->isValid($_POST)){
            $data = $form->getValues();
            if ($block) {
                $block->position = $position;
                $htmlBlock = $modelHtmlBlock->getByBlockId($block->id);
            } else {
                $block = new Block();
                $block->position = $position;
                $block->type = static::TYPE;
                $block->section = $section;
                $block->page_id = $page->id;
                $block->save();
                $htmlBlock = new HtmlBlock();
                $htmlBlock->block_id = $block->id;
            }
            $htmlBlock->content = $data['html_content']['content'];
            $htmlBlock->save();
            return $block;
        }
        $event->stopPropagation();
        $errors = $form->getMessages();
        return $errors;
    }

    /**
     * Remove html block
     * @param \Zend\EventManager\Event $event
     * @return bool
     */
    public function removeBlock(Event $event)
    {
        $locator = Module::locator();
        $modelHtmlBlock = $locator->get('Dots\Db\Model\HtmlBlock');
        $block = $event->getTarget();
        $htmlBlock = $modelHtmlBlock->getByBlockId($block->id);
        $htmlBlock->delete();
        $block->delete();
        return true;
    }

    /**
     * Update object with received data
     * @param $obj
     * @param $data
     * @return mixed
     */
    private function updateObject($obj, $data)
    {
        foreach ($data as $key => $value) {
            $obj->$key = $value;
        }
        return $obj;
    }

}