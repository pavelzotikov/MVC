<?php defined('DOCROOT') OR die('No direct script access.');

spl_autoload_register('autoload');

function autoload($className) {
    include DOCROOT . 'classes/' . str_replace('_', '/', $className) . '.php';
}