<?php defined('DOCROOT') or die('No direct script access.');

class Methods_Redis extends Redis {

    public static function instance()
    {
        return Methods_Instance::getInstance()->get('redis');
    }

    public function __construct()
    {
        $this->pconnect('127.0.0.1', 6379);
        $this->auth('21gJs32hv3ks');
        $this->select(0);
    }

}