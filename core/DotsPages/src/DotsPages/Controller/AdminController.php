<?php
namespace DotsPages\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\Json\Encoder,

    DotsPages\Db\Entity,

    Dots\Form\MultiForm,
    DotsPages\Form\Page,
    DotsPages\Form\PageMeta;

class AdminController extends AbstractActionController
{
    /**
     * Show add view or add a new page
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        //init used variables
        $form = new MultiForm( array(
            'page' => new Page(),
            'meta' => new PageMeta(),
        ));
        $request = $this->getRequest();
        $response = $this->getResponse();

        //on post return the response as a json string
        if ($request->getMethod()=='POST'){
            //handle form errors
            $errorResponse = $this->handleErrors($form);
            if ($errorResponse) {
                return $errorResponse;
            }
            //get the form values
            $data = $form->getValues();
            //save the page entity
            $page = new Entity\Page();
            $this->updateObject($page, $data['page']);
            $page->save();
            //save the page meta
            $meta = new Entity\PageMeta();
            $this->updateObject($meta, $data['meta']);
            $meta->page_id = $page->id;
            $meta->save();

            return $this->jsonResponse(array(
                'success' => true,
                'action' => 'window.location = "/'. urlencode($page->alias) .'";'
            ));
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
        //init used variables
        $form = new MultiForm(array(
            'page' => new Page(),
            'meta' => new PageMeta(),
        ));
        $request = $this->getRequest();
        $response = $this->getResponse();
        $alias = $_REQUEST['alias'];
        $pageModel = $this->getLocator()->get('DotsPages\Db\Model\Page');
        $metaModel = $this->getLocator()->get('DotsPages\Db\Model\PageMeta');
        $page = $pageModel->getByAlias($alias);
        $meta = $metaModel->getByPageId($page->id);

        //on post return the response as a json string
        if ($request->getMethod() == 'POST') {
            //handle form errors
            $errorResponse = $this->handleErrors($form);
            if ($errorResponse) {
                return $errorResponse;
            }
            //get the form values
            $data = $form->getValues();
            //save the page entity
            $this->updateObject($page, $data['page']);
            $page->save();
            //save the page meta
            $this->updateObject($meta, $data['meta']);
            $meta->page_id = $page->id;
            $meta->save();

            return $this->jsonResponse(array(
                'success' => true,
                'action' => 'window.location = "/' . urlencode($page->alias) . '";'
            ));
        }else{
            $form->getSubForm('page')->populate($page->toArray());
            $form->getSubForm('meta')->populate($meta->toArray());
        }

        return $this->getTeminalView(
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
        $pageModel = $this->getLocator()->get('DotsPages\Db\Model\Page');
        $pageMetaModel = $this->getLocator()->get('DotsPages\Db\Model\PageMeta');
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
    private function getTeminalView($vars = array(), $options = array())
    {
        $viewModel = new ViewModel($vars, $options);
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     * Check if the form is valid and return a response object if invalid
     * @param $form
     * @return bool|\Zend\Stdlib\ResponseDescription
     */
    private function handleErrors($form)
    {
        $response = $this->getResponse();
        $request = $this->getRequest();
        if (!$form->isValid($request->post()->toArray())) {
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
     * @return \Zend\Stdlib\ResponseDescription
     */
    private function jsonResponse ($data)
    {
        $response = $this->getResponse();
        $json = Encoder::encode($data);
        $response->setContent($json);
        return $response;
    }

    /**
     * Update object with received data
     * @param $obj
     * @param $data
     * @return mixed
     */
    private function updateObject($obj, $data)
    {
        foreach($data as $key=>$value){
            $obj->$key = $value;
        }
        return $obj;
    }
}