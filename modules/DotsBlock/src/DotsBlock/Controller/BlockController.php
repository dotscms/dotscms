<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsBlock\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Dots\Registry;
use Dots\Form\MultiForm;
use DotsBlock\Db\Entity\Block;
use DotsBlock\Form\Setting\DefaultBlockSettingsForm;

class BlockController extends AbstractActionController
{
    /**
     * Get Edit/Add form
     * @return \Zend\View\Model\ViewModel
     */
    public function getFormAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        $model = json_decode($post['model'], true);
        $alias = $post['alias'];
        $section = $model['section'];
        $type = $model['type'];
        $position = $model['position'];
        $block = null;
        if (isset($model['id'])){
            $blockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\Block');
            $block = $blockModel->getById($model['id']);
        }else{
            $block = new Block();
            $block->type = $type;
            $block->section = $section;
            $block->position = $position;
        }

        $pageModel = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');

        $page = $pageModel->getByAlias($alias);
        $blockManager = Registry::get('block_manager');

        $results = $blockManager->events()->trigger('editBlock/' . $type, $block, array('page' => $page, 'section' => $section, 'request'=>$request));
        $params = array('html'=>$results->last());
        $params['block'] = $block;
        return $this->getTerminalView($params);
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        $method = $post['_method'];

        $blockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\Block');
        $pageModel = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');

        switch($method){
            case 'POST': case 'PUT':
                $model = json_decode($post['model'], true);
                $page = $pageModel->getByAlias($post['alias']);
                $block = null;
                if (isset($model['id']) && !empty($model['id'])){
                    $block = $blockModel->getById($model['id']);
                }else{
                    $block = new Block();
                    $block->position = $model['position'];
                    $block->section = $model['section'];
                    $block->type = $model['type'];
                    $block->page_id = $page->id;
                }

                $blockManager = Registry::get('block_manager');
                $responses = $blockManager->events()->trigger('saveBlock/' . $model['type'], $block, array('page' => $page, 'section' => $model['section'], 'request' => $request));
                if ($responses->stopped()) {
                    return $this->jsonResponse(array('success' => false, 'errors' => $responses->last()));
                }
                $block = $responses->last();
                return $this->jsonResponse(array('success' => true, 'block_id' => $block->id));

                break;
            case 'DELETE':
                echo "DELETE block model not implemented yet.\n\n";
                var_dump($_REQUEST);
                exit;
                break;
            case 'GET':
                echo "GET block model not implemented yet.\n\n";
                var_dump($_REQUEST);
                exit;
                break;
        }

        return $this->jsonResponse(array('success' => false, 'msg'=>'Invalid request'));
    }

    public function editSettingsAction()
    {
        //get instances of the block manager and model classes
        $request = $this->getRequest();
        $blockManager = Registry::get('block_manager');
        $blockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\Block');
        //get the current block based on the provided id
        $id = $_REQUEST['id'];
        $block = $blockModel->get($id);

        //create the complete multiform that should be displayed and populate it with any needed data
        $form = new MultiForm(array(
            'default' => new DefaultBlockSettingsForm()
        ));
        $form->setData(array(
            'default'=>$block->toArray(),
        ));
        $blockManager->events()->trigger('editSettings/' . $block->type, $block, array('form'=>$form, 'request' => $request));

        //on post check if the form is valid and save the data
        if ($request->getMethod() == 'POST'){
            $form->setData($request->getPost()->toArray());
            if ($form->isValid()){
                $data = $form->getInputFilter()->getValues();
                $default = $data['default'];
                $block->class = $default['class'];
                $blockModel->persist($block);
                $blockManager->events()->trigger('saveSettings/' . $block->type, $block, array('form' => $form, 'request' => $request));
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
        $request = $this->getRequest();
        $post = $request->getPost();
        $models = json_decode($post['models'], true);

        $data = array();
        foreach($models as $model){
            if(!empty($model['id'])){
                $data[$model['id']] = $model;
            }
        }

        $ids = array_keys($data);

        if (!empty($ids)){
            $blockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\Block');
            $blocks = $blockModel->getAllById($ids);
            foreach($blocks as $block){
                if ($block->position!= $data[$block->id]['position'] || $block->section != $data[$block->id]['section']){
                    $block->position = $data[$block->id]['position'];
                    $block->section = $data[$block->id]['section'];
                    $blockModel->persist($block);
                }
            }
            $blockModel->flush();
        }

        return $this->jsonResponse(array('success' => true));
    }

    /**
     * Remove existing block
     * @return \Zend\View\Model\ViewModel
     */
    public function removeAction()
    {
        $request = $this->getRequest();
        $blockId = $_REQUEST['block_id'];
        $blockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\Block');

        $block = $blockModel->getById($blockId);
        $blockManager = Registry::get('block_manager');

        $responses = $blockManager->events()->trigger('removeBlock/' . $block->type, $block, array('request' => $request));
        $success = $responses->last();
        return $this->jsonResponse(array('success' => $success, 'block_id' => $blockId));
    }

    /**
     * Either render the view or the edit block if the admin is logged in
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {
        $request = $this->getRequest();
        $blockModel = $this->getServiceLocator()->get('DotsBlock\Db\Model\Block');
        $pageModel = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');
        $view = $this->getServiceLocator()->get('TwigViewRenderer');
        $blockId = $_REQUEST['block_id'];
        $block = $blockModel->getById($blockId);
        $page = $pageModel->getById($block->page_id);
        if ($view->plugin('auth')->isLoggedIn()){
            return $this->getTerminalView( array(
                'block' => $block,
                'page'=>$page,
                'request' => $request
            ), array('template'=>'dots-block/handler/edit-block') );
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
     * @return bool|\Zend\View\Model\ModelInterface
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
     * @return \Zend\View\Model\ModelInterface
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