<?php
namespace DotsBlock;

/**
 * Content Handler
 * @author Cosmin Harangus<cosmin@around25.com>
 */
class ContentHandler
{
    protected $alias = null;
    protected $name = null;

    /**
     * Content Handler constructor
     * @param null $alias
     * @param null $name
     * @param null $renderCallback
     * @param null $editCallback
     */
    public function __construct($alias = null, $name = null){
        $this->alias = $alias;
        $this->name = $name;
    }


    /**
     * Set alias
     * @param $alias
     */
    public function setAlias($alias){
        $this->alias = $alias;
        return $this;
    }
    /**
     * Get alias
     * @return string
     */
    public function getAlias(){
        return $this->alias;
    }


    /**
     * Set name
     * @param $name
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    /**
     * Get name
     * @return string
     */
    public function getName(){
        return $this->name;
    }

}