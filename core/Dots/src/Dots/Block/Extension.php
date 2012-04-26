<?php
namespace Dots\Block;

use Zend\EventManager\EventManager,
    Zend\EventManager\EventCollection,
    Zend\EventManager\Event,
    Twig_Extension,
    Twig_Filter_Method,


    Dots\Module,
    Dots\Db\Entity\Block,
    Dots\Db\Model\Block as BlockModel,
    Dots\Block\Extension\ContentBlock\TokenParser as ContentBlockTokenParser;

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
        return 'DotsBlocks';
    }

    /**
     * Return a list of token parsers to register with the envirionment
     * @return array
     */
    public function getTokenParsers()
    {
        return array(
            new ContentBlockTokenParser(),
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
            $blockManager = Module::blockManager();
            $type = $block->type;
            $results = $blockManager->events()->trigger('renderBlock/'.$type, $block, array('page'=>$page));
            return $results->last();
        }
        return $block;
    }

    public function renderEditBlock($block, $page = null)
    {
        if ($block instanceof Block) {
            $blockManager = Module::blockManager();
            $type = $block->type;
            $results = $blockManager->events()->trigger('editBlock/' . $type, $block, array('page' => $page));
            return $results->last();
        }
        return $block;
    }

    public function renderContentBlock($name, $page, $params)
    {
        $view = Module::locator()->get('view');
        $edit = $view->plugin("auth")->isLoggedIn();

        $model = Module::locator()->get('Dots\Db\Model\Block');
        $blocks = $model->getAllByPageIdAndSection($page->id, $name);
        if (!$blocks) {
            $blocks = array();
        }

        if ($edit){
            $blockManager = Module::blockManager();
            $block_handlers = $blockManager->getContentBlockHandlers();
            return $view->render('dots/blocks/edit-blocks', array(
                'blocks' => $blocks,
                'handlers' => $block_handlers,
                'page' => $page,
                'section' => $name,
            ));
        }else{
            return $view->render('dots/blocks/render', array(
                'blocks'=>$blocks,
                'page'=>$page,
                'section'=>$name,
            ));
        }
    }

}