<?php
namespace Dots\Form\Block;
use Dots\Form\Form;

class HtmlContentForm extends Form
{

    public function init()
    {
        $this->addElement('hidden', 'id', array(
            'required' => false,
            'decorators'=>array('ViewHelper')
        ));
        $this->addElement('hidden', 'block_id', array(
            'required' => false,
            'decorators'=>array('ViewHelper')
        ));
        $this->addElement('textarea', 'content', array(
            'label' => 'Content',
            'required' => false,
            'attribs' => array(
                'rows' => 6,
                'cols' => 40,
                'class' => 'editor',
            ),
            'decorators' => array('ViewHelper')
        ));
    }

}