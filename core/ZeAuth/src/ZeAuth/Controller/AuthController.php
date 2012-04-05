<?php
namespace ZeAuth\Controller;

// GLOBAL REQUIREMENTS
use Zend\Mvc\Controller\ActionController,
// CLOSED REQUIREMENTS
    ZeAuth\Service\Auth,
    ZeAuth\Module;

class AuthController extends ActionController
{
    /**
     * Login action. Returns an array that contains the login form.
     * @return array
     */
    public function indexAction()
    {
        // Get the login form and authentication service
        $homeRoute = Module::getOption('home_route');
        $form = $this->getLocator()->get('ze-auth-form_login');
        $service = $this->getLocator()->get('ze-auth-service_auth');
        // If the form is valid
        if ($this->request->isPost()){
            if ($form->isValid($this->request->post()->toArray())){
                // Login the user and redirect to the home route
                $data = $this->request->post()->toArray();
                $result = $service->login($data);
                if ( $result === true ){
                    $this->redirect()->toRoute($homeRoute);
                } else {
                    foreach($result as $key=>$error){
                        $form->$key->addError($error);
                    }
                }
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
        $service = $this->getLocator()->get('ze-auth-service_auth');
        $service->logout();
        $this->redirect()->toRoute('ze-auth');
    }

}