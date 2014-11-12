<?php

class View
{

    public static function render($filename, array $date = NULL)
    {
        ob_start();

        extract($date, EXTR_SKIP);
        include DOCROOT . 'classes/views/' . $filename . '.phtml';

        return ob_get_clean();
    }

}