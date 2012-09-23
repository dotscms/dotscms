<?php
namespace DotsBlock\Form\Setting;
use Dots\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class DefaultBlockSettingsForm extends Form
{

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setLabel('Default settings');
        $this->setDescription('Modify general settings about the block:');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        // add html class element
        $this->add(array(
            'name' => 'class',
            'options' => array(
                'label' => 'Html Class',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $factory = new InputFilterFactory();
            $inputFilterSpec = array();
            $inputFilterSpec['class'] = array(
                'required' => false,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            );
            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }
}