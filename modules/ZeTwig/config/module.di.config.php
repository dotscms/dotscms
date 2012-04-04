<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'ZeTwig\\View\\Environment' => array(
                    '__construct' => array(
                        'loader' => array(
                            'type' => 'ZeTwig\\View\\Resolver',
                            'required' => false,
                        ),
                        'broker' => array(
                            'type' => 'Zend\\View\\HelperBroker',
                            'required' => false,
                        ),
                        'options' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setLocator' => array(
                        'locator' => array(
                            'type' => 'Zend\\Di\\Locator',
                            'required' => true,
                        ),
                    ),
                    'setEnvironmentOptions' => array(
                        'environment_options' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setBroker' => array(
                        'broker' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setBaseTemplateClass' => array(
                        'class' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setCache' => array(
                        'cache' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setLexer' => array(
                        'lexer' => array(
                            'type' => 'Twig_LexerInterface',
                            'required' => true,
                        ),
                    ),
                    'setParser' => array(
                        'parser' => array(
                            'type' => 'Twig_ParserInterface',
                            'required' => true,
                        ),
                    ),
                    'setCompiler' => array(
                        'compiler' => array(
                            'type' => 'Twig_CompilerInterface',
                            'required' => true,
                        ),
                    ),
                    'setLoader' => array(
                        'loader' => array(
                            'type' => 'Twig_LoaderInterface',
                            'required' => true,
                        ),
                    ),
                    'setCharset' => array(
                        'charset' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setExtensions' => array(
                        'extensions' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                ),
                'ZeTwig\\View\\Exception\\TemplateException' => array(
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
                'ZeTwig\\View\\Extension\\Render\\RenderNode' => array(
                    '__construct' => array(
                        'expr' => array(
                            'type' => 'Twig_Node_Expression',
                            'required' => true,
                        ),
                        'attributes' => array(
                            'type' => 'Twig_Node_Expression',
                            'required' => true,
                        ),
                        'options' => array(
                            'type' => 'Twig_Node_Expression',
                            'required' => true,
                        ),
                        'lineno' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'tag' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setAttribute' => array(
                        'name' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'value' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setNode' => array(
                        'name' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'node' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                ),
                'ZeTwig\\View\\Extension\\Render\\TokenParser' => array(
                    'setParser' => array(
                        'parser' => array(
                            'type' => 'Twig_Parser',
                            'required' => true,
                        ),
                    ),
                ),
                'ZeTwig\\View\\Extension\\Trigger\\RenderNode' => array(
                    '__construct' => array(
                        'event' => array(
                            'type' => 'Twig_Node_Expression',
                            'required' => true,
                        ),
                        'target' => array(
                            'type' => 'Twig_Node_Expression',
                            'required' => true,
                        ),
                        'attributes' => array(
                            'type' => 'Twig_Node_Expression',
                            'required' => true,
                        ),
                        'lineno' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'tag' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setAttribute' => array(
                        'name' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'value' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setNode' => array(
                        'name' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'node' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                ),
                'ZeTwig\\View\\Extension\\Trigger\\TokenParser' => array(
                    'setParser' => array(
                        'parser' => array(
                            'type' => 'Twig_Parser',
                            'required' => true,
                        ),
                    ),
                ),
                'ZeTwig\\View\\Extension' => array(
                    'setEventManager' => array(
                        'events' => array(
                            'type' => 'Zend\\EventManager\\EventCollection',
                            'required' => true,
                        ),
                    ),
                ),
                'ZeTwig\\View\\HelperFunction' => array(
                    '__construct' => array(
                        'name' => array(
                            'type' => null,
                            'required' => true,
                        ),
                        'options' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setArguments' => array(
                        'arguments' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                ),
                'ZeTwig\\View\\Renderer' => array(
                    '__construct' => array(
                        'environment' => array(
                            'type' => 'ZeTwig\\View\\Environment',
                            'required' => true,
                        ),
                        'config' => array(
                            'type' => null,
                            'required' => false,
                        ),
                    ),
                    'setResolver' => array(
                        'resolver' => array(
                            'type' => 'Zend\\View\\Resolver',
                            'required' => true,
                        ),
                    ),
                    'setEnvironmentOptions' => array(
                        'options' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setBroker' => array(
                        'broker' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                    'setFilterChain' => array(
                        'filters' => array(
                            'type' => 'Zend\\Filter\\FilterChain',
                            'required' => true,
                        ),
                    ),
                    'setCanRenderTrees' => array(
                        'renderTrees' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                ),
                'ZeTwig\\View\\Resolver' => array(
                    'setConfig' => array(
                        'config' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
					'attach' => array(
                        'resolver' => array(
							'type' => 'Zend\View\Resolver', 
							'required' => true
						)
                    )
                ),
                'ZeTwig\\View\\Strategy\\TwigRendererStrategy' => array(
                    '__construct' => array(
                        'renderer' => array(
                            'type' => 'ZeTwig\\View\\Renderer',
                            'required' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);