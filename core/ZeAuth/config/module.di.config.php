<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'ZeAuth\\Crypt' => array(
                ),
                'ZeAuth\\Exception' => array(
                    '__construct' => array(
                        'message' => array(
                            'type' => null,
                            'required' => false,
                        ),
                        'code' => array(
                            'type' => null,
                            'required' => false,
                        ),
                        'previous' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                ),
                'ZeAuth\\Event\\Listener' => array(
                ),
                'ZeAuth\\Db\\Model' => array(
                ),
                'ZeAuth\\Db\\Mapper\\User' => array(
                    '__construct' => array(
                        'config' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setLastLogin' => array(
                        'date' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                ),
                'ZeAuth\\Db\\Model\\User' => array(
                ),
                'ZeAuth\\Db\\Mapper' => array(
                    'setLastLogin' => array(
                        'date' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                ),
                'ZeAuth\\Service\\Auth' => array(
                    'setEventManager' => array(
                        'event_manager' => array(
                            'type' => 'Zend\\EventManager\\EventCollection',
                            'required' => true,
                        ),
                    ),
                ),
                'ZeAuth\\Controller\\AuthController' => array(
                    'setEventManager' => array(
                        'events' => array(
                            'type' => 'Zend\\EventManager\\EventCollection',
                            'required' => true,
                        ),
                    ),
                    'setEvent' => array(
                        'e' => array(
                            'type' => 'Zend\\EventManager\\EventDescription',
                            'required' => true,
                        ),
                    ),
                    'setLocator' => array(
                        'locator' => array(
                            'type' => 'Zend\\Di\\Locator',
                            'required' => true,
                        ),
                    ),
                    'setBroker' => array(
                        'broker' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                ),
                'ZeAuth\\Form\\Login' => array(
                    '__construct' => array(
                        'options' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setOptions' => array(
                        'options' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setConfig' => array(
                        'config' => array(
                            'type' => 'Zend\\Config\\Config',
                            'required' => true,
                        ),
                    ),
                    'setPluginLoader' => array(
                        'loader' => array(
                            'type' => 'Zend\\Loader\\PrefixPathMapper',
                            'required' => true,
                        ),
                        'type' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setAttrib' => array(
                        'key' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'value' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setAttribs' => array(
                        'attribs' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setAction' => array(
                        'action' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setMethod' => array(
                        'method' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setEnctype' => array(
                        'value' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setName' => array(
                        'name' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setLegend' => array(
                        'value' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setDescription' => array(
                        'value' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setOrder' => array(
                        'index' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setElements' => array(
                        'elements' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setDefaults' => array(
                        'defaults' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setDefault' => array(
                        'name' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'value' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setElementFilters' => array(
                        'filters' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setElementsBelongTo' => array(
                        'array' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setIsArray' => array(
                        'flag' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setSubForms' => array(
                        'subForms' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setDefaultDisplayGroupClass' => array(
                        'class' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setDisplayGroups' => array(
                        'groups' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setErrorMessages' => array(
                        'messages' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setErrors' => array(
                        'messages' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setView' => array(
                        'view' => array(
                            'type' => 'Zend\\View\\Renderer',
                            'required' => false,
                        ),
                    ),
                    'setDecorators' => array(
                        'decorators' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setElementDecorators' => array(
                        'decorators' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'elements' => array(
                            'type' => null,
                            'required' => false,
                        ),
                        'include' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setDisplayGroupDecorators' => array(
                        'decorators' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setSubFormDecorators' => array(
                        'decorators' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setTranslator' => array(
                        'translator' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setDefaultTranslator' => array(
                        'translator' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setDisableTranslator' => array(
                        'flag' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setDisableLoadDefaultDecorators' => array(
                        'flag' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);