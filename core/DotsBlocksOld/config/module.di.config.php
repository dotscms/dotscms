<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Dots\\Block\\BlockManager' => array(
                    'setEventManager' => array(
                        'events' => array(
                            'type' => false,
                            'required' => false,
                        ),
                    ),
                    'addContentHandler' => array(
                        'contentHandler' => array('type' => 'Dots\\Block\\HandlerAware', 'required' => true)
                    )
                ),
                'Dots\\Block\\Handler\\HtmlHandler' => array(
                    'attach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    ),
                    'detach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    )
                ),
                'Dots\\Block\\Handler\\ImageHandler' => array(
                    'attach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    ),
                    'detach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    )
                ),
                'Dots\\Block\\Handler\\LinksHandler' => array(
                    'attach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    ),
                    'detach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    )
                ),
                'Dots\\Block\\Handler\\NavigationHandler' => array(
                    'attach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    ),
                    'detach' => array(
                        'events' => array(
                            'required' => false,
                            'type' => false,
                        ),
                    )
                ),
            ),
        ),
    ),
);