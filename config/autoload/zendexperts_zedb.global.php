<?php
return array(
    'zendexperts_zedb' => array(
        'models' => array(
            'ZeAuth\Db\Model\User' => array(
                'tableName' => 'users',
                'entityClass' => 'ZeAuth\Db\Entity\User',
            ),
            'DotsPages\Db\Model\Page' => array(
                'tableName' => 'pages',
                'entityClass' => 'DotsPages\Db\Entity\Page',
            ),
            'DotsPages\Db\Model\PageMeta' => array(
                'tableName' => 'page_metas',
                'entityClass' => 'DotsPages\Db\Entity\PageMeta',
            ),
            'Dots\Db\Model\Block' => array(
                'tableName' => 'blocks',
                'entityClass' => 'Dots\Db\Entity\Block',
            ),
            'Dots\Db\Model\HtmlBlock' => array(
                'tableName' => 'block_html',
                'entityClass' => 'Dots\Db\Entity\HtmlBlock',
            ),
            'Dots\Db\Model\ImageBlock' => array(
                'tableName' => 'block_image',
                'entityClass' => 'Dots\Db\Entity\ImageBlock',
            ),
            'Dots\Db\Model\LinkBlock' => array(
                'tableName' => 'block_links',
                'entityClass' => 'Dots\Db\Entity\LinkBlock',
            ),
            'Dots\Db\Model\NavigationBlock' => array(
                'tableName' => 'block_navigation',
                'entityClass' => 'Dots\Db\Entity\NavigationBlock',
            ),
        ),
    ),

);
