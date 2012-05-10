<?php
/**
 * This file is part of ZeDb
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeDb;

use Zend\Db\Db,
    Zend\Di\LocatorInterface,
    Zend\Loader\LocatorAware;

/**
 * Registry class for loading and registering models
 *
 * @package ZeDb
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class Registry implements LocatorAware
{
    /**
     * @var array
     */
    private $_models = array();

    /**
     * @var null
     */
    private $_locator = null;

    /**
     * @param \Zend\Di\Locator $locator
     */
    public function setLocator(LocatorInterface $locator)
    {
        $this->_locator = $locator;
    }

    /**
     * @return null
     */
    public function getLocator()
    {
        return $this->_locator;
    }

    /**
     * @param $models
     */
    public function __construct($models)
    {
        foreach($models as $key=>$value){
            if (is_string($value)){
                $this->_models[trim($key,'\\')] = trim($value,'\\');
            }elseif (is_array($value)){
                foreach($models as $k=>$v){
                    if (is_string($k)){
                        $this->_models[trim($k,'\\')] = trim($v,'\\');
                    }else{
                        $v = trim($v,'\\');
                        $model = $this->_locator->get($v);
                        $k = $model->getEntityClass();
                        $this->_models[trim($k,'\\')] = $v;
                    }
                }
            }
        }
    }

    /**
     * Return a instance of the model class associated with the given entity class.
     * If the table name is provided and the entity class is not found in the registry
     *     an instance of ZeDb\Model class is returned with the provided info.
     * If the entity class is not found in the registry it is presumed that the given class is
     *     a model class and it is returned from the locator.
     *
     * @param $id
     * @param null $tableName
     * @return mixed
     * @throws Exception\ModelNotFoundException
     */
    public function get($id, $tableName = null)
    {
        //load by entity name from the registered models
        $class = /*'\\'.*/trim($id, '\\');
        if (array_key_exists($class, $this->_models)){
            $model = $this->getLocator()->get($this->_models[$class]);
            return $model;
        }
        //else if tableName is specified load default model with the entityClass and tableName set
        if ($tableName){
            return $this->_locator->get('ZeDb\Model', array(
                'options'=>array(
                    'entityClass'=>$class,
                    'tableName'=>$tableName,
                )
            ));
        }
        //else presume the request is a model class and load it from locator
        return $this->_locator->get($class);
    }

}