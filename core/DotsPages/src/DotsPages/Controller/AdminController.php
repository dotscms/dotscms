<?php
namespace DotsPages\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Zend\Json\Encoder,
    DotsPages\Form\Page;

class AdminController extends ActionController
{
    /**
     * Show add view or add a new page
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        $form = new Page();
        if ($this->getRequest()->getMethod()=='POST'){
            $response = $this->getResponse();
            if ($form->isValid($this->getRequest()->post()->toArray())){
                $json = Encoder::encode(array(
                    'success' => true,
                    'action' => 'window.location = "/";'
                ));
                $response->setContent($json);
                return $response;
            }else{
                $json = Encoder::encode(array(
                    'success' => false,
                    'errors' => $form->getMessages()
                ));
                $response->setContent($json);
                return $response;
            }
        }

        return $this->getTeminalView(
            array(
                'form' => $form
            )
        );
    }

    /**
     * Show edit view or update the page
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        return $this->getTeminalView();
    }

    /**
     * Remove the current page
     * @return \Zend\View\Model\ViewModel
     */
    public function removeAction()
    {
        return $this->getTeminalView();
    }

    /**
     * Return a view model that does not render the layout
     * @param array $vars
     * @param array $options
     * @return \Zend\View\Model\ViewModel
     */
    private function getTeminalView($vars = array(), $options = array())
    {
        $viewModel = new ViewModel($vars, $options);
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}
