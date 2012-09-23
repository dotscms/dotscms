<?php
namespace Dots\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Form\Form;
use Dots\Form\MultiForm;

class DotsForm extends AbstractHelper
{

    protected function renderElement($elem)
    {
        $view = $this->getView();
        $render = '';
        switch ($elem->getAttribute('type')){
            case 'checkbox':
                $render .= <<<END
                <dt>&nbsp;</dt>
                <dd>
                    {$view->formInput($elem)}
                    {$view->formLabel($elem)}
                    {$view->formElementErrors($elem, array('class' => 'errors'))}
                </dd>
END;
                break;
            case 'hidden':
                $render.="{$view->formInput($elem)}";
                break;

            default:
                $render .= '<dt>';
                if ($elem->getLabel()) {
                    $render .= $view->formLabel($elem);
                } else {
                    $render .= '&nbsp;';
                }
                $render .= '</dt>';
                $render .= <<<END
                <dd>
                    {$view->formInput($elem)}
                    {$view->formElementErrors($elem, array('class' => 'errors'))}
                </dd>
END;
        }
        return $render;
    }

    public function renderFieldset($form)
    {
        $form->prepare();
        $view = $this->getView();
        $render = '';
        $render .= '<fieldset>';
        $render .= '<legend>' . $form->getLabel() . '</legend>';
        if ($form->getOption('description')){
            $render .= "<p>{$form->getOption('description')}</p>";
        }
        $render .= '<dl>';
        foreach ($form->getIterator() as $elem) {
            $render .= $this->renderElement($elem);
        }
        $render .= '</dl>';
        $render .= '</fieldset>';
        return $render;
    }

    public function renderForm($form)
    {
        $form->prepare();
        $view = $this->getView();
        $render = '';
        if ($form->getOption('label')){
            $render .= "<h2>{$form->getOption('label')}</h2>";
        }
        $render .= "<p>{$form->getOption('description')}</p>";
        $render .= '<dl>';
        foreach ($form->getIterator() as $elem) {
            $render .= $this->renderElement($elem);
        }
        $render .= '</dl>';
        return $render;
    }

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
            foreach($form->getFieldsets() as $elem){
                $render.= $this->renderFieldset($elem);
            }
        }else{
            $render .= $this->renderForm($form);
        }
        $render .= $view->form()->closeTag($form);
        return $render;
    }


}