<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsLinkBlock\Handler;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;

use Dots\Registry;
use Dots\Form\MultiForm;
use DotsLinkBlock\Db\Entity\LinkBlock;
use DotsBlock\ContentHandler;
use DotsBlock\HandlerInterface;

/**
 * Links content block handler
 */
class LinksHandler implements HandlerInterface
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
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 100)
    {
        $events->getSharedManager()->attach('dots', 'admin.body.inline', array($this, 'initTemplates'), $priority);
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
            $this->handler = new ContentHandler(static::TYPE, 'Files & Links');
        }
        return $this->handler;
    }

    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-link-block/templates');
    }

    /**
     * Render html block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $model   = $locator->get('DotsLinkBlock\Db\Model\LinkBlock');
        $block   = $event->getTarget();
        $page    = $event->getParam('page');
        $links   = $model->getAllByBlockIdOrderByPosition($block->id);
        return $this->renderViewModel('dots-link-block/render', array(
            'page' => $page,
            'block' => $block,
            'links' => $links,
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
            $model = $locator->get('DotsLinkBlock\Db\Model\LinkBlock');
            $linkBlocks = $model->getAllByBlockIdOrderByPosition( $block->id);
        } else {
            $linkBlocks = array();
        }

        $form = $this->getForm($linkBlocks);
        return $this->renderViewModel('dots-link-block/edit-form', array(
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
        $modelLinkBlock = $locator->get('DotsLinkBlock\Db\Model\LinkBlock');
        $block = $event->getTarget();
        $linkBlock = $modelLinkBlock->getByBlockId($block->id);
        if ($linkBlock){
            $linkBlock->delete();
        }
        $block->delete();
        return true;
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
        $form = new MultiForm(array());
        $form->setParams(array(
            'links' => $linkBlocks
        ));
        return $form;
    }

}