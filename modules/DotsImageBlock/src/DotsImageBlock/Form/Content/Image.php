<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsImageBlock\Form\Content;
use Dots\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class Image extends Form
{
    private $imageBlock = null;

    public function __construct($imageBlock = null, $name=null)
    {
        $this->imageBlock = $imageBlock;
        parent::__construct($name);
    }

    public function getImageBlock()
    {
        return $this->imageBlock;
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'block_id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'original_src',
            'options'=>array(
                'label' => 'File',
            ),
            'attributes' => array(
                'type' => 'file', //'jpg,png,gif'
            ),
        ));
        $this->add(array(
            'name' => 'src',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'alt',
            'attributes' => array(
                'type' => 'hidden',
            ),
            'options' => array(
                'label' => 'Alternative text',
            ),
        ));
        $this->add(array(
            'name' => 'display_width',
            'attributes' => array(
                'type' => 'text',
                'class'=> 'span1'
            ),
            'options' => array(
                'label' => 'Width',
            ),
        ));
        $this->add(array(
            'name' => 'display_height',
            'attributes' => array(
                'type' => 'text',
                'class' => 'span1'
            ),
            'options' => array(
                'label' => 'Height',
            ),
        ));
        $this->add(array(
            'name' => 'crop_x1',
            'attributes' => array(
                'type' => 'hidden',
                'data-img-crop-field' => 'x1',
            ),
        ));
        $this->add(array(
            'name' => 'crop_y1',
            'attributes' => array(
                'type' => 'hidden',
                'data-img-crop-field' => 'y1',
            ),
        ));
        $this->add(array(
            'name' => 'crop_x2',
            'attributes' => array(
                'type' => 'hidden',
                'data-img-crop-field' => 'x2',
            ),
        ));
        $this->add(array(
            'name' => 'crop_y2',
            'attributes' => array(
                'type' => 'hidden',
                'data-img-crop-field' => 'y2',
            ),
        ));
        $this->setWrapElements(false);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $factory = new InputFilterFactory();
            $inputFilterSpec = array();
            $inputFilterSpec['id'] = array(
                'required' => false,
            );
            $inputFilterSpec['block_id'] = array(
                'required' => false,
            );
            $inputFilterSpec['src'] = array(
                'required' => false,
            );
            $inputFilterSpec['alt'] = array(
                'required' => false,
            );
            $inputFilterSpec['display_width'] = array(
                'required' => false,
            );
            $inputFilterSpec['display_height'] = array(
                'required' => false,
            );
            $inputFilterSpec['crop_x1'] = array(
                'required' => false,
            );
            $inputFilterSpec['crop_x2'] = array(
                'required' => false,
            );
            $inputFilterSpec['crop_y1'] = array(
                'required' => false,
            );
            $inputFilterSpec['crop_y2'] = array(
                'required' => false,
            );
            $inputFilterSpec['original_src'] = array(
                'required' => false,
                'validators'=>array(
                    array(
                        'name'=>'Dots\Validator\File\Extension',
                        'options'=>array(
                            'extension'=>'jpg,jpeg,gif,png'
                        )
                    )
                )
            );
            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }

}