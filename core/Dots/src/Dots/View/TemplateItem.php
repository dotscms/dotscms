<?php
namespace Dots\View;

/**
 * Template item class
 */
class TemplateItem
{
    /**
     * @var string
     */
    protected $name = "";
    /**
     * @var string
     */
    protected $path = "";

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return TemplateItem
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $path
     * @return TemplateItem
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

}