<?php
namespace DotsSlideshow\Handler;

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
use DotsSlideshow\Db\Entity\SlideshowBlock;
use DotsSlideshow\Db\Entity\SlideshowImage;

class SlideshowHandler implements HandlerAware
{
    /**
     * Block type
     */
    const TYPE = 'slideshow_content';
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
        GlobalEventManager::attach('admin.head.pre', array($this, 'initAdminHeaders'), $priority);
        GlobalEventManager::attach('head.pre',array($this, 'initHeaders'),$priority);
        GlobalEventManager::attach('admin.body.inline', array($this, 'initTemplates'), $priority);
        $this->listeners[] = $events->attach('listHandlers', array($this, 'getHandler'), $priority);
        $this->listeners[] = $events->attach('renderBlock/' . static::TYPE, array($this, 'renderBlock'), $priority);
        $this->listeners[] = $events->attach('editBlock/' . static::TYPE, array($this, 'editBlock'), $priority);
//        $this->listeners[] = $events->attach('saveBlock/' . static::TYPE, array($this, 'saveBlock'), $priority);
//        $this->listeners[] = $events->attach('removeBlock/' . static::TYPE, array($this, 'removeBlock'), $priority);
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
            $this->handler = new ContentHandler(static::TYPE, 'Slideshow');
        }
        return $this->handler;
    }

    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-slideshow/handler/templates');
    }

    /**
     * Add code in the header section of the page
     * @param \Zend\EventManager\Event $event
     */
    public function initHeaders(Event $event)
    {
        $view = $event->getTarget();
        $view->plugin('headScript')->appendFile('/assets/nivo_slider/jquery.nivo.slider.pack.js');
        $view->plugin('headLink')->appendStylesheet('/assets/nivo_slider/nivo-slider.css');
    }

    /**
     * Add code in the admin header section of the page
     * @param \Zend\EventManager\Event $event
     */
    public function initAdminHeaders(Event $event)
    {
        $view = $event->getTarget();
        $view->plugin('headScript')->appendFile('/assets/nivo_slider/jquery.nivo.slider.pack.js');
        $view->plugin('headLink')->appendStylesheet('/assets/nivo_slider/nivo-slider.css');
        $view->plugin('headScript')->appendFile('/assets/file_upload/js/vendor/jquery.ui.widget.js')
            ->appendFile('/assets/file_upload/js/jquery.iframe-transport.js')
            ->appendFile('/assets/file_upload/js/jquery.fileupload.js')
            ->appendFile('/assets/dots_slideshow/slideshow.js');
    }
    /**
     * Render slideshow block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $block = $event->getTarget();
        $page = $event->getParam('page');
        $locator = Registry::get('service_locator');
        $slideshowModel = $locator->get('DotsSlideshow\Db\Model\SlideshowBlock');
        $slideshowBlock = $slideshowModel->getByBlockId($block->id);
        $slideshowImagesModel = $locator->get('DotsSlideshow\Db\Model\SlideshowImage');
        $slideshowImages = $slideshowImagesModel->getAllBySlideshowId($slideshowBlock->id);
        return $this->renderViewModel('dots-slideshow/handler/render', array(
            'page' => $page,
            'block' => $block,
            'slideshowBlock' => $slideshowBlock,
            'images' => $slideshowImages
        ));
    }

    private function renderViewModel($template=null, $vars=array())
    {
        $view = Registry::get('service_locator')->get('view');
        $viewModel = new ViewModel($vars, array('has_parent' => true));
        $viewModel->setTemplate($template)
            ->setTerminal(true);
        return $view->render($viewModel);
    }

    /**
     * Render edit slideshow block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function editBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $view = $locator->get('TwigViewRenderer');
        $block = $event->getTarget();
        $page = $event->getParam('page');
        if ($block->id){
            $model = $locator->get('DotsSlideshow\Db\Model\SlideshowBlock');
            $slideshowBlock = $model->getByBlockId($block->id);
        }else{
            $slideshowBlock = new SlideshowBlock();
        }
        if($slideshowBlock->id){
            $slideshowImagesModel = $locator->get('DotsSlideshow\Db\Model\SlideshowImage');
            $slideshowImages = $slideshowImagesModel->getAllBySlideshowIdSortByOrder($slideshowBlock->id);
        }else{
            $slideshowImages = array();
        }
        $slideshowEffects = array("sliceDown","sliceDownLeft","sliceUp","sliceUpLeft","sliceUpDown","sliceUpDownLeft","fold","fade","random","slideInRight","slideInLeft","boxRandom","boxRain","boxRainReverse","boxRainGrow","boxRainGrowReverse");
        return $this->renderViewModel('dots-slideshow/handler/edit', array(
            'page' => $page,
            'block' => $block,
            'slideshowBlock' => $slideshowBlock,
            'slideshowImages' => $slideshowImages,
            "slideshowEffects" => $slideshowEffects
        ));
    }
}
