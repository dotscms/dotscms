<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots;

class Registry
{
    protected static $data = array();

    public static function get($name)
    {
        if (!isset(static::$data[$name])){
            return null;
        }
        return static::$data[$name];
    }

    public static function set($name, $value)
    {
        static::$data[$name] = $value;
    }

}