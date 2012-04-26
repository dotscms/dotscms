<?php
namespace DotsPages\Form;
use Zend\Form\Form,
    DotsPages\Module;

class Page extends Form
{

    public function init()
    {
        //Set elements
        $this->addElement('hidden', 'id', array('decorators'=>array('ViewHelper')));
        $this->addElement('text', 'title', array(
            'label'=>'Title',
            'required'=>true,
        ));
        $this->addElement('text', 'alias', array(
            'label' => 'Alias / Uri',
            'required' => true
        ));

        $locator = Module::locator();
        $container = $locator->get('Dots\View\TemplateContainer');
        $this->addElement('select', 'template', array(
            'label' => 'Template',
            'multiOptions'=> $container->toArray(),
            'required' => true
        ));

        $this->addElement('select', 'language', array(
            'label' => 'Language',
            'multiOptions'=>array(
                'en'=>'English',
                'de'=>'German',
                'ro'=>'Romanian',
            ),
            'required' => true
        ));

        // Set display group
        $this->addDisplayGroup(
            array('id', 'title', 'alias', 'template', 'language'),
            'page',
            array(
                'legend'=>'Page Settings',
                'description'=>'Fill out the form to set up the general settings of the page.'
            )
        );

    }

}