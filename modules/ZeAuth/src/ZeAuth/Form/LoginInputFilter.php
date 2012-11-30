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

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use ZeAuth\Crypt;

class LoginInputFilter extends \Zend\InputFilter\InputFilter
{

    /**
     * @var \Zend\ServiceManager\ServiceManager $sm
     */
    private $sm;

    private $loginOptions;

    public function __construct($sm)
    {
        $this->sm = $sm;

        $config = $sm->get('config');

        $this->loginOptions = array(
            'identity_type'             => $config['ze-auth']['identity_type'],
            'remember_me'               => $config['ze-auth']['remember_me'],
            'password_hash_algorithm'   => $config['ze-auth']['password_hash_algorithm']
        );

        self::init();
    }

    public function init()
    {
        $identity_type = $this->loginOptions['identity_type'];
        if ($identity_type != 'email_address'){
            $this->add(array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                )
            ), 'identity');
        }
        else {
            $this->add(array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new EmailAddress(),
                ),
            ), 'identity');
        }

        $this->add(array(
            'required' => true,
            'filters' => array(
                array('name' => 'Zend\Filter\StringTrim'),
            ),
        ),'credential');
    }

    /**
     * Check if login form is valid
     *  - first call parent to validate fields
     *  - get user by identity and validate
     * @return bool
     */
    public function isValid()
    {
        $valid = parent::isValid();
        if($valid){
            $identity = $this->get('identity')->getValue();
            $credential = $this->get('credential')->getValue();

            $user = $this->getUserByIdentity($identity);

            if (!$user){
                $this->invalidInputs['identity'] = $this->get('identity');
                $this->get('identity')->setErrorMessage('Invalid identity or credential supplied.');

                return false;
            }
            $salt = $user->getPasswordSalt();
            $password = $user->getPassword();

            if (!$this->_isValidCredential($password, $salt, $credential)){
                $this->invalidInputs['identity'] = $this->get('identity');
                $this->get('identity')->setErrorMessage('Invalid identity or credential supplied.');

                return false;
            }
        }

        return $valid;
    }

    /**
     * Test if the credential is valid based on the configured algorithm
     * @param string $password
     * @param string $salt
     * @param string $credential
     * @return bool
     */
    protected function _isValidCredential($password, $salt, $credential)
    {
        $crypt = new Crypt();
        $alg = $this->loginOptions['password_hash_algorithm'];
        if ($password == $crypt->encode($alg, $credential, $salt)){
            return true;
        }
        return false;
    }

    /**
     * Get a user by the configured identity type
     * @param $identity
     * @return \ZeAuth\Db\MapperInterface
     */
    protected function getUserByIdentity($identity)
    {
        $model = $this->sm->get('ZeAuthModelUser');
        $identity_type = $this->loginOptions['identity_type'];
        switch ($identity_type) {
            case 'username':
                $mapper = $model->getByUsername($identity);
                break;
            case 'email_address':
                $mapper = $model->getByEmailAddress($identity);
                break;
            default:
                if (strpos($identity, '@') === false) {
                    $mapper = $model->getByUsername($identity);
                } else {
                    $mapper = $model->getByEmailAddress($identity);
                }
                break;
        }
        return $mapper;
    }

}