<?php
namespace Dots\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Form\Form;
use Dots\Form\MultiForm;

class DotsForm extends AbstractHelper
{

    public function __invoke($form = null, $options=null)
    {
        if ($form == null){
            return $this;
        }
        $form->prepare();
        $view = $this->getView();
        $render = '';
        $render .= $view->form()->openTag($form);
        if ($form instanceof MultiForm){
            $render .= $view->render('dots/helpers/dots-form/multi-form', array('form'=>$form));
        }else{
            $render .= $view->render('dots/helpers/dots-form/form', array('form' => $form));
        }
        $render .= $view->form()->closeTag($form);
        return $render;
    }

}