<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsNavBlock\Handler;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;

use Dots\Registry;
use Dots\Form\MultiForm;
use Dots\EventManager\GlobalEventManager;
use DotsNavBlock\Db\Entity\NavigationBlock;
use DotsBlock\ContentHandler;
use DotsBlock\HandlerInterface;

/**
 * Navigation content block handler
 */
class NavigationHandler implements HandlerInterface
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
     * Return ContentHandler instance for the navigation block
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
     * Render the templates that should be used on the edit page for navigation
     * @param \Zend\EventManager\Event $event
     * @return string
     */
    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-nav-block/templates');
    }

    /**
     * Render navigation block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $model   = $locator->get('DotsNavBlock\Db\Model\NavigationBlock');
        $block   = $event->getTarget();
        $page    = $event->getParam('page');
        $items   = $model->getAllByBlockIdOrderByPosition($block->id);
        return $this->renderViewModel('dots-nav-block/render', array(
            'page' => $page,
            'block' => $block,
            'items' => $items,
            'handler' => $this,
        ));
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
            $model = $locator->get('DotsNavBlock\Db\Model\NavigationBlock');
            $navigationBlocks = $model->getAllByBlockIdOrderByPosition( $block->id);
        } else {
            $navigationBlocks = array();
        }
        $form = $this->getEditBlockForm($navigationBlocks);
        return $this->renderViewModel('dots-nav-block/edit-form', array(
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
        $modelNavigationBlock = $locator->get('DotsNavBlock\Db\Model\NavigationBlock');
        $block = $event->getTarget();
        $modelNavigationBlock->removeByBlockId($block->id);
        $block->delete();
        return true;
    }

    /**
     * Render a specific template
     * @param null $template
     * @param array $vars
     * @return string
     */
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
    public function getEditBlockForm($navigationBlocks = null)
    {
        $form = new MultiForm(array());
        $form->setParams(array(
            'items' => $navigationBlocks
        ));
        return $form;
    }

}