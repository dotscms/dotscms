<?php
namespace Dots\Form\Setting;
use Dots\Form\Form;

class DefaultBlockSettingsForm extends Form
{

    public function init()
    {
        $this->addElement('hidden', 'id', array('decorators' => Form::$HIDDEN_DECORATOR));

        $this->addElement('text', 'class', array(
            'label' => 'Html class'
        ));

        $this->addDisplayGroup(array('id', 'class'),'default', array(
            'legend' => 'Default settings',
            'description' => 'Modify general settings about the block:'
        ));
    }

}