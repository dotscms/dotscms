<?php
namespace Dots\Block\Handler;
use Zend\EventManager\EventManagerInterface,
    Zend\EventManager\Event,
    Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,

    Dots\Module,
    Dots\Db\Entity\Block,
    Dots\Db\Entity\LinkBlock,
    Dots\Form\MultiForm,
    Dots\Form\Block\LinkContentForm,
    Dots\Block\ContentHandler,
    Dots\Block\HandlerAware;

/**
 * Links content block handler and Controller
 */
class LinksContentController extends ActionController implements HandlerAware
{
    /**
     * Block type
     */
    const TYPE = 'links_content';
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
            $this->handler = new ContentHandler(static::TYPE, 'File & Links Content');
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
        $view->plugin('headScript')->appendFile('/assets/dots/js/blocks/links.js');
        $view->plugin('headScript')->appendScript(<<<END
    Dots.Blocks.Links.init();
END
);
    }

    /**
     * Render html block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $locator = Module::locator();
        $view    = $locator->get('view');
        $model   = $locator->get('Dots\Db\Model\LinkBlock');
        $block   = $event->getTarget();
        $page    = $event->getParam('page');
//        $links   = $model->getAllByBlockIdOrderByPosition($block->id);
        $links = $model->getAllByColumns(array(
            'block_id = ? ORDER BY position' => $block->id,
        ));
        return $view->render('dots/blocks/links/render', array(
            'page' => $page,
            'block' => $block,
            'links' => $links,
        ));
    }

    public function addAction()
    {
        $form = new LinkContentForm();
        $form->setElementsBelongTo('link_content[1]');
        $form->addButtons();
        $viewModel = new ViewModel(array('form'=>$form));
        $viewModel->setTemplate('dots/blocks/links/form');
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function getPagesAction()
    {
        $locator = Module::locator();
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $QUERY = $this->getRequest()->query()->toArray();
        $pages = $modelPage->getAllLikeTitle('%' . $QUERY['term'] . '%');
        $data = array();
        foreach($pages as $page){
            $data[] = array(
                'id'=>$page->id,
                'label'=>$page->title,
                'value'=>$page->title,
            );
        }
        return $this->jsonResponse($data);
    }

    public function removeAction()
    {
        $locator = Module::locator();
        $modelLinkBlock = $locator->get('Dots\Db\Model\LinkBlock');
        $QUERY = $this->getRequest()->query()->toArray();
        $link_id = $QUERY['id'];
        $link = $modelLinkBlock->getById($link_id);
        $link->delete();
        return $this->jsonResponse(array('success' => true, 'id' => $link_id));
    }

    public function editAction()
    {
        $locator = Module::locator();
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('Dots\Db\Model\Block');
        $modelLinkBlock = $locator->get('Dots\Db\Model\LinkBlock');
        $QUERY = $this->getRequest()->query()->toArray();
        $link = $modelLinkBlock->getById($QUERY['id']);
        $data = $link->toArray();
        switch($data['type']){
            case 'link':
                $data['link'] = $data['href'];
                break;
            case 'page':
                if ($data['entity_id']){
                    $page = $modelPage->getById($data['entity_id']);
                    $data['page'] = $page->title;
                }
                break;
        }

        $form = new LinkContentForm();
        $form->setElementsBelongTo('link_content[1]');
        $form->addButtons();
        $form->populate($data);

        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTemplate('dots/blocks/links/form');
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveAction()
    {
        $locator = Module::locator();
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('Dots\Db\Model\Block');
        $modelLinkBlock = $locator->get('Dots\Db\Model\LinkBlock');

        $POST = $this->getRequest()->post()->toArray();
        $form = new LinkContentForm();
        $form->setElementsBelongTo('link_content[1]');
        if ($form->isValid($POST['link_content'][1])){
            $data = $form->getValues(true);

            $page = $modelPage->getByAlias($POST['alias']);
            if ($POST['block_id']) {
                $block = $modelBlock->getById($POST['block_id']);
            } else {
                $block = new Block();
                $block->page_id = $page->id;
                $block->section = $POST['section'];
                $block->type = self::TYPE;
                $block->position = $POST['position'];
                $block->save();
            }
            if ($data['id']){
                $linkBlock = $modelLinkBlock->getById($data['id']);
            }else{
                $linkBlock = new LinkBlock();
                $linkBlock->block_id = $block->id;
            }
            if ($linkBlock->type == 'file' && $data['type']!='file' && $linkBlock->href){
                unset($linkBlock->href);
            }
            $linkBlock->type = $data['type'];
            $linkBlock->title = $data['title'];
            $linkBlock->position = $data['position'];
            switch ($linkBlock->type){
                case 'link':
                    $linkBlock->href = $data['link'];
                    break;
                case 'file':
                    if ($data['file']) {
                        if ($linkBlock->href)
                            unset($linkBlock->href);
                        $linkBlock->href = $data['file'];
                    }

                    break;
                case 'page':
                    $selectedPage = $modelPage->getById($data['entity_id']);
                    $linkBlock->entity_id = $data['entity_id'];
                    $linkBlock->href = $selectedPage->alias;
                    break;
            }
            if (!is_numeric($data['position'])){
                $linkBlock->position = 1;
            }
            $linkBlock->save();

            return $this->jsonResponse(array('success' => true, 'data'=>$linkBlock->toArray()));
        }
        return $this->jsonResponse(array(
            'success' => false,
            'errors'=>$form->getMessages()
        ));
    }

    public function moveAction(){
        $blockId = $_REQUEST['block_id'];
        $linkId = $_REQUEST['id'];
        $linkBlockModel = $this->getLocator()->get('Dots\Db\Model\LinkBlock');
        $link = $linkBlockModel->getById($linkId);
        $position = $_REQUEST['position'];
        $oldPosition = $link->position;
        $link->position = $position;
        $links = $linkBlockModel->getAllByColumnsOrderByPosition(array(
            'block_id = ?' => $blockId,
            'id != ? ORDER BY position' => $linkId
        ));

        $pos = 1;
        if ($links){
            foreach ($links as $lnk){
                if ($pos==$position){
                    $linkBlockModel->persist($link); $pos++;
                }
                $lnk->position = $pos++;
                $linkBlockModel->persist($lnk);
            }
        }
        $linkBlockModel->flush();

        return $this->jsonResponse(array('success' => true));
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
        if ($block) {
            $model = $locator->get('Dots\Db\Model\LinkBlock');
            $linkBlocks = $model->getAllByColumns(array(
                'block_id = ? ORDER BY position' => $block->id,
            ));
//            $linkBlocks = $model->getAllByBlockId($block->id);
        } else {
            $block = new Block();
            $block->type = static::TYPE;
            $block->section = $section;
            $linkBlocks = array();
        }
        $form = $this->getForm($linkBlocks);
        return $view->render('dots/blocks/links/edit', array(
            'page'  => $page,
            'block' => $block,
            'form'  => $form,
        ));
    }

    /**
     * Remove links block
     * @param \Zend\EventManager\Event $event
     * @return bool
     */
    public function removeBlock(Event $event)
    {
        $locator = Module::locator();
        $modelLinkBlock = $locator->get('Dots\Db\Model\LinkBlock');
        $block = $event->getTarget();
        $linkBlock = $modelLinkBlock->getByBlockId($block->id);
        $linkBlock->delete();
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

    /**
     * Return a Json Response
     * @param $data
     * @return \Zend\View\Model\JsonModel
     */
    private function jsonResponse($data)
    {
        return new JsonModel($data);
    }

    /**
     * Get the form used for editing links
     * @param null $linkBlocks
     * @return \Dots\Form\MultiForm
     */
    public function getForm($linkBlocks = null)
    {
        $locator = Module::locator();
        $view = $locator->get('view');
        $form = new MultiForm(array());
        $form->setParams(array(
            'links' => $linkBlocks
        ));
        $form->setView($view);
        $form->setDecorators(array(
            array('ViewScript', array('viewScript'=>'dots/blocks/links/edit-form'))
        ));
        return $form;
    }

}