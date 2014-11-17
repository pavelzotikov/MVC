<?php defined('DOCROOT') OR die('No direct script access.');

spl_autoload_register('autoload');

function autoload($className)
{
    $className = str_replace('_', '/', $className);
    $user_class_file = DOCROOT . "classes/$className.php";
    $system_class_file = DOCROOT . "system/classes/$className.php";
    include(is_file($user_class_file) ? $user_class_file : $system_class_file);
}