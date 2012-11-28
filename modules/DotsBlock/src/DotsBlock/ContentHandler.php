<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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
     */
    public function __construct($alias = null, $name = null){
        $this->alias = $alias;
        $this->name = $name;
    }


    /**
     * Set alias
     * @param string $alias
     * @return \DotsBlock\ContentHandler
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
     * @param string $name
     * @return \DotsBlock\ContentHandler
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