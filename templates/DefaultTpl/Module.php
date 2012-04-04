<?php

namespace DefaultTpl;

class Module
{
    /**
     * Get core configuration array
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
