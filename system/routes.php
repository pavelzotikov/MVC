<?php defined('DOCROOT') OR die('No direct script access.');

// Defines the pattern of a <segment>
$REGEX_KEY = '<([a-zA-Z0-9_]++)>';

$requestURL = preg_replace('/^([^?]+)(\?.*?)?(#.*)?$/', '$1$3', $_SERVER['REQUEST_URI']);

$requestURI = explode('/', $requestURL);
$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

include DOCROOT . 'classes/routes.php';

for ($i = 0; $i <= sizeof($scriptName); $i++) if (empty($requestURI[$i])) unset($requestURI[$i]);

$routeUrl = implode('/', $requestURI);

$params = array();
$action = $controller = '';

foreach ($routes as $route => $item) {
    if ($routeUrl === $route) {

        $action = 'action_' . $item['action'];
        $controller = 'Controller_' . ucfirst($item['controller']);
        break;

    } elseif (strpos($route, '{int}') !== FALSE) {

        $pattern = str_replace('{int}', '(\d+)', $route);

        if (preg_match("#^$pattern$#", $routeUrl, $matches)) {
            array_shift($matches);

            foreach ($matches as $key => $value) {
                Request::$params[$item['params'][$key]] = $value;
            }

            $action = 'action_' . $item['action'];
            $controller = 'Controller_' . ucfirst($item['controller']);
            break;
        }

    }
}

if ($controller && $action) {

    include DOCROOT . "classes/" . str_replace('_', '/', $controller) . ".php";
    $class = new $controller();
    if ($params) $class::$params = $params;
    echo $class->$action();

} else echo View::render('errors/404');
