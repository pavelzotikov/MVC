<?php

spl_autoload_register('autoload');

function autoload($className) {
    include DOCROOT . 'classes/' . str_replace('_', '/', $className) . '.php';
}