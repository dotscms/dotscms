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
            'DotsBlock\Db\Model\Block' => array(
                'tableName' => 'blocks',
                'entityClass' => 'DotsBlock\Db\Entity\Block',
            ),
            'DotsBlock\Db\Model\HtmlBlock' => array(
                'tableName' => 'block_html',
                'entityClass' => 'DotsBlock\Db\Entity\HtmlBlock',
            ),
            'DotsBlock\Db\Model\ImageBlock' => array(
                'tableName' => 'block_image',
                'entityClass' => 'DotsBlock\Db\Entity\ImageBlock',
            ),
            'DotsBlock\Db\Model\LinkBlock' => array(
                'tableName' => 'block_links',
                'entityClass' => 'DotsBlock\Db\Entity\LinkBlock',
            ),
            'DotsBlock\Db\Model\NavigationBlock' => array(
                'tableName' => 'block_navigation',
                'entityClass' => 'DotsBlock\Db\Entity\NavigationBlock',
            ),
        ),
    ),

);
