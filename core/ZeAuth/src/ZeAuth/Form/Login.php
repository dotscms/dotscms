<?php
namespace ZeAuth\Form;

use Zend\Form as ZForm,
    ZeAuth\Module;

class Login extends ZForm\Form
{
    /**
     * Initialize the user login form
     * @return void
     */
    public function init()
    {
        // Get options from the module configuration
        $identity_type = Module::getOption('identity_type');
        $remember_me = Module::getOption('remember_me');
        $_username = new ZForm\Element(array(
                'name'=>'identity',
                'type'=>'text',
                'required'=>true,
                'label'=>'Username'
            ));
        $_emailAddress = new ZForm\Element(array(
                'name'=>'identity',
                'type'=>'text',
                'required'=>true,
                'label'=>'Email Address'
            ));
        $_usernameOrEmailAddress = new ZForm\Element(array(
                'name'=>'identity',
                'type'=>'text',
                'required'=>true,
                'label'=>'Username / Email'
            ));
        // Identity element (can me either a username or an email address or both)
        switch ($identity_type){
            case 'username':
                $this->identity = $_username;
                break;
            case 'email_address':
                $this->identity = $_emailAddress;
                break;
            case 'both':
                $this->identity = $_usernameOrEmailAddress;
                break;
        }
        // Credential element (the password)
        $this->credential = new ZForm\Element(array(
                'name'=>'credential',
                'type'=>'password',
                'required'=>true,
                'label'=>'Password'
            ));
        // Add remember me element if not disabled
        if ($remember_me){
            $this->remember_me = new ZForm\Element\Checkbox('remember_me',array(
                'type'=>'checkbox',
                'label'=>'Keep me logged in',
                'value'=>'1',
                'decorators' => array(
                    array('ViewHelper'),
                    array('Label', array('placement' => 'APPEND')),
                    array(array('data' => 'HtmlTag'), array('tag' => 'dd')),
                    array('HtmlTag', array('tag' => 'dt')),
                )
            ));
        }
        // Submit button
        $this->submit = new ZForm\Element(array(
                'type'=>'submit',
                'name'=>'submit',
                'ignore'=>true,
                'label'=>'&nbsp;',
                'value'=>'Login'
            ));
    }
}