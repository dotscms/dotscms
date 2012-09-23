<?php
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