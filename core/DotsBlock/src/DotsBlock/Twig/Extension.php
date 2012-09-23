<?php
namespace DotsBlock\Twig;

use Zend\EventManager\EventManager;
use Zend\EventManager\Event;
use Twig_Extension;
use Twig_Filter_Method;
use Dots\Registry;
use DotsBlock\Db\Entity\Block;
use DotsBlock\Db\Model\Block as BlockModel;
use DotsBlock\Twig\Extension\Section\TokenParser as SectionTokenParser;

/**
 * Twig Extension for ZeTwig
 */
class Extension extends Twig_Extension
{
    /**
     * @var \Zend\EventManager\EventManager | null
     */
    protected $events = null;

    /**
     * Returns the name of the extension.
     * @return string The extension name
     */
    function getName()
    {
        return 'DotsBlock';
    }

    /**
     * Return a list of token parsers to register with the envirionment
     * @return array
     */
    public function getTokenParsers()
    {
        return array(
            new SectionTokenParser(),
        );
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'renderBlock' => new Twig_Filter_Method($this, 'renderBlock'),
            'renderEditBlock' => new Twig_Filter_Method($this, 'renderEditBlock'),
        );
    }

    public function renderBlock($block, $page = null)
    {
        if ($block instanceof Block){
            $blockManager = Registry::get('block_manager');
            $type = $block->type;
            $results = $blockManager->events()->trigger('renderBlock/'.$type, $block, array('page'=>$page));
            return $results->last();
        }
        return $block;
    }

    public function renderEditBlock($block, $page = null)
    {
        if ($block instanceof Block) {
            $blockManager = Registry::get('block_manager');
            $type = $block->type;
            $results = $blockManager->events()->trigger('editBlock/' . $type, $block, array('page' => $page));
            return $results->last();
        }
        return $block;
    }

    /**
     * Render a particular section
     * @todo Revise how sections are represented and how they are marked as static.
     *       Perhaps we should not set the id of the page if the block is added to a static section.
     *       There should be an attribute in the generated html that would allow the script to differenciate between static and non static sections.
     * @param $name
     * @param $page
     * @param $params
     * @return string
     */
    public function renderSection($name, $page, $params)
    {
        $view = Registry::get('service_locator')->get('TwigViewRenderer');
        $edit = $view->plugin("auth")->isLoggedIn();

        $model = Registry::get('service_locator')->get('DotsBlock\Db\Model\Block');
        $is_static = (isset($params['is_static']) && $params['is_static']);
        if ($is_static){
            $blocks = $model->getAllBySectionOrderByPosition($name);
        }else{
            $blocks = $model->getAllByPageIdAndSectionOrderByPosition($page->id, $name);
        }

        if (!$blocks) {
            $blocks = array();
        }

        if ($edit){
            $blockManager = Registry::get('block_manager');
            $block_handlers = $blockManager->getContentBlockHandlers();
            return $view->render('dots-block/handler/edit-blocks', array(
                'blocks' => $blocks,
                'handlers' => $block_handlers,
                'page' => $page,
                'section' => $name,
                'is_static'=> $is_static
            ));
        }else{
            return $view->render('dots-block/handler/render', array(
                'blocks'=>$blocks,
                'page'=>$page,
                'section'=>$name,
            ));
        }
    }

}