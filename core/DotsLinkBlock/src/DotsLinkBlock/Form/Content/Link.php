<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsLinkBlock\Form\Content;
use Dots\Form\Form;
use Dots\Validator\Url as UrlValidator;
use Zend\Form\Fieldset;
use Zend\InputFilter\Factory as InputFilterFactory;

class Link extends Form
{
    private $linkBlocks = null;

    public function __construct($linkBlocks = null, $name = null)
    {
        $this->linkBlocks = $linkBlocks;
        parent::__construct($name);
    }

    public function getLinkBlocks()
    {
        return $this->linkBlocks;
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
            'name' => 'href',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Title'
            )
        ));
        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Select',
            'attributes'=>array(
                'options' => array(
                    'link' => 'Link',
                    'file' => 'File',
                    'page' => 'Page',
                ),
            ),
            'options' => array(
                'label' => 'Type',
            ),
        ));
        $this->add(array(
            'name' => 'file',
            'attributes' => array(
                'type' => 'file',
            ),
            'options' => array(
                'label' => 'File',
            ),
        ));
        $this->add(array(
            'name' => 'link',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Link',
            ),
        ));
        $this->add(array(
            'name' => 'page',
            'attributes' => array(
                'type' => 'text',
            ),
            'options' => array(
                'label' => 'Page',
            ),
        ));
        $this->add(array(
            'name' => 'entity_id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'position',
            'attributes' => array(
                'type' => 'hidden',
                'value' => 1
            ),
        ));
        $this->setWrapElements(true);

    }

    public function setData($data)
    {
        $this->filter = null;
        return parent::setData($data);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $factory = new InputFilterFactory();
            $inputFilterSpec = array(
                'id'        => array( 'required'=>false ),
                'block_id'  => array( 'required' => false ),
                'href'      => array( 'required' => false ),
                'title'     => array( 'required' => true ),
                'type'      => array( 'required' => true ),
                'file'      => array( 'required' => false ),
                'link'      => array(
                    'required' => false,
                    'validators' => array(
                        new UrlValidator()
                    )
                ),
                'page'      => array( 'required' => false ),
                'entity_id' => array( 'required' => false ),
                'position'  => array( 'required' => false ),
            );
            $data = $this->data;
//            if ($this->getName()){
//                $name = str_replace(']','', $this->getName());
//                $name = explode('[', $name);
//                while (!empty($name)){
//                    $key = array_shift($name);
//                    $data = $data[$key];
//                }
//            }
            switch ($data['type']) {
                case 'link':
                    $inputFilterSpec['link']['required'] = true;
                    break;
                case 'page':
                    $inputFilterSpec['page']['required'] = true;
                    break;
                case 'file':
                    $inputFilterSpec['file']['required'] = true;
                    break;
            }

            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }

//    public function getValues($suppressArrayNotation = false)
//    {
//        //handle file upload
//        $srcElement = $this->getElement('file');
//        $src = $this->getValue('file');
//        $srcElement->addFilter('Rename', array(
//                'target' => PUBLIC_PATH . '/data/files/' . uniqid('file_'.rand()) . '_' . $src
//            ));
//        $values = parent::getValues($suppressArrayNotation);
//        if ($srcElement->isUploaded()) {
//            if ($srcElement->receive()) {
//                $fullFilePath = $srcElement->getFileName();
//                $fullFilePath = str_replace(PUBLIC_PATH, "", $fullFilePath);
//                $fullFilePath = str_replace('\\','/', $fullFilePath);
//                $values['file'] = $fullFilePath;
//            }
//        }
//        return $values;
//    }

    public function addButtons()
    {
        $fieldset = new Fieldset('buttons');
        $fieldset->setAttributes(array(
            'class'=>'dots-form-buttons'
        ));
        $fieldset->add(array(
            'name' => 'cancel',
            'options' => array(
                'label' => 'Cancel',
            ),
            'attributes' => array(
                'type' => 'button',
                'class' => 'btn',
                'data-action' => 'cancel-link-block',
            ),
        ));
        $fieldset->add(array(
            'name' => 'save',
            'options' => array(
                'label' => 'Save',
            ),
            'attributes' => array(
                'type' => 'button',
                'class' => 'btn btn-primary',
                'data-action' => 'save-link-block',
            ),
        ));
        $this->add($fieldset);
    }

}