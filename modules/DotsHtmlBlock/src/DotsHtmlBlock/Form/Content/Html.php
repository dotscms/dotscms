<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsHtmlBlock\Form\Content;

use Dots\Form\Form;
use Zend\InputFilter\Factory as InputFilterFactory;

class Html extends Form
{
    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'block_id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        // add html class element
        $this->add(array(
            'name' => 'content',
            'options' => array(
                'label' => 'Content',
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => 6,
                'cols' => 40,
                'class' => 'editor',
            ),
        ));
        $this->setWrapElements(false);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $factory = new InputFilterFactory();
            $inputFilterSpec = array();
            $inputFilterSpec['content'] = array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
            );
            $this->filter = $factory->createInputFilter($inputFilterSpec);
        }
        return $this->filter;
    }

}