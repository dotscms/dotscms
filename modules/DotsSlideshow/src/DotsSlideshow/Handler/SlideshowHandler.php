<?php
namespace DotsSlideshow\Handler;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;

use Dots\Registry;
use DotsBlock\Db\Entity\Block;
use DotsBlock\ContentHandler;
use DotsBlock\HandlerInterface;
use DotsSlideshow\Db\Entity\SlideshowBlock;
use DotsSlideshow\Db\Entity\SlideshowImage;

class SlideshowHandler implements HandlerInterface
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
        $events->getSharedManager()->attach('dots', 'admin.body.inline', array($this, 'initTemplates'), $priority);
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
        if (!$this->handler) {
            $this->handler = new ContentHandler(static::TYPE, 'Slideshow');
        }
        return $this->handler;
    }

    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-slideshow/handler/templates');
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
        $slideshowImages = $slideshowImagesModel->getAllByBlockSlideshowId($slideshowBlock->id);
        return $this->renderViewModel('dots-slideshow/handler/render', array(
            'page' => $page,
            'block' => $block,
            'slideshowBlock' => $slideshowBlock,
            'images' => $slideshowImages
        ));
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
     * Render edit slideshow block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function editBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $view = $locator->get('DotsTwigViewRenderer');
        $block = $event->getTarget();
        $page = $event->getParam('page');
        if ($block->id) {
            $model = $locator->get('DotsSlideshow\Db\Model\SlideshowBlock');
            $slideshowBlock = $model->getByBlockId($block->id);
        } else {
            $slideshowBlock = new SlideshowBlock();
        }
        if ($slideshowBlock->id) {
            $slideshowImagesModel = $locator->get('DotsSlideshow\Db\Model\SlideshowImage');
            $slideshowImages = $slideshowImagesModel->getAllByBlockSlideshowIdOrderByOrder($slideshowBlock->id);
        } else {
            $slideshowImages = array();
        }
        $slideshowEffects = array("sliceDown", "sliceDownLeft", "sliceUp", "sliceUpLeft", "sliceUpDown", "sliceUpDownLeft", "fold", "fade", "random", "slideInRight", "slideInLeft", "boxRandom", "boxRain", "boxRainReverse", "boxRainGrow", "boxRainGrowReverse");
        $slideshowThemes = array("default","bar","dark","light");
        return $this->renderViewModel('dots-slideshow/handler/edit', array(
            'page' => $page,
            'block' => $block,
            'slideshowBlock' => $slideshowBlock,
            'slideshowImages' => $slideshowImages,
            "slideshowEffects" => $slideshowEffects,
            'slideshowThemes' => $slideshowThemes
        ));
    }


    /**
     * Save the slideshow block
     * @param \Zend\EventManager\Event $event
     * @return array|\DotsBlock\Db\Entity\Block|object|string
     */
    public function saveBlock(Event $event)
    {
        $request = $event->getParam('request');
        $block = $event->getTarget();
        $locator = Registry::get('service_locator');
        $post = $request->getPost()->toArray();
        $slideshow = $post['slideshow'];
        $images = $post['images'];
        $modelSlideshowBlock = $locator->get('DotsSlideshow\Db\Model\SlideshowBlock');
        $modelSlideshowImage = $locator->get('DotsSlideshow\Db\Model\SlideshowImage');
        if ($block->id) {
            $slideshowBlock = $modelSlideshowBlock->getByBlockId($block->id);
        } else {
            $block->save();
            $slideshowBlock = new SlideshowBlock();
            $slideshowBlock->block_id = $block->id;
        }
        $slideshowBlock->effect = $slideshow['effect'];
        $slideshowBlock->animSpeed = $slideshow['animSpeed'];
        $slideshowBlock->pauseTime = $slideshow['pauseTime'];
        $slideshowBlock->theme = $slideshow['theme'];
        $slideshowBlock->save();
        foreach($images as $image){
            if($image['id']){
                $slideshowImage = $modelSlideshowImage->getById($image['id']);
            }else{
                $slideshowImage = new SlideshowImage();
            }
            $slideshowImage->block_slideshow_id = $slideshowBlock->id;
            $slideshowImage->src = $image['filename'];
            $slideshowImage->order = $image['order'];
            $slideshowImage->caption = $image['caption'];
            $slideshowImage->save();
        }
        return $block;
    }

    /**
     * Remove slide show block
     * @param \Zend\EventManager\Event $event
     * @return bool
     */
    public function removeBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $appConfig = $locator->get('ApplicationConfig');
        $config = $locator->get('Configuration');
        $modelSlideshowBlock = $locator->get('DotsSlideshow\Db\Model\SlideshowBlock');
        $modelSlideshowImage = $locator->get('DotsSlideshow\Db\Model\SlideshowImage');
        $block = $event->getTarget();
        $slideshowBlock = $modelSlideshowBlock->getByBlockId($block->id);
        $images = $modelSlideshowImage->getAllByBlockSlideshowId($slideshowBlock->id);
        $slideshowBlock->delete();
        $block->delete();
        if(!empty($images)){
            foreach($images as $image){
                $image->delete();
                $filename = $appConfig['public_path'].'/'. $config['dots_slideshow']['image_path'] .$image->src;
                unlink($filename);
            }
        }
        return true;
    }
}
