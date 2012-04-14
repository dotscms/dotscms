<?php
namespace Core\Controller;

use Zend\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    public function indexAction()
    {
        $db = $this->locator->get('zedb');

        $model = $db->get('ZeAuth\Db\Entity\User');
        $user = $model->get(1);
        $user->username = 'admin2';
//        var_dump($this->plugin('auth')->user());
//        $model->persist($user);
//        $model->flush();
        return array();
    }
}
