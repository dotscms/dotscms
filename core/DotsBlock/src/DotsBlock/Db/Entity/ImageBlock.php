<?php
namespace DotsBlock\Db\Entity;

use ZeDb\Entity;

class ImageBlock extends Entity
{
    protected $_data = array(
        'id'                => null, // id of the image content
        'block_id'          => null, // id of the block
        'original_src'      => null, // the src to the uploaded image
        'src'               => null, // the src to the cropped image
        'alt'               => null, // alternative text
        'width'             => null, // the width of the original image
        'height'            => null, // the height of the original image
        'display_width'     =>'100%',// the width of the displayed image
        'display_height'    => null, // the height of the displayed image

        'crop_x1'           => null, // top left crop point
        'crop_y1'           => null,
        'crop_x2'           => null, // bottom right crop point
        'crop_y1'           => null,
    );
}