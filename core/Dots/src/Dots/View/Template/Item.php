<?php
namespace Dots\View\Template;

/**
 * Template item class
 */
class Item
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
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $path
     * @return Item
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