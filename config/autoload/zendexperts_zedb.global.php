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
            'DotsHtmlBlock\Db\Model\HtmlBlock' => array(
                'tableName' => 'block_html',
                'entityClass' => 'DotsHtmlBlock\Db\Entity\HtmlBlock',
            ),
            'DotsImageBlock\Db\Model\ImageBlock' => array(
                'tableName' => 'block_image',
                'entityClass' => 'DotsImageBlock\Db\Entity\ImageBlock',
            ),
            'DotsLinkBlock\Db\Model\LinkBlock' => array(
                'tableName' => 'block_links',
                'entityClass' => 'DotsLinkBlock\Db\Entity\LinkBlock',
            ),
            'DotsNavBlock\Db\Model\NavigationBlock' => array(
                'tableName' => 'block_navigation',
                'entityClass' => 'DotsNavBlock\Db\Entity\NavigationBlock',
            ),
        ),
    ),

);
