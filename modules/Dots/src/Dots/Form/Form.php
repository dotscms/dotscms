<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\Form;
use Zend\Form\Form as BaseForm;

class Form extends BaseForm
{

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->init();
    }

    public function init()
    {
        //Add elements here
    }

    public function setData($data)
    {
        $this->filter = null;
        return parent::setData($data);
    }

    public function prepareElement(BaseForm $form)
    {
        $name = $this->getName();
        if ($name){
            parent::prepareElement($form);
        }
    }

    public function setDescription($description)
    {
        $this->options['description'] = $description;
    }

    public function getDescription()
    {
        if (!isset($this->options['description'])){
            return null;
        }
        return $this->options['description'];
    }

}