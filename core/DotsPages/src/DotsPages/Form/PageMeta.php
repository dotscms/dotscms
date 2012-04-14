<?php
namespace DotsPages\Form;
use Zend\Form\Form;

class PageMeta extends Form
{

    public function init()
    {
        $this->addElement('hidden', 'id', array('decorators'=>array('ViewHelper')));
        $this->addElement('hidden', 'page_id', array('decorators'=>array('ViewHelper')));
        $this->addElement('text', 'title', array(
            'label'=>'Title',
            'required'=> true,
        ));
        $this->addElement('text', 'keywords', array(
            'label' => 'Keywords',
            'required' => false
        ));
        $this->addElement('textarea', 'description', array(
            'label' => 'Description',
            'required' => false,
            'attribs'=>array(
                'rows'=>6,
                'cols'=>40,
            )
        ));
        $this->addElement('text', 'author', array(
            'label' => 'Author',
            'required' => false
        ));
        $this->addElement('text', 'robots', array(
            'label' => 'Robots',
            'required' => false
        ));
        $this->addElement('text', 'copyright', array(
            'label' => 'Copyright',
            'required' => false
        ));
        $this->addElement('select', 'charset', array(
            'label' => 'Charset',
            'required' => false,
            'multiOptions'=>array(
                'UTF-8'=>'UTF-8',
            )
        ));
        $this->addElement('text', 'expires_after', array(
            'label' => 'Expires After',
            'required' => false
        ));

        $this->addDisplayGroup(
            array('id', 'page_id', 'title', 'keywords', 'description', 'author', 'robots', 'copyright', 'charset', 'expires_after'),
            'meta',
            array(
                'legend'=>'Meta Settings',
                'description'=>'Fill out the metadata information for the page.'
            )
        );

        $this->setDecorators(array(
            'FormElements',
            'FormDecorator',
        ));

        $this->setDisplayGroupDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl')),
            array('Description',array('placement'=>'prepend')),
            'Fieldset',
        ));

    }

}