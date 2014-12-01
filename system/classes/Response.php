<?php defined('DOCROOT') OR die('No direct script access.');

class Response
{

    public $body = '';
    public $_headers = array();

    protected static $_instance;

    public static function getInstance()
    {
        if (null === self::$_instance) self::$_instance = new self();
        return self::$_instance;
    }

    public function headers($key, $value)
    {
        $this->_headers[$key] = $value;
    }

    public function redirect($uri = '')
    {
        $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', trim($uri, '/'));
        $this->headers('Location', "http://$domain/$path");
    }

}