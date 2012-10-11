<?php
namespace DotsBlock\Handler;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;

use Dots\Form\MultiForm;
use Dots\Registry;
use Dots\EventManager\GlobalEventManager;
use DotsBlock\Db\Entity\Block;
use DotsBlock\Db\Entity\HtmlBlock;
use DotsBlock\Form\Content\Html as HtmlContentForm;
use DotsBlock\ContentHandler;
use DotsBlock\HandlerAware;

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
    public function attach(EventManagerInterface $events, $priority = 100)
    {
        GlobalEventManager::attach('admin.head.pre', array($this, 'initHeaders'), $priority);
        GlobalEventManager::attach('admin.body.inline', array($this, 'initTemplates'), $priority);
//        $this->listeners[] = $events->attach('initHeaders', array($this, 'initHeaders'), $priority);
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

    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-block/handler/html/templates');
    }

    /**
     * Add code in the header section of the page
     * @param \Zend\EventManager\Event $event
     */
    public function initHeaders(Event $event)
    {
        $view = $event->getTarget();
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
        $locator = Registry::get('service_locator');
        $model = $locator->get('DotsBlock\Db\Model\HtmlBlock');
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
        $locator = Registry::get('service_locator');
        $block = $event->getTarget();
        $page = $event->getParam('page');
        if ($block->id){
            $model = $locator->get('DotsBlock\Db\Model\HtmlBlock');
            $htmlBlock = $model->getByBlockId($block->id);
        }else{
            $htmlBlock = new HtmlBlock();
        }
        $form = new MultiForm(array(
            'html_content' => new HtmlContentForm()
        ));
        $form->addButtons();
        $form->setData(array(
            'html_content'=> $htmlBlock->toArray()
        ));
        return $this->renderViewModel('dots-block/handler/html/edit', array(
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
        $locator = Registry::get('service_locator');
        $modelHtmlBlock = $locator->get('DotsBlock\Db\Model\HtmlBlock');
        $block = $event->getTarget();
        $request = $event->getParam('request');
        $form = new MultiForm(array(
            'html_content' => new HtmlContentForm()
        ));

        $form->setData($request->getPost()->toArray());
        if ($form->isValid()){
            $data = $form->getInputFilter()->getValues();
            if ($block->id) {
                $htmlBlock = $modelHtmlBlock->getByBlockId($block->id);
            } else {
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
        $locator = Registry::get('service_locator');
        $modelHtmlBlock = $locator->get('DotsBlock\Db\Model\HtmlBlock');
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

    private function renderViewModel($template = null, $vars = array())
    {
        $view = Registry::get('service_locator')->get('view');
        $viewModel = new ViewModel($vars, array('has_parent'=>true));
        $viewModel->setTemplate($template)
            ->setTerminal(true);
        return $view->render($viewModel);
    }

}