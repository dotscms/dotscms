<?php
namespace DotsPages\Form;

use Dots\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class Page extends Form
{

    protected $templates = array();

    public function __construct($templates=array())
    {
        parent::__construct('page');
        $this->templates = $templates;
        $this->setAttribute('method', 'post');
        $this->setLabel('Page Settings');
        $this->setDescription('Fill out the form to set up the general settings of the page.');
        $this->init();
    }

    /**
     * Initialize the page form
     */
    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => 'Title',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'alias',
            'options' => array(
                'label' => 'Alias / Uri',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $templates = array();
        foreach($this->templates as $tpl){
            $templates[$tpl['path']] = $tpl['name'];
        }

        $this->add(array(
            'name' => 'template',
            'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Template',
            ),
            'attributes' => array(
                'options' => $templates,
            ),
        ));

        $this->add(array(
            'name' => 'language',
            'type' => '\Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Language',
            ),
            'attributes' => array(
                'options' => array(
                    'en' => 'English',
                    'de' => 'German',
                    'ro' => 'Romanian',
                )
            ),
        ));
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $factory = new InputFilterFactory();
            $inputFilterSpec = array(
                'type'=>'Dots\InputFilter\InputFilter',
                'id' => array(
                    'required' => false,
                ),
                'title' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'alias' => array(
                    'required' => false,
                    'allow_empty' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                    'validators'=>array(
                        array(
                            'name'=>'Zend\Validator\Db\NoRecordExists',
                            'options'=>array(
                                'table'=>'pages',
                                'field'=>'alias',
                                'adapter'=>\Dots\Registry::get('service_locator')->get('Zend\Db\Adapter\Adapter'),
                                'exclude'=>"id!='". $this->data['id']."'"
                            )
                        )
                    )
                ),
                'template' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'language' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
            );
            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }

}