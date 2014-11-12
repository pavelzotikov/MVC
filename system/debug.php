<?php defined('DOCROOT') OR die('No direct script access.');

class Debug {

    public static function vars($value)
    {
        return "<pre>" . print_r($value, true) . "</pre>";
    }

}