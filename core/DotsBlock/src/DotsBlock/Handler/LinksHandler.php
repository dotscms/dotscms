<?php
namespace DotsBlock\Handler;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Dots\Registry;
use Dots\Form\MultiForm;
use Dots\File\Upload;
use Dots\EventManager\GlobalEventManager;
use DotsBlock\Db\Entity\Block;
use DotsBlock\Db\Entity\LinkBlock;
use DotsBlock\Form\Content\Link as LinkContentForm;
use DotsBlock\ContentHandler;
use DotsBlock\HandlerAware;

/**
 * Links content block handler and Controller
 */
class LinksHandler extends AbstractActionController implements HandlerAware
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
    public function attach(EventManagerInterface $events, $priority = 100)
    {
        GlobalEventManager::attach('admin.head.pre', array($this, 'initHeaders'), $priority);
        GlobalEventManager::attach('admin.body.inline', array($this, 'initTemplates'), $priority);
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

    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-block/handler/links/templates');
    }

    /**
     * Add code in the header section of the page
     * @param \Zend\EventManager\Event $event
     */
    public function initHeaders(Event $event)
    {
        $view = $event->getTarget();
        $view->plugin('headScript')->appendFile('/assets/dots/js/blocks/links.js');
    }

    /**
     * Render html block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $model   = $locator->get('DotsBlock\Db\Model\LinkBlock');
        $block   = $event->getTarget();
        $page    = $event->getParam('page');
        $links   = $model->getAllByBlockIdOrderByPosition($block->id);
        return $this->renderViewModel('dots-block/handler/links/render', array(
            'page' => $page,
            'block' => $block,
            'links' => $links,
        ));
    }

    public function addAction()
    {
        $form = new LinkContentForm();
        $form->setName('link_content[1]');
        $form->addButtons();
        $output = $this->renderViewModel('dots-block/handler/links/form', array('form' => $form));
        $this->getResponse()->setContent($output);
        return $this->getResponse();
    }

    public function getPagesAction()
    {
        $locator = Registry::get('service_locator');
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $QUERY = $this->getRequest()->getQuery()->toArray();
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
        $locator = Registry::get('service_locator');
        $modelLinkBlock = $locator->get('DotsBlock\Db\Model\LinkBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $link_id = $QUERY['id'];
        $link = $modelLinkBlock->getById($link_id);
        $link->delete();
        return $this->jsonResponse(array('success' => true, 'id' => $link_id));
    }

    public function editAction()
    {
        $locator = Registry::get('service_locator');
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('DotsBlock\Db\Model\Block');
        $modelLinkBlock = $locator->get('DotsBlock\Db\Model\LinkBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
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
        $form->setName('link_content[1]');
        $form->addButtons();
        $form->setData($data);

        $response = $this->renderViewModel('dots-block/handler/links/form', array('form' => $form));
        $this->getResponse()->setContent($response);

        return $this->getResponse();
    }

    public function saveAction()
    {
        $locator = Registry::get('service_locator');
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('DotsBlock\Db\Model\Block');
        $modelLinkBlock = $locator->get('DotsBlock\Db\Model\LinkBlock');

        $POST = $this->getRequest()->getPost()->toArray();
        $FILES = $this->getRequest()->getFiles()->toArray();
        $form = new LinkContentForm();
        $form->setName('link_content[1]');
        if (!empty($FILES)){
            $POST['link_content'][1]['file'] = $FILES['link_content'][1]['file'];
        }
        $form->setData($POST['link_content'][1]);
        if ($form->isValid()){
            $data = $form->getInputFilter()->getValues();
//            $data = $form->getValues(true);

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
                    $FILES = $this->getRequest()->getFiles();
                    $upload = new Upload(array(
                        'path' => '/data/uploads/',
                        'destination' => PUBLIC_PATH
                    ));
                    $path = $upload->process(array('file'=>$POST['link_content'][1]['file']));
                    $data['file'] = $path['file'];

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
        $linkBlockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\LinkBlock');
        $link = $linkBlockModel->getById($linkId);
        $position = $_REQUEST['position'];
        $oldPosition = $link->position;
        $link->position = $position;
        $links = $linkBlockModel->getAllByColumnsOrderByPosition(array(
            'block_id = ?' => $blockId,
            'id != ?' => $linkId
        ));

        $pos = 1;
        if ($links){
            foreach ($links as $lnk){
                if ($pos==$position){
                    $pos++;
                }
                $lnk->position = $pos++;
                $linkBlockModel->persist($lnk);
            }
        }
        $linkBlockModel->persist($link);
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
        $locator = Registry::get('service_locator');
        $block = $event->getTarget();
        $page = $event->getParam('page');
        if ($block->id) {
            $model = $locator->get('DotsBlock\Db\Model\LinkBlock');
            $linkBlocks = $model->getAllByBlockIdOrderByPosition( $block->id);
        } else {
            $linkBlocks = array();
        }

        $form = $this->getForm($linkBlocks);
        return $this->renderViewModel('dots-block/handler/links/edit-form', array(
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
        $locator = Registry::get('service_locator');
        $modelLinkBlock = $locator->get('DotsBlock\Db\Model\LinkBlock');
        $block = $event->getTarget();
        $linkBlock = $modelLinkBlock->getByBlockId($block->id);
        if ($linkBlock){
            $linkBlock->delete();
        }
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

    private function renderViewModel($template = null, $vars = array(), $render=true)
    {
        $view = Registry::get('service_locator')->get('view');
        $viewModel = new ViewModel($vars, array(
            'has_parent' => true
        ));
        $viewModel->setTemplate($template)
            ->setTerminal(true);
        if ($render){
            return $view->render($viewModel);
        }
        return $viewModel;
    }

    /**
     * Get the form used for editing links
     * @param null $linkBlocks
     * @return \Dots\Form\MultiForm
     */
    public function getForm($linkBlocks = null)
    {
        $locator = Registry::get('service_locator');
        $form = new MultiForm(array());
        $form->setParams(array(
            'links' => $linkBlocks
        ));
        return $form;
    }

}