<?php
namespace ZeDebug\View\Helper;

use Zend\View\Helper\AbstractHelper,
    ZeDebug\Widget\Backtrace;


class Debug extends AbstractHelper
{
    protected $_widgets = array();

    public function __invoke()
    {
        $this->_widgets = array();
        $this->_widgets['backtrace'] = new Backtrace();
        //return $this->_render();
    }

    protected function _render()
    {
        ob_start();
        include __DIR__ . '/../../Widget/views/wrap.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function getWidgets()
    {
        return $this->_widgets;
    }

    /**
     * @todo Implement a way to log any backtrace into the developer console
     *       using the debug_backtrace or any similar function
     * @todo Display the matched route and the full request object.
     * @todo Hook into all events and display a list of triggered ones with
     *       a full backtrace and a list of parameters
     */
}