<?php defined('DOCROOT') OR die('No direct script access.');

require DOCROOT . 'system/autoload.php';
require_once DOCROOT . 'system/debug.php';
require_once DOCROOT . 'system/view.php';

$requestURL = preg_replace('/^([^?]+)(\?.*?)?(#.*)?$/', '$1$3', $_SERVER['REQUEST_URI']);

$requestURI = explode('/', $requestURL);
$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

for ($i = 0; $i <= sizeof($scriptName); $i++) if (empty($requestURI[$i])) unset($requestURI[$i]);

$routes = include DOCROOT . 'classes/routes.php';

$requestURI = preg_replace('/^([^?]+)(\?.*?)?(#.*)?$/', '$1$3', $requestURI);

$routeUrl = implode('/', $requestURI);
if (isset($routes[$routeUrl])) {

    $action = 'action_' . $routes[$routeUrl]['action'];
    $controller = 'Controller_' . ucfirst($routes[$routeUrl]['controller']);
    include DOCROOT . "classes/" . str_replace('_', '/', $controller) . ".php";
    $class = new $controller();
    echo $class->$action();

} else echo View::render('errors/404');


