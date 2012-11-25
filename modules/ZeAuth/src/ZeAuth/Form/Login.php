<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\Validator\EmailAddress;

class Login extends Form
{
    protected $loginOptions = array();

    public function __construct($config)
    {
        parent::__construct('login');
        $this->loginOptions = array(
            'identity_type' => $config['identity_type'],
            'remember_me'   => $config['remember_me'],
        );
        $this->setAttribute('method', 'post');
    }

    /**
     * Initialize the user login form
     * @return void
     */
    public function prepare()
    {
        if (!$this->isPrepared) {
            $identity_type = $this->loginOptions['identity_type'];
            $remember_me = $this->loginOptions['remember_me'];
            // add identity element
            $element_type = 'text';
            switch ($identity_type){
                case 'username':
                    $label = 'Username';
                    break;
                case 'email_address':
                    $label = 'Email Address';
                    $element_type = 'email';
                    break;
                default:
                    $label = 'Username / Email';
            }
            $this->add(array(
                'name' => 'identity',
                'options' => array(
                    'label' => $label,
                ),
                'attributes' => array(
                    'type' => $element_type,
                ),
            ));

            // add credential element
            $this->add(array(
                'name' => 'credential',
                'options' => array(
                    'label' => 'Password',
                ),
                'attributes' => array(
                    'type' => 'password',
                ),
            ));

            // add remember me element
            if ($remember_me) {
                $this->add(array(
                    'type'=>'Zend\Form\Element\Checkbox',
                    'name' => 'remember_me',
                    'options' => array(
                        'label' => 'Keep me logged in',
                    ),
                    'attributes' => array(
                        'value' => 1,
                    ),
                ));
            }

            // add submit button
            $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                    'type' => 'submit',
                    'value' => 'Login',
                ),
            ));

            $this->isPrepared = true;
        }
    }

    public function getInputFilter()
    {
        if (!$this->filter){
            $factory = new InputFilterFactory();
            $identity_type = $this->loginOptions['identity_type'];
            $inputFilterSpec = array();
            if ($identity_type != 'email_address'){
                $inputFilterSpec['identity'] = array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                );
            }else {
                $inputFilterSpec['identity'] = array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                    'validators' => array(
                        new EmailAddress(),
                    ),
                );
            }
            $inputFilterSpec['credential'] = array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            );
            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }

}