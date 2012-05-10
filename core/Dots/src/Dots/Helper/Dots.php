<?php
/**
 * @namespace
 */
namespace Dots\Helper;
use Dots\Module,
    Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\View\Helper\HelperInterface,
    Zend\View\Renderer\RendererInterface as Renderer;

/**
 * @uses Dots\Module
 */
class Dots extends AbstractPlugin implements HelperInterface
{
    protected $view = null;

    /**
     * Render the administrator navigation bar
     * @return string
     */
    public function adminNav()
    {
        // do not render anything if not logged in
        if ( !$this->view->plugin("auth")->isLoggedIn() ){
            return '';
        }
        // add the stylesheet and render the admin navigation bar
        $this->view->plugin('headScript')->appendFile('/assets/default/js/jquery.form.js');
        $this->view->plugin('headScript')->appendFile('/assets/default/js/jquery-ui.min.js');
        $this->view->plugin('headScript')->appendFile('/assets/default/js/jquery.json.js');
        $this->view->plugin('headScript')->appendFile('/assets/dots/js/admin.js');
        $this->view->plugin('headScript')->appendFile('/assets/dots/js/admin.blocks.js');
        $blockManager = Module::blockManager();
        $blockManager->events()->trigger('initHeaders', null,  array(
            'view' => $this->view
        ));

        $this->view->plugin('headScript')->appendScript(<<<END
    $(function(){Dots.Blocks.init();});
END
        );
        return $this->view->render('dots/admin/nav');
    }

    /**
     * Set the View object
     * @param  \Zend\View\Renderer\RendererInterface $view
     * @return \Zend\View\Helper\HelperInterface
     */
    public function setView(Renderer $view)
    {
        $this->view = $view;
    }

    /**
     * Get the View object
     * @return \Zend\View\Renderer\RendererInterface
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Return the current object at invoke
     * @return Dots
     */
    public function __invoke()
    {
        return $this;
    }

}