<?php
namespace Dots\View;

/**
 *
 */
class TemplateContainer
{
    /**
     * @var array
     */
    protected $templates = array();

    /**
     * @param array $options
     */
    public function __construct($options=array())
    {
        if (array_key_exists('templates', $options)){
            $this->templates = $options['templates'];
        }
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param $templates
     * @return TemplateContainer
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;
        return $this;
    }

    /**
     * @param $alias
     * @return mixed
     */
    public function getTemplate($alias)
    {
        return $this->templates[$alias];
    }

    /**
     * @param $alias
     * @param $value
     * @return TemplateContainer
     */
    public function setTemplate($alias, $value)
    {
        $this->templates[$alias] = $value;
        return $this;
    }

    /**
     * Return the templates as a multiOptions array
     * @return array
     */
    public function toArray()
    {
        $templates = array();
        foreach ($this->templates as $tpl){
            $templates[$tpl['path']] = $tpl['name'];
        }
        return $templates;
    }

}