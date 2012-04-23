<?php
return array(
    'di' =>array(
        'instance' =>array(

            /**
             * Database registry
             */
            'ZeDb\Registry' => array(
                'parameters' => array(
                    'models' => array(
                        'ZeAuth\Db\Entity\User' => 'ZeAuth\Db\Model\User',
                        'DotsPages\Db\Entity\Page' => 'DotsPages\Db\Model\Page',
                        'DotsPages\Db\Entity\PageMeta' => 'DotsPages\Db\Model\PageMeta',
                        'Dots\Db\Entity\Block' => 'Dots\Db\Model\Block',
                        'Dots\Db\Entity\HtmlBlock' => 'Dots\Db\Model\HtmlBlock',
                    ),
                ),
            ),

            /**
             * Database Model configuration
             */
            'ZeAuth\Db\Model\User' => array(
                'parameters' => array(
                    'options' => array(
                        'tableName' => 'users',
                        'entityClass' => 'ZeAuth\Db\Entity\User',
                    ),
                ),
            ),
            'DotsPages\Db\Model\Page' => array(
                'parameters' => array(
                    'options' => array(
                        'tableName' => 'pages',
                        'entityClass' => 'DotsPages\Db\Entity\Page',
                    ),
                ),
            ),
            'DotsPages\Db\Model\PageMeta' => array(
                'parameters' => array(
                    'options' => array(
                        'tableName' => 'page_metas',
                        'entityClass' => 'DotsPages\Db\Entity\PageMeta',
                    ),
                ),
            ),
            'Dots\Db\Model\Block' => array(
                'parameters' => array(
                    'options' => array(
                        'tableName' => 'blocks',
                        'entityClass' => 'Dots\Db\Entity\Block',
                    ),
                ),
            ),
            'Dots\Db\Model\HtmlBlock' => array(
                'parameters' => array(
                    'options' => array(
                        'tableName' => 'block_html',
                        'entityClass' => 'Dots\Db\Entity\HtmlBlock',
                    ),
                ),
            ),
        )
    )
);