<?php
namespace Dots\Block\Handler;
use Zend\EventManager\EventManagerInterface,
    Zend\EventManager\Event,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel,

    Dots\Module,
    Dots\Db\Entity\Block,
    Dots\Db\Entity\NavigationBlock,
    Dots\Form\MultiForm,
    Dots\Form\Block\NavigationContentForm,
    Dots\Block\ContentHandler,
    Dots\Block\HandlerAware;

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
            $this->handler = new ContentHandler(static::TYPE, 'Navigation');
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
        $view->plugin('headScript')->appendFile('/assets/dots/js/blocks/nav.js');
        $view->plugin('headScript')->appendScript(<<<END
    Dots.Blocks.Nav.init();
END
);
    }

    /**
     * Render navigation block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $locator = Module::getServiceLocator();
        $model   = $locator->get('Dots\Db\Model\NavigationBlock');
        $block   = $event->getTarget();
        $page    = $event->getParam('page');
        $items   = $model->getAllByBlockIdOrderByPosition($block->id);
        return $this->renderViewModel('dots/blocks/navigation/render', array(
            'page' => $page,
            'block' => $block,
            'items' => $items,
            'handler' => $this,
        ));
    }

    public function getPagesAction()
    {
        $locator = Module::getServiceLocator();
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
        $locator = Module::getServiceLocator();
        $modelNavBlock = $locator->get('Dots\Db\Model\NavigationBlock');
        $QUERY = $this->getRequest()->query()->toArray();
        $nav_id = $QUERY['id'];
        $nav = $modelNavBlock->getById($nav_id);
        $nav->delete();
        return $this->jsonResponse(array('success' => true, 'id' => $nav_id));
    }

    public function addAction()
    {
        $form = $this->getEditForm();
        return $this->renderViewModel('dots/blocks/navigation/item-add', array('form' => $form) );
    }

    public function editAction()
    {
        $locator = Module::getServiceLocator();
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('Dots\Db\Model\Block');
        $modelNavBlock = $locator->get('Dots\Db\Model\NavigationBlock');
        $QUERY = $this->getRequest()->query()->toArray();
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
        $form->populate($data);
        return $this->renderViewModel('dots/blocks/navigation/item-edit', array('form' => $form));
    }

    public function saveAction()
    {
        $locator = Module::getServiceLocator();
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('Dots\Db\Model\Block');
        $modelNavBlock = $locator->get('Dots\Db\Model\NavigationBlock');

        $POST = $this->getRequest()->post()->toArray();
        $form = $this->getEditForm();
        if ($form->isValid($POST['navigation'])){
            $data = $form->getSubForm('navigation')->getValues(true);

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
        $navBlockModel = $this->getServiceLocator()->get('Dots\Db\Model\NavigationBlock');
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
        $locator = Module::getServiceLocator();
        $block = $event->getTarget();
        $page = $event->getParam('page');
        $section = $event->getParam('section');
        if ($block) {
            $model = $locator->get('Dots\Db\Model\NavigationBlock');
            $navigationBlocks = $model->getAllByBlockIdOrderByPosition( $block->id);
        } else {
            $block = new Block();
            $block->type = static::TYPE;
            $block->section = $section;
            $navigationBlocks = array();
        }
        $form = $this->getForm($navigationBlocks);
        return $this->renderViewModel('dots/blocks/navigation/edit', array(
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
        $locator = Module::getServiceLocator();
        $modelNavigationBlock = $locator->get('Dots\Db\Model\NavigationBlock');
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
        $view = Module::getServiceLocator()->get('view');
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
        $locator = Module::locator();
        $view = $locator->get('view');
        $form = new MultiForm(array());
        $form->setParams(array(
            'items' => $navigationBlocks
        ));
        $form->setView($view);
        $form->setDecorators(array(
            array('ViewScript', array('viewScript'=>'dots/blocks/navigation/edit-form'))
        ));
        return $form;
    }

    public function getEditForm($data = null)
    {
        $locator = Module::locator();
        $view = $locator->get('view');
        $navForm = new NavigationContentForm();
        $form = new MultiForm(array('navigation'=>$navForm));
        $form->setView($view);
        if ($data!==null)
            $form->populate($data);
        return $form;
    }

}