<?php defined('DOCROOT') OR die('No direct script access.');

class Request
{

    public $params;

    protected static $_instance;

    public static function getInstance()
    {
        if (null === self::$_instance) self::$_instance = new self();
        return self::$_instance;
    }

    public function param($key, $default = '')
    {
        return Arr::get((array)$this->params, $key, $default);
    }

}