<?php
namespace ZeDebug\Widget;
use ZeDebug\Widget;

class Backtrace implements Widget
{
    protected $_backtrace = array();

    public function __construct()
    {
        $this->_backtrace = debug_backtrace();
        unset($this->_backtrace[0]);
        unset($this->_backtrace[1]);
        $this->_backtrace = array_reverse($this->_backtrace);
    }

    public function getName()
    {
        return 'Backtrace';
    }

    public function render()
    {
        $content = '';
        foreach($this->_backtrace as $trace){
            $content .=$this->_renderBacktrace($trace);
        }
        return $content;
    }

    protected function _renderBacktrace($trace){
        ob_start();
        include __DIR__ . '/views/backtrace.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

}