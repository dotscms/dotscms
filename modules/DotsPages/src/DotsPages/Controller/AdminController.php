<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsPages\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Encoder;

use DotsPages\Db\Entity;

use Dots\Form\MultiForm;
use DotsPages\Form\Page;
use DotsPages\Form\PageMeta;

class AdminController extends AbstractActionController
{

    public function manageAction()
    {

        return new ViewModel();
    }
    /**
     * Show add view or add a new page
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        $config = $this->getServiceLocator()->get('Configuration');
        //init used variables
        $form = new MultiForm( array(
            'page' => new Page($config['dots-pages']['templates']),
            'meta' => new PageMeta(),
        ));
        $request = $this->getRequest();

        //on post return the response as a json string
        if ($request->getMethod()=='POST'){
            //handle form errors
            $errorResponse = $this->handleErrors($form);
            if ($errorResponse) {
                return $errorResponse;
            }

            //get the form values
            $data = $form->getInputFilter()->getValues();

            //save the page entity
            $page = new Entity\Page();
            $page->populate($data['page']);
            $page->save();
            //save the page meta
            $meta = new Entity\PageMeta();
            $meta->populate($data['meta']);
            $meta->expires_after = (empty($meta->expires_after)?null:$meta->expires_after);
            $meta->page_id = $page->id;
            $meta->save();

            return $this->jsonResponse(array(
                'success' => true,
                'action' => 'window.location = "/'. urlencode($page->alias) .'";'
            ));
        }

        return $this->getTerminalView(
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
        $config = $this->getServiceLocator()->get('Configuration');
        //init used variables
        $form = new MultiForm(array(
            'page' => new Page($config['dots-pages']['templates']),
            'meta' => new PageMeta(),
        ));
        $request = $this->getRequest();
        $response = $this->getResponse();
        $alias = $_REQUEST['alias'];
        $pageModel = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');
        $metaModel = $this->getServiceLocator()->get('DotsPages\Db\Model\PageMeta');
        $page = $pageModel->getByAlias($alias);
        $meta = $metaModel->getByPageId($page->id);
        if (!$meta){
            $meta = new Entity\PageMeta();
            $meta->page_id = $page->id;
        }

        //on post return the response as a json string
        if ($request->getMethod() == 'POST') {
            //handle form errors
            $errorResponse = $this->handleErrors($form);
            if ($errorResponse) {
                return $errorResponse;
            }
            //get the form values
            $data = $form->getInputFilter()->getValues();
            //save the page entity
            $page->populate($data['page']);
            $page->save();
            //save the page meta
            $meta->populate($data['meta']);
            $meta->expires_after = (empty($meta->expires_after) ? null : $meta->expires_after);
            $meta->page_id = $page->id;
            $meta->save();

            return $this->jsonResponse(array(
                'success' => true,
                'action' => 'window.location = "/' . urlencode($page->alias) . '";'
            ));
        }else{
            $form->setData(array(
                'page'=> $page->toArray(),
                'meta'=> $meta->toArray()
            ));
        }

        return $this->getTerminalView(
            array(
                'form' => $form
            )
        );
    }

    /**
     * Remove the current page
     * @return \Zend\View\Model\ViewModel
     */
    public function removeAction()
    {
        $alias = $_REQUEST['alias'];
        $pageModel = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');
        $pageMetaModel = $this->getServiceLocator()->get('DotsPages\Db\Model\PageMeta');
        $page = $pageModel->getByAlias($alias);
        $page_id = $page->id;
        $pageMeta = $pageMetaModel->getByPageId($page_id);
        $pageMeta->delete();
        $page->delete();
        return $this->jsonResponse(array(
            'success' => true,
            'action' => 'window.location = "/";'
        ));
    }



    /**
     * Return a view model that does not render the layout
     * @param array $vars
     * @param array $options
     * @return \Zend\View\Model\ViewModel
     */
    private function getTerminalView($vars = array(), $options = array())
    {
        $viewModel = new ViewModel($vars, $options);
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     * Check if the form is valid and return a response object if invalid
     * @param \Zend\Form\Form $form
     * @return bool|\Zend\Stdlib\ResponseInterface
     */
    private function handleErrors(\Zend\Form\Form $form)
    {
        $request = $this->getRequest();
        $form->setData($request->getPost()->toArray());
        if (!$form->isValid()) {
            return $this->jsonResponse(array(
                'success' => false,
                'errors' => $form->getMessages()
            ));
        }
        return false;
    }

    /**
     * Create a json response based on the data
     * @param $data
     * @return \Zend\Stdlib\ResponseInterface
     */
    private function jsonResponse ($data)
    {
        $response = $this->getResponse();
        $json = Encoder::encode($data);
        $response->setContent($json);
        return $response;
    }
}