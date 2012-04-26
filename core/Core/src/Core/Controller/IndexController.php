<?php
namespace Core\Controller;

use Zend\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    public function indexAction()
    {
        $db = $this->locator->get('zedb');
        $model = $db->get('Dots\Db\Entity\Block');
        return array();
    }
}
