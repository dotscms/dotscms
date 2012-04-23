<?php
namespace Dots\Form;
use Zend\Form\Form;

class MultiForm extends Form
{

    public function __construct($forms, $options = null)
    {
        parent::__construct($options);
        $this->setIsArray(false);

        foreach($forms as $key=>$form){
            $form->setIsArray(true);
            // Set decorators for the form
            $form->setDecorators(array(
                'FormElements',
                'FormDecorator',
            ));

            $form->setDisplayGroupDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl')),
                array('Description', array('placement' => 'prepend')),
                'Fieldset',
            ));
            $this->addSubForm($form, $key);
        }
        $this->setSubFormDecorators(array(
            'FormElements',
        ));

    }

    public function addButtons()
    {
        $this->addElement('button', 'cancel', array(
            'label' => 'Cancel',
            'decorators'=>array('ViewHelper'),
            'attribs'=>array(
                'class'=>'btn',
                'data-action'=>'cancel-block',
            )
        ));
        $this->addElement('button', 'save', array(
            'label' => 'Save',
            'decorators' => array('ViewHelper'),
            'attribs' => array(
                'class' => 'btn btn-primary',
                'data-action' => 'save-block',
            )
        ));
        $this->addDisplayGroup(array('cancel', 'save'),'buttons', array(
            'decorators' => array('FormElements', array('HtmlTag', array('tag'=>'div', 'class' => 'dots-form-buttons') )),
        ));
    }

}