<?php defined('DOCROOT') OR die('No direct script access.');

class Arr
{

    public static function get($array, $key, $default = NULL)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

}