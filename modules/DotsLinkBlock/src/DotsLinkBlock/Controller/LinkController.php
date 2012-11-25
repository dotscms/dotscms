<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsLinkBlock\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Dots\Form\MultiForm;
use Dots\File\Upload;
use DotsBlock\Db\Entity\Block;
use DotsLinkBlock\Db\Entity\LinkBlock;
use DotsLinkBlock\Form\Content\Link as LinkContentForm;

/**
 * Link Controller
 */
class LinkController extends AbstractActionController
{
    /**
     * Block type
     */
    const TYPE = 'links_content';

    public function addAction()
    {
        $form = new LinkContentForm();
        $form->addButtons();
        $output = $this->renderViewModel('dots-link-block/form', array('form' => $form));
        $this->getResponse()->setContent($output);
        return $this->getResponse();
    }

    public function getPagesAction()
    {
        $modelPage = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $pages = $modelPage->getAllLikeTitle('%' . $QUERY['term'] . '%');
        $data = array();
        foreach($pages as $page){
            $data[] = array(
                'id'=>$page->id,
                'label'=>$page->title,
                'value'=>$page->title,
            );
        }
        return $this->jsonResponse($data);
    }

    public function removeAction()
    {
        $modelLinkBlock = $this->getServiceLocator()->get('DotsLinkBlock\Db\Model\LinkBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $link_id = $QUERY['id'];
        $link = $modelLinkBlock->getById($link_id);
        $link->delete();
        return $this->jsonResponse(array('success' => true, 'id' => $link_id));
    }

    public function editAction()
    {
        $modelPage = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');
        $modelLinkBlock = $this->getServiceLocator()->get('DotsLinkBlock\Db\Model\LinkBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $link = $modelLinkBlock->getById($QUERY['id']);
        $data = $link->toArray();
        switch($data['type']){
            case 'link':
                $data['link'] = $data['href'];
                break;
            case 'page':
                if ($data['entity_id']){
                    $page = $modelPage->getById($data['entity_id']);
                    $data['page'] = $page->title;
                }
                break;
        }

        $form = new LinkContentForm();
        $form->addButtons();
        $form->setData($data);

        $response = $this->renderViewModel('dots-link-block/form', array('form' => $form));
        $this->getResponse()->setContent($response);

        return $this->getResponse();
    }

    public function saveAction()
    {
        $locator = $this->getServiceLocator();
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('DotsBlock\Db\Model\Block');
        $modelLinkBlock = $locator->get('DotsLinkBlock\Db\Model\LinkBlock');

        $POST = $this->getRequest()->getPost()->toArray();
        $FILES = $this->getRequest()->getFiles()->toArray();
        $form = new LinkContentForm();
        if (!empty($FILES)){
            $POST['file'] = $FILES['file'];
        }
        $form->setData($POST);
        if ($form->isValid()){
            $data = $form->getInputFilter()->getValues();

            $page = $modelPage->getByAlias($POST['alias']);
            if ($POST['block_id']) {
                $block = $modelBlock->getById($POST['block_id']);
            } else {
                $block = new Block();
                $block->page_id = $page->id;
                $block->section = $POST['section'];
                $block->type = self::TYPE;
                $block->position = $POST['position'];
                $block->save();
            }
            if ($data['id']){
                $linkBlock = $modelLinkBlock->getById($data['id']);
            }else{
                $linkBlock = new LinkBlock();
                $linkBlock->block_id = $block->id;
            }
            if ($linkBlock->type == 'file' && $data['type']!='file' && $linkBlock->href){
                unset($linkBlock->href);
            }
            $linkBlock->type = $data['type'];
            $linkBlock->title = $data['title'];
            $linkBlock->position = $data['position'];
            switch ($linkBlock->type){
                case 'link':
                    $linkBlock->href = $data['link'];
                    break;
                case 'file':
                    $upload = new Upload(array(
                        'path' => 'data/uploads/',
                        'destination' => PUBLIC_PATH . '/'
                    ));
                    $path = $upload->process(array('file'=>$POST['file']));
                    $data['file'] = $path['file'];

                    if ($data['file']) {
                        if ($linkBlock->href)
                            unset($linkBlock->href);
                        $linkBlock->href = $data['file'];
                    }

                    break;
                case 'page':
                    $selectedPage = $modelPage->getById($data['entity_id']);
                    $linkBlock->entity_id = $data['entity_id'];
                    $linkBlock->href = $selectedPage->alias;
                    break;
            }
            if (!is_numeric($data['position'])){
                $linkBlock->position = 1;
            }

            $linkBlock->save();

            return $this->jsonResponse(array('success' => true, 'data'=>$linkBlock->toArray()));
        }
        return $this->jsonResponse(array(
            'success' => false,
            'errors'=>$form->getMessages()
        ));
    }

    public function moveAction(){
        // get variables from request
        $blockId = $_REQUEST['block_id'];
        $linkId = $_REQUEST['id'];
        // get link block model
        $linkBlockModel = $this->getServiceLocator()->get('DotsLinkBlock\Db\Model\LinkBlock');
        // get an instance of the item that changes position and set the new position
        $link = $linkBlockModel->getById($linkId);
        $position = $_REQUEST['position'];
        $link->position = $position;
        // get all other items from the link list
        $links = $linkBlockModel->getAllByColumnsOrderByPosition(array(
            'block_id = ?' => $blockId,
            'id != ?' => $linkId
        ));

        // update positions for all items
        $pos = 1;
        if ($links){
            foreach ($links as $lnk){
                if ($pos==$position){
                    $pos++;
                }
                $lnk->position = $pos++;
                $linkBlockModel->persist($lnk);
            }
        }
        $linkBlockModel->persist($link);
        // save everything in the DB
        $linkBlockModel->flush();

        return $this->jsonResponse(array('success' => true));
    }

    /**
     * Return a Json Response
     * @param $data
     * @return \Zend\View\Model\JsonModel
     */
    private function jsonResponse($data)
    {
        return new JsonModel($data);
    }

    private function renderViewModel($template = null, $vars = array(), $render=true)
    {
        $view = $this->getServiceLocator()->get('view');
        $viewModel = new ViewModel($vars, array(
            'has_parent' => true
        ));
        $viewModel->setTemplate($template)
            ->setTerminal(true);
        if ($render){
            return $view->render($viewModel);
        }
        return $viewModel;
    }

    /**
     * Get the form used for editing links
     * @param null $linkBlocks
     * @return \Dots\Form\MultiForm
     */
    public function getForm($linkBlocks = null)
    {
        $form = new MultiForm(array());
        $form->setParams(array(
            'links' => $linkBlocks
        ));
        return $form;
    }

}