<?php

define('EXT', '.php');

error_reporting(E_ALL | E_STRICT);

define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

require_once DOCROOT . 'classes/bootstrap.php';
require_once DOCROOT . 'system/core.php';

