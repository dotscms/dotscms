<?php

namespace Core\Controller;

use Zend\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    public function indexAction()
    {
        $db = $this->locator->get('zedb');

        $model = $db->get('Core\Db\Entity\User');
        $user = $model->get(1);
        var_dump($user);
//        $model->persist($user);
//        $model->flush();
        return array();
    }

    public function displayThisAction()
    {
        return array();
    }

    public function restrictedAction()
    {
        return array();
    }
}
