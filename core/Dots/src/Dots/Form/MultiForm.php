<?php
namespace Dots\Form;

use Dots\InputFilter\MultiFormFilter;

class MultiForm extends Form
{
    protected $params = array();

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($name)
    {
        return $this->params[$name];
    }

    public function __construct($forms, $options = null)
    {
        parent::__construct(null);
        $this->setWrapElements(false);
        foreach($forms as $key => $form){
            // Set decorators for the form
            $form->setName($key);
            $this->add($form);
        }
    }

    public function getInputFilter()
    {
        if (!$this->filter){
            $this->filter = new MultiFormFilter($this->getFieldsets());
        }
        return $this->filter;
    }

    public function addButtons()
    {
        $this->add(array(
            'name' => 'cancel',
            'options' => array(
                'label' => 'Cancel',
            ),
            'attributes' => array(
                'type' => 'button',
                'class' => 'btn',
                'data-action' => 'cancel-block',
            ),
        ));
        $this->add(array(
            'name' => 'save',
            'options' => array(
                'label' => 'Save',
            ),
            'attributes' => array(
                'type' => 'button',
                'class' => 'btn btn-primary',
                'data-action' => 'save-block',
            ),
        ));
    }

}