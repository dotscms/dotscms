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
use DotsBlock\Db\Entity\NavigationBlock;
use DotsBlock\Form\Content\Navigation as NavigationContentForm;
use DotsBlock\ContentHandler;
use DotsBlock\HandlerAware;

/**
 * Navigation content block handler and Controller
 */
class NavigationHandler extends AbstractActionController implements HandlerAware
{
    /**
     * Block type
     */
    const TYPE = 'navigation';
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
     * @param int $priority
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
            $this->handler = new ContentHandler(static::TYPE, 'Navigation');
        }
        return $this->handler;
    }

    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-block/handler/navigation/templates');
    }

    /**
     * Add code in the header section of the page
     * @param \Zend\EventManager\Event $event
     */
    public function initHeaders(Event $event)
    {
        $view = $event->getTarget();
        $view->plugin('headScript')->appendFile('/assets/dots/js/blocks/nav.js');
    }

    /**
     * Render navigation block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $model   = $locator->get('DotsBlock\Db\Model\NavigationBlock');
        $block   = $event->getTarget();
        $page    = $event->getParam('page');
        $items   = $model->getAllByBlockIdOrderByPosition($block->id);
        return $this->renderViewModel('dots-block/handler/navigation/render', array(
            'page' => $page,
            'block' => $block,
            'items' => $items,
            'handler' => $this,
        ));
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
        $modelNavBlock = $locator->get('DotsBlock\Db\Model\NavigationBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $nav_id = $QUERY['id'];
        $nav = $modelNavBlock->getById($nav_id);
        $nav->delete();
        return $this->jsonResponse(array('success' => true, 'id' => $nav_id));
    }

    public function addAction()
    {
        $form = $this->getEditForm();
        $response = $this->getResponse();
        $content = $this->renderViewModel('dots-block/handler/navigation/item-add', array('form' => $form));
        $response->setContent($content);
        return $response;
    }

    public function editAction()
    {
        $locator = Registry::get('service_locator');
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('DotsBlock\Db\Model\Block');
        $modelNavBlock = $locator->get('DotsBlock\Db\Model\NavigationBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $nav = $modelNavBlock->getById($QUERY['id']);
        $data = $nav->toArray();
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

        $form = $this->getEditForm();
        $form->setData(array('navigation'=>$data));
        $response = $this->getResponse();
        $content = $this->renderViewModel('dots-block/handler/navigation/item-edit', array('form' => $form));
        $response->setContent($content);
        return $response;
    }

    public function saveAction()
    {
        $locator = Registry::get('service_locator');
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('DotsBlock\Db\Model\Block');
        $modelNavBlock = $locator->get('DotsBlock\Db\Model\NavigationBlock');

        $POST = $this->getRequest()->getPost()->toArray();
        $form = $this->getEditForm();
        $form->setData($POST);
        if ($form->isValid()){
            $data = $form->getInputFilter()->getValues();
            $data = $data['navigation'];
//            $data = $form->getSubForm('navigation')->getValues(true);

            $page = $modelPage->getByAlias($POST['alias']);
            if (array_key_exists('block_id', $POST) && $POST['block_id']) {
                $block = $modelBlock->getById($POST['block_id']);
            } else {
                $block = new Block();
                $block->page_id = $page->id;
                $block->section = $POST['section'];
                $block->type = self::TYPE;
                $block->position = $POST['position'];
                $block->save();
            }
            if (array_key_exists('id', $data) && $data['id']){
                $navBlock = $modelNavBlock->getById($data['id']);
            }else{
                $navBlock = new NavigationBlock();
                $navBlock->block_id = $block->id;
            }
            if ($navBlock->type == 'file' && $data['type']!='file' && $navBlock->href){
                unset($navBlock->href);
            }
            $navBlock->type = $data['type'];
            $navBlock->title = $data['title'];
            $navBlock->position = $data['position'];
            switch ($navBlock->type){
                case 'link':
                    $navBlock->href = $data['link'];
                    break;
                case 'page':
                    $selectedPage = $modelPage->getById($data['entity_id']);
                    $navBlock->entity_id = $data['entity_id'];
                    $navBlock->href = $selectedPage->alias;
                    break;
            }
            if (!is_numeric($data['position'])){
                $navBlock->position = 1;
            }
            $navBlock->save();

            return $this->jsonResponse(array('success' => true, 'data'=> $navBlock->toArray()));
        }
        return $this->jsonResponse(array(
            'success' => false,
            'errors'=>$form->getMessages()
        ));
    }

    public function moveAction(){
        $blockId = $_REQUEST['block_id'];
        $navId = $_REQUEST['id'];
        $navBlockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\NavigationBlock');
        $nav = $navBlockModel->getById($navId);
        $position = $_REQUEST['position'];
        $oldPosition = $nav->position;
        $nav->position = $position;
        $items = $navBlockModel->getAllByColumnsOrderByPosition(array(
            'block_id = ?' => $blockId,
            'id != ?' => $navId
        ));

        $pos = 1;
        if ($items){
            foreach ($items as $itm){
                if ($pos==$position){
                    $pos++;
                }
                $itm->position = $pos++;
                $navBlockModel->persist($itm);
            }
        }
        $navBlockModel->persist($nav);
        $navBlockModel->flush();

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
            $model = $locator->get('DotsBlock\Db\Model\NavigationBlock');
            $navigationBlocks = $model->getAllByBlockIdOrderByPosition( $block->id);
        } else {
            $navigationBlocks = array();
        }
        $form = $this->getForm($navigationBlocks);
        return $this->renderViewModel('dots-block/handler/navigation/edit-form', array(
            'page'  => $page,
            'block' => $block,
            'form'  => $form,
        ));
    }

    /**
     * Remove navigation block
     * @param \Zend\EventManager\Event $event
     * @return bool
     */
    public function removeBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $modelNavigationBlock = $locator->get('DotsBlock\Db\Model\NavigationBlock');
        $block = $event->getTarget();
        $navBlock = $modelNavigationBlock->removeByBlockId($block->id);
        $block->delete();
        return true;
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

    private function renderViewModel($template = null, $vars = array())
    {
        $view = Registry::get('service_locator')->get('view');
        $viewModel = new ViewModel($vars, array('has_parent' => true));
        $viewModel->setTemplate($template)
            ->setTerminal(true);
        return $view->render($viewModel);
    }

    /**
     * Get the form used for editing navigation links
     * @param null $navigationBlocks
     * @return \Dots\Form\MultiForm
     */
    public function getForm($navigationBlocks = null)
    {
        $locator = Registry::get('service_locator');
        $view = $locator->get('view');
        $form = new MultiForm(array());
        $form->setParams(array(
            'items' => $navigationBlocks
        ));
        return $form;
    }

    public function getEditForm($data = null)
    {
        $locator = Registry::get('service_locator');
        $view = $locator->get('view');
        $navForm = new NavigationContentForm();
        $form = new MultiForm(array('navigation'=>$navForm));
        if ($data!==null)
            $form->setData($data);
        return $form;
    }

}