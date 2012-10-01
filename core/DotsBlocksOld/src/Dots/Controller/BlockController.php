<?php
namespace Dots\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\Json\Encoder,
    Zend\View\Model\JsonModel,

    Dots\Module,
    Dots\Db\Entity;

class BlockController extends AbstractActionController
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
        return $this->getTerminalView(array('html'=>$results->last()));
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
        return $this->getTerminalView(array(
            'block'=>$block,
            'html'=>$results->last()
        ));
    }

    public function editSettingsAction()
    {
        //get instances of the block manager and model classes
        $request = $this->getRequest();
        $blockManager = Module::blockManager();
        $blockModel = $this->getLocator()->get('Dots\Db\Model\Block');
        //get the current block based on the provided id
        $id = $_REQUEST['id'];
        $block = $blockModel->get($id);

        //create the complete multiform that should be displayed and populate it with any needed data
        $form = new \Dots\Form\MultiForm(array(
            'default' => new \Dots\Form\Setting\DefaultBlockSettingsForm()
        ));
        $form->getSubForm('default')->populate($block->toArray());
        $blockManager->events()->trigger('editSettings/' . $block->type, $block, array('form'=>$form));

        //on post check if the form is valid and save the data
        if ($request->isPost()){
            if ($form->isValid($request->post()->toArray())){
                $default = $form->getSubForm('default')->getValues(true);
                $block->class = $default['class'];
                $blockModel->persist($block);
                $blockManager->events()->trigger('saveSettings/' . $block->type, $block, array('form' => $form));
                $blockModel->flush();
                return $this->jsonResponse(array('success' => true, 'block_id' => $block->id));
            }else{
                return $this->jsonResponse(array('success' => false, 'errors' => $form->getMessages()));
            }
        }

        return $this->getTerminalView(array(
            'form' => $form
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
        $fromSection = $block->section;
        $toSection = $_REQUEST['to'];
        $alias = $_REQUEST['alias'];
        $position = $_REQUEST['position'];
        $pageId = $block->page_id;
        $oldPosition = $block->position;
        $block->section = $toSection;
        $block->position = $position;
        if ($fromSection==$toSection){
            $blocks = $blockModel->getAllByColumnsOrderByPosition(array(
                'page_id = ?' => $pageId,
                'section = ?' => $fromSection,
                'id != ?' => $blockId
            ));
            $pos = 1;
            if ($blocks){
                foreach ($blocks as $blk){
                    if ($pos == $position) {
                        $pos++;
                    }
                    $blk->position = $pos++;
                    $blockModel->persist($blk);
                }
            }
            $blockModel->persist($block);

        }else{
            $fromBlocks = $blockModel->getAllByColumnsOrderByPosition(array(
                'page_id = ?' => $pageId,
                'section = ?' => $fromSection,
                'id != ?' => $blockId
            ));
            $pos = 1;
            if ($fromBlocks) {
                foreach ($fromBlocks as $blk) {
                    $blk->position = $pos++;
                    $blockModel->persist($blk);
                }
            }
            $toBlocks = $blockModel->getAllByColumnsOrderByPosition(array(
                'page_id = ?' => $pageId,
                'section = ?' => $toSection,
                'id != ?' => $blockId
            ));
            $pos = 1;
            if ($toBlocks) {
                foreach ($toBlocks as $blk) {
                    if ($block->position == $pos){
                        $pos++;
                    }
                    $blk->position = $pos++;
                    $blockModel->persist($blk);
                }
            }
            $blockModel->persist($block);
        }
        $blockModel->flush();

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
            return $this->getTerminalView( array(
                'block' => $block,
                'page'=>$page
            ), array('template'=>'dots/blocks/edit-block') );
        }
        return $this->getTerminalView(array(
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
    private function getTerminalView($vars = array(), $options = array())
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
        return new JsonModel($data);
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