<?php
namespace DotsPages\Form;
use Dots\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class PageMeta extends Form
{

    public function __construct()
    {
        parent::__construct('meta');
        $this->setAttribute('method', 'post');
        $this->setLabel('Meta Settings');
        $this->setDescription('Fill out the metadata information for the page.');
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
            'name' => 'page_id',
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
            'name' => 'keywords',
            'options' => array(
                'label' => 'Keywords',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'type' => 'textarea',
            ),
        ));

        $this->add(array(
            'name' => 'author',
            'options' => array(
                'label' => 'Author',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'robots',
            'options' => array(
                'label' => 'Robots',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'copyright',
            'options' => array(
                'label' => 'Copyright',
            ),
            'attributes' => array(
                'type' => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'charset',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Charset',
            ),
            'attributes' => array(
                'options' => array(
                    'UTF-8' => 'UTF-8',
                )
            ),
        ));
        $this->add(array(
            'name' => 'expires_after',
            'options' => array(
                'label' => 'Expires After',
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
            $inputFilterSpec = array(
                'id' => array(
                    'required' => false,
                ),
                'page_id' => array(
                    'required' => false,
                ),
                'title' => array(
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'keywords' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'description' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'author' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'robots' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'copyright' => array(
                    'required' => false,
                    'filters' => array(
                        array('name' => 'Zend\Filter\StringTrim'),
                    ),
                ),
                'charset' => array(
                    'required' => false,
                ),
                'expires_after' => array(
                    'required' => false,
                ),
            );
            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }

}