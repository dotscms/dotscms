<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class AuthController extends AbstractActionController
{
    /**
     * Login action. Returns an array that contains the login form.
     * @return array
     */
    public function indexAction()
    {
        // Get the login form and authentication service
        $service = $this->getServiceLocator()->get('ZeAuth');
        $homeRoute = $service->getHomeRoute();
        $loginFilter = $this->getServiceLocator()->get('ZeLoginInputFilter');
        $form = $service->getLoginForm();
        $form->setInputFilter($loginFilter);
        // If the form is valid
        if ($this->request->isPost()){
            $data = $this->request->getPost()->toArray();
            $form->setData($data);
            if ($form->isValid()){
                // Login the user and redirect to the home route
                $service->login($data);
                return $this->redirect()->toRoute($homeRoute);
            }
        }
        // Return the form and display it in the view
        return array(
            'form' => $form
        );
    }

    /**
     * Logout action. Clear the user authentication and logout the user.
     * @return void
     */
    public function logoutAction()
    {
        $service = $this->getServiceLocator()->get('ZeAuth');
        $service->logout();
        return $this->redirect()->toRoute('ze-auth');
    }

}