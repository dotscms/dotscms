<?php
namespace DotsBlock\Handler;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;
use Zend\Filter\File\Rename as RenameFilter;

use Dots\Registry;
use Dots\Form\MultiForm;
use Dots\EventManager\GlobalEventManager;
use Dots\File\Upload;
use DotsBlock\Db\Entity\Block;
use DotsBlock\Db\Entity\ImageBlock;
use DotsBlock\Form\Content\Image as ImageContentForm;
use DotsBlock\ContentHandler;
use DotsBlock\HandlerAware;
use DotsBlock\Thumbs\PhpThumbFactory;

/**
 * Image Handler block handler
 */
class ImageHandler implements HandlerAware
{
    /**
     * Block Type
     */
    const TYPE = 'image_content';
    /**
     * Listener containers
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
     * @param $priority
     */
    public function attach(EventManagerInterface $events, $priority = 100)
    {
        GlobalEventManager::attach('admin.head.pre', array($this, 'initHeaders'), $priority);
        GlobalEventManager::attach('admin.body.inline', array($this, 'initTemplates'), $priority);
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
            $this->handler = new ContentHandler(static::TYPE, 'Image Content');
        }
        return $this->handler;
    }

    public function initTemplates(Event $event)
    {
        return $this->renderViewModel('dots-block/handler/image/templates');
    }

    /**
     * Initialize the headers for the handler
     * @param \Zend\EventManager\Event $event
     */
    public function initHeaders(Event $event)
    {
        $view = $event->getTarget();
        $view->plugin('headLink')->appendStylesheet('/assets/img_crop/css/imgareaselect-default.css');
        $view->plugin('headScript')->appendFile('/assets/img_crop/scripts/jquery.imgareaselect.js');
    }

    /**
     * Render image block
     * @param \Zend\EventManager\Event $event
     * @return mixed
     */
    public function renderBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $block = $event->getTarget();
        $page = $event->getParam('page');
        $model = $locator->get('DotsBlock\Db\Model\ImageBlock');
        $imageBlock = $model->getByBlockId($block->id);
        return $this->renderViewModel('dots-block/handler/image/render', array(
            'page' => $page,
            'block' => $block,
            'imageBlock' => $imageBlock,
        ));
    }

    /**
     * Render edit image block
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
            $model = $locator->get('DotsBlock\Db\Model\ImageBlock');
            $imageBlock = $model->getByBlockId($block->id);
        }else{
            $imageBlock = new ImageBlock();
        }
        $form = new MultiForm(array(
            'image_content' => new ImageContentForm($imageBlock)
        ));
        $form->addButtons();
        $form->setData(array('image_content'=>$imageBlock->toArray()));
        return $this->renderViewModel('dots-block/handler/image/edit', array(
            'page' => $page,
            'block' => $block,
            'imageBlock' => $imageBlock,
            'form' => $form,
        ));
    }

    /**
     * Save the image block and create a cropped image if necessary
     * @param \Zend\EventManager\Event $event
     * @return array|\Dots\Db\Entity\Block|object|string
     */
    public function saveBlock(Event $event)
    {
        $request = $event->getParam('request');
        $locator = Registry::get('service_locator');
        $modelImageBlock = $locator->get('DotsBlock\Db\Model\ImageBlock');
        $block = $event->getTarget();
        $form = new MultiForm(array(
            'image_content' => new ImageContentForm()
        ));
        $data = array(
            'image_content' => $request->getPost()
        );

        $files = $request->getFiles();

        $data['image_content']['original_src'] = $files['original_src']['name'];
        $form->setData($data);

        if ($form->isValid()){
            $data = $form->getInputFilter()->getValues();

            if ($block->id) {
                $imageBlock = $modelImageBlock->getByBlockId($block->id);
            } else {
                $block->save();
                $imageBlock = new ImageBlock();
                $imageBlock->block_id = $block->id;
            }

            $imageBlock->alt = $data['image_content']['alt'];
            $imageBlock->display_width = $data['image_content']['display_width'];
            $imageBlock->display_height = $data['image_content']['display_height'];
            $editedCrop = (
                $imageBlock->crop_x1 != $data['image_content']['crop_x1']
                || $imageBlock->crop_y1 != $data['image_content']['crop_y1']
                || $imageBlock->crop_x2 != $data['image_content']['crop_x2']
                || $imageBlock->crop_y2 != $data['image_content']['crop_y2']
            );
            $imageBlock->crop_x1 = $data['image_content']['crop_x1'];
            $imageBlock->crop_y1 = $data['image_content']['crop_y1'];
            $imageBlock->crop_x2 = $data['image_content']['crop_x2'];
            $imageBlock->crop_y2 = $data['image_content']['crop_y2'];

            if (!empty($files['original_src']['tmp_name'])){
                $upload = new Upload(array(
                    'path' => '/data/uploads/',
                    'destination' => PUBLIC_PATH
                ));
                $path = $upload->process($files);
                $data['image_content']['original_src'] = $path['original_src'];
            }

            if ( !($imageBlock->id && empty($data['image_content']['original_src'])) ){
                // success - do something with the uploaded file
                $fullFilePath = $data['image_content']['original_src'];
                if ($imageBlock->original_src){
                    unlink(PUBLIC_PATH.$imageBlock->original_src);
                }
                if ($imageBlock->src != $imageBlock->original_src) {
                    unlink(PUBLIC_PATH . $imageBlock->src);
                }
                $imageBlock->original_src = $fullFilePath;
                $imageBlock->src = $fullFilePath;
                $thumb = PhpThumbFactory::create(PUBLIC_PATH . $imageBlock->original_src);
                $dimensions = $thumb->getCurrentDimensions();
                $imageBlock->width = $dimensions['width'];
                $imageBlock->height = $dimensions['height'];
            }

            if ($editedCrop){
                if ($imageBlock->src != $imageBlock->original_src) {
                    unlink(PUBLIC_PATH . $imageBlock->src);
                }
                if ($imageBlock->crop_x1 !== "" && $imageBlock->crop_y1 !== "" && $imageBlock->crop_x2 !== "" && $imageBlock->crop_y2 !== "") {
                    $thumb = PhpThumbFactory::create(PUBLIC_PATH . $imageBlock->original_src);
                    if ($imageBlock->width && $imageBlock->height){
                        $w = $imageBlock->width;
                        $h = $imageBlock->height;
                    }else{
                        $dimensions = $thumb->getCurrentDimensions();
                        $imageBlock->width = $w = $dimensions['width'];
                        $imageBlock->height = $h = $dimensions['height'];
                    }

                    $x1 = round($imageBlock->crop_x1 * $w / 100);
                    $y1 = round($imageBlock->crop_y1 * $h / 100);
                    $x2 = round($imageBlock->crop_x2 * $w / 100);
                    $y2 = round($imageBlock->crop_y2 * $h / 100);
                    $thumb->crop($x1, $y1, $x2 - $x1, $y2 - $y1);
                    $filename = basename($imageBlock->original_src);
                    $filename = substr($filename, 0, strrpos($filename, '.')) . '.jpg';
                    $filename = '/data/uploads/edited/' . uniqid(rand()) . '_' . $filename;
                    $thumb->save(PUBLIC_PATH . $filename, 'jpg');
                    $imageBlock->src = $filename;
                } else {
                    $imageBlock->src = $imageBlock->original_src;
                }
            }

            $imageBlock->save();
            return $block;
        }
        $event->stopPropagation();
        $errors = $form->getMessages();
        return $errors;
    }

    /**
     * Remove the image block
     * @param \Zend\EventManager\Event $event
     * @return bool
     */
    public function removeBlock(Event $event)
    {
        $locator = Registry::get('service_locator');
        $modelImageBlock = $locator->get('DotsBlock\Db\Model\ImageBlock');
        $block = $event->getTarget();
        $imageBlock = $modelImageBlock->getByBlockId($block->id);
        if ($imageBlock){
            if ($imageBlock->original_src){
                unlink(PUBLIC_PATH . $imageBlock->original_src);
            }
            if ($imageBlock->src) {
                unlink(PUBLIC_PATH . $imageBlock->src);
            }
            $imageBlock->delete();
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

    private function renderViewModel($template=null, $vars=array())
    {
        $view = Registry::get('service_locator')->get('view');
        $viewModel = new ViewModel($vars, array('has_parent' => true));
        $viewModel->setTemplate($template)
                  ->setTerminal(true);
        return $view->render($viewModel);
    }

}