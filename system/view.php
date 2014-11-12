<?php defined('DOCROOT') OR die('No direct script access.');

class View {

    public static function render($filename, array $date = NULL)
    {
        ob_start();

        if ($date !== NULL) extract($date, EXTR_SKIP);
        include DOCROOT . 'classes/views/' . $filename . '.phtml';

        return ob_get_clean();
    }

}