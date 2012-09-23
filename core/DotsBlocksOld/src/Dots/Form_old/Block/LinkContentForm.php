<?php
namespace Dots\Form\Block;
use Dots\Form\Form,
    Dots\Form\Validate\Url as UrlValidator;

class LinkContentForm extends Form
{
    private $linkBlocks = null;

    public function __construct($linkBlocks = null, $options = null)
    {
        $this->linkBlocks = $linkBlocks;
        parent::__construct($options);
    }

    public function getLinkBlocks()
    {
        return $this->linkBlocks;
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
        $this->addElement('text', 'title', array(
            'label' => 'Title',
            'required' => true,
        ));
        $this->addElement('select', 'type', array(
            'label'     => 'Type',
            'required'  => true,
            'multiOptions'  => array(
                'link'  => 'Link',
                'file'  => 'File',
                'page'  => 'Page',
            ),
        ));

        $this->addElement('file', 'file', array(
            'label' => 'File',
            'required' => false,
            'valueDisabled' => true
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
                $this->getElement('file')->clearValidators();
                break;
            case 'page':
                $this->getElement('page')->setRequired(true);
                $this->getElement('link')->clearValidators();
                $this->getElement('file')->clearValidators();
                break;
            case 'file':
                $this->getElement('file')->setRequired(true);
                $this->getElement('page')->clearValidators();
                $this->getElement('link')->clearValidators();
                break;
        }
        return parent::isValid($data);
    }

    public function getValues($suppressArrayNotation = false)
    {
        //handle file upload
        $srcElement = $this->getElement('file');
        $src = $this->getValue('file');
        $srcElement->addFilter('Rename', array(
                'target' => PUBLIC_PATH . '/data/files/' . uniqid('file_'.rand()) . '_' . $src
            ));
        $values = parent::getValues($suppressArrayNotation);
        if ($srcElement->isUploaded()) {
            if ($srcElement->receive()) {
                $fullFilePath = $srcElement->getFileName();
                $fullFilePath = str_replace(PUBLIC_PATH, "", $fullFilePath);
                $fullFilePath = str_replace('\\','/', $fullFilePath);
                $values['file'] = $fullFilePath;
            }
        }
        return $values;
    }

    public function addButtons()
    {
        $this->addElement('button', 'cancel', array(
            'label' => 'Cancel',
            'decorators' => array('ViewHelper'),
            'attribs' => array(
                'class' => 'btn',
                'data-action' => 'cancel-link-block',
            )
        ));
        $this->addElement('button', 'save', array(
            'label' => 'Save',
            'decorators' => array('ViewHelper'),
            'attribs' => array(
                'class' => 'btn btn-primary',
                'data-action' => 'save-link-block',
            )
        ));
        $this->addDisplayGroup(array('cancel', 'save'), 'buttons', array(
            'decorators' => array('FormElements', array('HtmlTag', array('tag' => 'div', 'class' => 'dots-form-buttons'))),
        ));
    }

}