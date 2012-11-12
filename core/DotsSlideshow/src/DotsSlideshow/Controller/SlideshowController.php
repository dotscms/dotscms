<?php
namespace DotsSlideshow\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Encoder;
use Zend\View\Model\JsonModel;

use Dots\Registry;
use Dots\Form\MultiForm;
use DotsBlock\Db\Entity;
use DotsSlideshow\Db\Entity\SlideshowImage;
use DotsBlock\Form\Setting\DefaultBlockSettingsForm;
use DotsSlideshow\Handler\UploadHandler;

class SlideshowController extends AbstractActionController
{
    public function uploadAction()
    {
        $upload_handler = new UploadHandler(
            array('upload_dir' => IMAGE_PATH, 'upload_url' => '/data/uploads/'), false
        );
        $response = $upload_handler->post(false);

        return $this->jsonResponse($response);

    }

    public function deleteImageAction()
    {
        $locator = Registry::get('service_locator');
        $post = $this->getRequest()->getPost()->toArray();
        $modelSlideshowImage = $locator->get('DotsSlideshow\Db\Model\SlideshowImage');
        if($post['id']){
            $modelSlideshowImage->removeById($post['id']);
        }
        $filename = IMAGE_PATH."/".$post['filename'];
        $deleted = unlink($filename);
        return $this->jsonResponse(array("success"=>$deleted));
    }

    /**
     * Create a json response based on the data
     * @param $data
     * @return \Zend\Stdlib\ResponseDescription
     */
    private function jsonResponse($data)
    {
        return new JsonModel($data);
    }
}
