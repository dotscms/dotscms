<?php
namespace Dots\Form\Block;
use Dots\Form\Form,
    Dots\Form\Validate\Url as UrlValidator;

class NavigationContentForm extends Form
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
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->addElement('hidden', 'id', array(
            'required' => false,
            'decorators'=>array('ViewHelper')
        ));
        $this->addElement('hidden', 'block_id', array(
            'required' => false,
            'decorators'=>array('ViewHelper')
        ));
        $this->addElement('hidden', 'parent_id', array(
            'required' => false,
            'decorators' => array('ViewHelper')
        ));
        $this->addElement('hidden', 'href', array(
            'required' => false,
            'decorators' => array('ViewHelper')
        ));
        $this->addElement('select', 'type', array(
            'label' => 'Type',
            'required' => true,
            'multiOptions' => array(
                'link' => 'Link',
                'page' => 'Page',
                'header' => 'Header',
                '-' => 'Separator'
            ),
        ));

        $this->addElement('text', 'title', array(
            'label' => 'Title',
            'required' => true,
        ));

        $this->addElement('text', 'link', array(
            'label' => 'Link',
            'required' => false,
            'validators' => array(
                new UrlValidator()
            )
        ));
        $this->addElement('text', 'page', array(
            'label' => 'Page',
            'required' => false
        ));
        $this->addElement('hidden', 'entity_id', array(
            'required' => false,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('hidden', 'position', array(
            'required' => false,
            'default' => 1,
            'decorators' => array('ViewHelper')
        ));
    }

    public function isValid($data)
    {
        //handle required elements
        switch ($data['type']) {
            case 'link':
                $this->getElement('link')->setRequired(true);
                $this->getElement('page')->clearValidators();
                break;
            case 'page':
                $this->getElement('page')->setRequired(true);
                $this->getElement('link')->clearValidators();
                break;
            case '-':
                $this->getElement('title')->setRequired(false);
                break;
        }
        return parent::isValid($data);
    }

}