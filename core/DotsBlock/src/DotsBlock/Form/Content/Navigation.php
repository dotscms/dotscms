<?php
namespace DotsBlock\Form\Content;
use Dots\Form\Form;
use Dots\Validator\Url as UrlValidator;
use Zend\InputFilter\Factory as InputFilterFactory;

class Navigation extends Form
{
    private $navigationBlocks = null;

    public function __construct($navigationBlocks = null, $options = null)
    {
        $this->navigationBlocks = $navigationBlocks;
        parent::__construct($options);
    }

    public function getNavigationBlocks()
    {
        return $this->navigationBlocks;
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
            'name' => 'parent_id',
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
            'name' => 'type',
            'type' => 'Zend\\Form\\Element\\Select',
            'options'=>array(
                'label'=>'Type',
            ),
            'attributes' => array(
                'options'=>array(
                    'link' => 'Link',
                    'page' => 'Page',
                    'header' => 'Header',
                    '-' => 'Separator'
                )
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'options'=>array(
                'label'=>'Title',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'link',
            'options' => array(
                'label' => 'Link',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'page',
            'options' => array(
                'label' => 'Page',
            ),
            'attributes' => array(
                'type' => 'text',
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
        $this->setWrapElements(false);

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
                'id' => array('required' => false),
                'block_id' => array('required' => false),
                'parent_id' => array('required' => false),
                'href' => array('required' => false),
                'title' => array('required' => true),
                'type' => array('required' => true),
                'file' => array('required' => false),
                'link' => array(
                    'required' => false,
                    'validators' => array(
                        //@todo Replace the URL validator with a path and url validator
//                        new UrlValidator()
                    )
                ),
                'page' => array('required' => false),
                'entity_id' => array('required' => false),
                'position' => array('required' => false),
            );
            $data = $this->data;
            switch ($data['type']) {
                case 'link':
                    $inputFilterSpec['link']['required'] = true;
                    break;
                case 'page':
                    $inputFilterSpec['page']['required'] = true;
                    break;
                case '-':
                    $inputFilterSpec['title']['required'] = false;
                    break;
            }

            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }

}