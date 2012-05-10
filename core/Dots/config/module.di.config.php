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
                'Dots\\Block\\Handler\\HtmlContent' => array(
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
                'Dots\\Block\\Handler\\ImageContent' => array(
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
                'Dots\\Block\\Handler\\LinksContentController' => array(
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