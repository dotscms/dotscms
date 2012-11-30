<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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
        if(method_exists($form, 'getInputFilter')){
            $form->setMessages($form->getInputFilter()->getMessages());
        }
        $view = $this->getView();
        $render = '';
        $render .= $view->plugin('form')->openTag($form);
        if ($form instanceof MultiForm){
            $render .= $view->render('dots/helpers/dots-form/multi-form', array('form'=>$form));
        }else{
            $render .= $view->render('dots/helpers/dots-form/form', array('form' => $form));
        }
        $render .= $view->plugin('form')->closeTag($form);
        return $render;
    }

}