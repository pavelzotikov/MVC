<?php defined('DOCROOT') or die('No direct script access.');

class Methods_Memcache extends Memcache
{

    public static function instance()
    {
        return Methods_Instance::getInstance()->get('memcache');
    }

    public function __construct()
    {
        $this->connect('127.0.0.1', 11211);
    }

}