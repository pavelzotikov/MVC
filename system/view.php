<?php defined('DOCROOT') OR die('No direct script access.');

class View
{

    public static function render($filename, $date = NULL)
    {
        ob_start();

        if (is_object($date)) $date = array('view' => $date);

        if ($date !== NULL) extract($date, EXTR_SKIP);
        include DOCROOT . "classes/views/".$filename.".phtml";

        return ob_get_clean();
    }

}