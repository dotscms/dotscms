<?php
return array(
    'factories'=>array(
        'DotsBlockManager'  => 'Dots\Block\Service\BlockManagerFactory',
    ),
    'aliases'=>array(
        'Dots\Block\BlockManager'=>'DotsBlockManager'
    )
);