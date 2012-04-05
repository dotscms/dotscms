<?php

namespace ZeTpl;

use Zend\Module\Manager;

class Module
{

    protected $template = 'default';

    /**
     * Start point for any module
     * @param \Zend\Module\Manager $moduleManager
     */
    public function init(Manager $moduleManager)
    {
        //@todo Add an event to change to the loaded template based on the database configuration
    }

    /**
     * Get core configuration array
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

}