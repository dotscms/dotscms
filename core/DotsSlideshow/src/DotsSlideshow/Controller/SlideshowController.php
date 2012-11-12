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
