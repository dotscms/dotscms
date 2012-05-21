<?php
namespace Dots\Form\Block;
use Dots\Form\Form;

class ImageContentForm extends Form
{
    private $imageBlock = null;

    public function __construct($imageBlock = null, $options = null)
    {
        $this->imageBlock = $imageBlock;
        parent::__construct($options);
    }

    public function getImageBlock()
    {
        return $this->imageBlock;
    }

    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->addElement('hidden', 'id', array(
            'required' => false,
            'decorators'=>array('ViewHelper')
        ));
        $this->addElement('hidden', 'block_id', array(
            'required' => false,
            'decorators'=>array('ViewHelper')
        ));
        $this->addElement('file', 'original_src', array(
            'label' => 'File',
            'required' => false,
            'valueDisabled' => true,
            'validators' =>array(
                array('Extension', false, 'jpg,png,gif')
            )
        ));
        $this->addElement('hidden', 'src', array(
            'required' => false,
            'decorators' => array('ViewHelper')
        ));
        $this->addElement('text', 'alt', array(
            'label' => 'Alternative text',
            'required' => false,
        ));
        $this->addElement('text', 'display_width', array(
            'label' => 'Width',
            'required' => false,
            'class'=>'span1'
        ));
        $this->addElement('text', 'display_height', array(
            'label' => 'Height',
            'required' => false,
            'class' => 'span1'
        ));
        $this->addElement('hidden', 'crop_x1', array(
            'decorators'=>array('ViewHelper'),
            'attribs'=>array(
                'data-img-crop-field'=>'x1'
            )
        ));
        $this->addElement('hidden', 'crop_y1', array(
            'decorators'=>array('ViewHelper'),
            'attribs' => array(
                'data-img-crop-field' => 'y1'
            )
        ));
        $this->addElement('hidden', 'crop_x2', array(
            'decorators'=>array('ViewHelper'),
            'attribs' => array(
                'data-img-crop-field' => 'x2'
            )
        ));
        $this->addElement('hidden', 'crop_y2', array(
            'decorators'=>array('ViewHelper'),
            'attribs' => array(
                'data-img-crop-field' => 'y2'
            )
        ));
    }

    public function getValues($suppressArrayNotation = false)
    {
        $srcElement = $this->getElement('original_src');
        $src = $this->getValue('original_src');
        $srcElement->addFilter('Rename', array(
                'target' => PUBLIC_PATH . '/data/uploads/' . uniqid(rand()) . '_' . $src
            ));
        $values = parent::getValues($suppressArrayNotation);
        if ($srcElement->isUploaded()) {
            if ($srcElement->receive()) {
                $fullFilePath = $srcElement->getFileName();
                $fullFilePath = str_replace(PUBLIC_PATH, "", $fullFilePath);
                $fullFilePath = str_replace('\\','/', $fullFilePath);
                $values['original_src'] = $fullFilePath;
            }
        }
        return $values;
    }

}