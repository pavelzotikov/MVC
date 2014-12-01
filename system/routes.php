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
                $params[$item['params'][$key]] = $value;
            }

            $action = 'action_' . $item['action'];
            $controller = 'Controller_' . ucfirst($item['controller']);
            break;
        }

    }
}

if ($controller && $action) {

    Request::getInstance()->params = $params;
    include DOCROOT . "classes/" . str_replace('_', '/', $controller) . ".php";
    try {
        $class = new $controller();
        $class->action = $action;
        echo $class->execute();
    } catch (Exception $e) {
        echo sprintf('%s [ %s ]: %s ~ %s [ %d ]',
            get_class($e), $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), $e->getLine());
    }

} else {
    header("HTTP/1.0 404 Not Found");
    echo View::render('errors/404');
}
