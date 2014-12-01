<?php defined('DOCROOT') OR die('No direct script access.');

class View
{

    protected static $_global_data;

    public static function render($filename, $data = NULL)
    {
        ob_start();

        $params = array();
        $params['view'] = $data;
        $params['global'] = View::$_global_data;

        if (!empty($params)) extract($params, EXTR_SKIP);

        include DOCROOT . "classes/views/".$filename.".phtml";

        return ob_get_clean();
    }

    public static function set_global($key, $value = NULL)
    {
        if (!View::$_global_data) View::$_global_data = json_decode('{}');
        View::$_global_data->$key = $value;
    }

}