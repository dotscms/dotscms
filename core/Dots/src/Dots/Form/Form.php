<?php
namespace Dots\Form;
use Zend\Form\Form as BaseForm;

class Form extends BaseForm
{
    static public $HIDDEN_DECORATOR = array('ViewHelper');
    static public $CHECKBOX_DECORATOR = array(
        'ViewHelper',
        array('Label', array('placement' => 'append')),
        'Errors',
        array(array('lblTag' => 'HtmlTag'), array('tag' => 'dd', 'class' => 'checkbox')),
        array('HtmlTag', array('tag' => 'dt', 'placement' => 'prepend', 'class' => 'checkbox')),
    );

}