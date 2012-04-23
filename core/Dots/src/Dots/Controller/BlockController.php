<?php
namespace Dots\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Zend\Json\Encoder,

    Dots\Module,
    Dots\Db\Entity;

class BlockController extends ActionController
{
    /**
     * Add a new block
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction()
    {
        $alias = $_REQUEST['alias'];
        $type = $_REQUEST['type'];
        $section = $_REQUEST['section'];
        $pageModel = $this->getLocator()->get('DotsPages\Db\Model\Page');

        $page = $pageModel->getByAlias($alias);
        $blockManager = Module::blockManager();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST'){
            $responses = $blockManager->events()->trigger('saveBlock/'.$type, null, array('page'=>$page, 'section' => $section));
            if ($responses->stopped()){
                return $this->jsonResponse(array('success' => false, 'errors'=>$responses->last()));
            }
            $block = $responses->last();
            return $this->jsonResponse(array('success'=>true, 'block_id'=>$block->id));
        }

        $results = $blockManager->events()->trigger('editBlock/' . $type, null, array('page' => $page, 'section' => $section));
        return $this->getTeminalView(array('html'=>$results->last()));
    }

    /**
     * Edit existing block
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        $alias = $_REQUEST['alias'];
        $section = $_REQUEST['section'];
        $blockId = $_REQUEST['block_id'];

        $blockModel = $this->getLocator()->get('Dots\Db\Model\Block');
        $pageModel = $this->getLocator()->get('DotsPages\Db\Model\Page');

        $page = $pageModel->getByAlias($alias);
        $block = $blockModel->getById($blockId);
        $blockManager = Module::blockManager();

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST'){
            $responses = $blockManager->events()->trigger('saveBlock/'. $block->type, $block, array('page'=>$page, 'section' => $section));
            if ($responses->stopped()) {
                return $this->jsonResponse(array('success' => false, 'errors' => $responses->last()));
            }
            $block = $responses->last();
            return $this->jsonResponse(array('success' => true, 'block_id' => $block->id));
        }

        $results = $blockManager->events()->trigger('editBlock/' . $block->type, $block, array('page' => $page, 'section' => $section));
        return $this->getTeminalView(array(
            'block'=>$block,
            'html'=>$results->last()
        ));
    }

    /**
     * Update existing block positions
     * @return \Zend\View\Model\ViewModel
     */
    public function moveAction()
    {
        $blockId = $_REQUEST['block_id'];
        $blockModel = $this->getLocator()->get('Dots\Db\Model\Block');
        $block = $blockModel->getById($blockId);
//        $fromSection = $_REQUEST['from'];
        $fromSection = $block->section;
        $toSection = $_REQUEST['to'];
        $alias = $_REQUEST['alias'];
        $blockId = $_REQUEST['block_id'];
        $position = $_REQUEST['position'];
        $pageId = $block->page_id;
        $oldPosition = $block->position;
        $block->section = $toSection;
        $block->position = $position;
        if ($fromSection==$toSection){

        }else{

        }
        $prevBlocks = $blockModel->getAllByColumnsOrderByPosition(array(
            'page_id = ?' => $pageId,
            'section = ?' => $fromSection,
            'position >= ?' => $oldPosition,
        ));
        var_dump($prevBlocks);

        return $this->jsonResponse(array('success' => true));
    }

    /**
     * Remove existing block
     * @return \Zend\View\Model\ViewModel
     */
    public function removeAction()
    {
        $blockId = $_REQUEST['block_id'];
        $blockModel = $this->getLocator()->get('Dots\Db\Model\Block');

        $block = $blockModel->getById($blockId);
        $blockManager = Module::blockManager();

        $responses = $blockManager->events()->trigger('removeBlock/' . $block->type, $block, array());
        $success = $responses->last();
        return $this->jsonResponse(array('success' => $success, 'block_id' => $blockId));
    }

    /**
     * Either render the view or the edit block if the admin is logged in
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {
        $blockModel = $this->getLocator()->get('Dots\Db\Model\Block');
        $pageModel = $this->getLocator()->get('DotsPages\Db\Model\Page');
        $view = $this->getLocator()->get('view');
        $blockId = $_REQUEST['block_id'];
        $block = $blockModel->getById($blockId);
        $page = $pageModel->getById($block->page_id);
        if ($view->plugin('auth')->isLoggedIn()){
            return $this->getTeminalView( array(
                'block' => $block,
                'page'=>$page
            ), array('template'=>'dots/blocks/edit-block') );
        }
        return $this->getTeminalView(array(
            'block' => $block,
            'page' => $page
        ) );
    }

    /**
     * Return a view model that does not render the layout
     * @param array $vars
     * @param array $options
     * @return \Zend\View\Model\ViewModel
     */
    private function getTeminalView($vars = array(), $options = array())
    {
        $template = null;
        if (array_key_exists('template', $options)){
            $template = $options['template'];
            unset($options['template']);
        }
        $viewModel = new ViewModel($vars, $options);
        if ($template){
            $viewModel->setTemplate($template);
        }
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