<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsNavBlock\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Dots\Form\MultiForm;
use DotsBlock\Db\Entity\Block;
use DotsNavBlock\Db\Entity\NavigationBlock;
use DotsNavBlock\Form\Content\Navigation as NavigationContentForm;

/**
 * Navigation Controller
 */
class NavigationController extends AbstractActionController
{
    /**
     * Block type
     */
    const TYPE = 'navigation';

    /**
     * Returns a JsonModel that contains a list of all the pages that contain the provided query
     * @return \Zend\View\Model\JsonModel
     */
    public function getPagesAction()
    {
        $modelPage = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');
        $query = $this->getRequest()->getQuery()->toArray();
        $pages = $modelPage->getAllLikeTitle('%' . $query['term'] . '%');
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

    /**
     * Remove a navigation item
     * @return \Zend\View\Model\JsonModel
     */
    public function removeAction()
    {
        $modelNavBlock = $this->getServiceLocator()->get('DotsNavBlock\Db\Model\NavigationBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $nav_id = $QUERY['id'];
        $nav = $modelNavBlock->getById($nav_id);
        $nav->delete();
        return $this->jsonResponse(array('success' => true, 'id' => $nav_id));
    }

    /**
     * Render the add navigation item page
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function addAction()
    {
        $form = $this->getEditItemForm();
        $response = $this->getResponse();
        $content = $this->renderViewModel('dots-nav-block/item-add', array('form' => $form));
        $response->setContent($content);
        return $response;
    }

    /**
     * Render the edit navigation item page
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function editAction()
    {
        $modelPage = $this->getServiceLocator()->get('DotsPages\Db\Model\Page');
        $modelNavBlock = $this->getServiceLocator()->get('DotsNavBlock\Db\Model\NavigationBlock');
        $QUERY = $this->getRequest()->getQuery()->toArray();
        $nav = $modelNavBlock->getById($QUERY['id']);
        $data = $nav->toArray();
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

        $form = $this->getEditItemForm();
        $form->setData(array('navigation'=>$data));
        $response = $this->getResponse();
        $content = $this->renderViewModel('dots-nav-block/item-edit', array('form' => $form));
        $response->setContent($content);
        return $response;
    }

    /**
     * Persist changes to a navigation item
     * @return \Zend\View\Model\JsonModel
     */
    public function saveAction()
    {
        $locator = $this->getServiceLocator();
        $modelPage = $locator->get('DotsPages\Db\Model\Page');
        $modelBlock = $locator->get('DotsBlock\Db\Model\Block');
        $modelNavBlock = $locator->get('DotsNavBlock\Db\Model\NavigationBlock');

        $POST = $this->getRequest()->getPost()->toArray();
        $form = $this->getEditItemForm();
        $form->setData($POST);
        if ($form->isValid()){
            $data = $form->getInputFilter()->getValues();
            $data = $data['navigation'];
//            $data = $form->getSubForm('navigation')->getValues(true);

            $page = $modelPage->getByAlias($POST['alias']);
            if (array_key_exists('block_id', $POST) && $POST['block_id']) {
                $block = $modelBlock->getById($POST['block_id']);
            } else {
                $block = new Block();
                $block->page_id = $page->id;
                $block->section = $POST['section'];
                $block->type = self::TYPE;
                $block->position = $POST['position'];
                $block->save();
            }
            if (array_key_exists('id', $data) && $data['id']){
                $navBlock = $modelNavBlock->getById($data['id']);
            }else{
                $navBlock = new NavigationBlock();
                $navBlock->block_id = $block->id;
            }
            if ($navBlock->type == 'file' && $data['type']!='file' && $navBlock->href){
                unset($navBlock->href);
            }
            $navBlock->type = $data['type'];
            $navBlock->title = $data['title'];
            $navBlock->position = $data['position'];
            switch ($navBlock->type){
                case 'link':
                    $navBlock->href = $data['link'];
                    break;
                case 'page':
                    $selectedPage = $modelPage->getById($data['entity_id']);
                    $navBlock->entity_id = $data['entity_id'];
                    $navBlock->href = $selectedPage->alias;
                    break;
            }
            if (!is_numeric($data['position'])){
                $navBlock->position = 1;
            }
            $navBlock->save();

            return $this->jsonResponse(array('success' => true, 'data'=> $navBlock->toArray()));
        }
        return $this->jsonResponse(array(
            'success' => false,
            'errors'=>$form->getMessages()
        ));
    }

    /**
     * Move handler for navigation items
     * @return \Zend\View\Model\JsonModel
     */
    public function moveAction(){
        // get variables from request
        $query = $this->getRequest()->getQuery()->toArray();
        $blockId = $query['block_id'];
        $navId = $query['id'];
        $position = $query['position'];
        // get navigation block model
        $navBlockModel = $this->getServiceLocator()->get('DotsNavBlock\Db\Model\NavigationBlock');
        // get an instance of the item that changes position and set the new position
        $nav = $navBlockModel->getById($navId);
        $nav->position = $position;
        // get all other items from the navigation
        $items = $navBlockModel->getAllByColumnsOrderByPosition(array(
            'block_id = ?' => $blockId,
            'id != ?' => $navId
        ));

        // update positions for all items
        $pos = 1;
        if ($items){
            foreach ($items as $itm){
                if ($pos==$position){
                    $pos++;
                }
                $itm->position = $pos++;
                $navBlockModel->persist($itm);
            }
        }
        $navBlockModel->persist($nav);
        // save everything in the DB
        $navBlockModel->flush();

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

    /**
     * Render a specific template
     * @param null $template
     * @param array $vars
     * @return string
     */
    private function renderViewModel($template = null, $vars = array())
    {
        $view = $this->getServiceLocator()->get('view');
        $viewModel = new ViewModel($vars, array('has_parent' => true));
        $viewModel->setTemplate($template)
            ->setTerminal(true);
        return $view->render($viewModel);
    }

    /**
     * Get edit navigation item form
     */
    public function getEditItemForm($data = null)
    {
        $navForm = new NavigationContentForm();
        $form = new MultiForm(array('navigation'=>$navForm));
        if ($data!==null)
            $form->setData($data);
        return $form;
    }

}