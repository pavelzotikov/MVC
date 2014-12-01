<?php

class Task_Base {

    protected function write($text)
    {
        fwrite(STDOUT, $text.PHP_EOL);
    }

    protected function read($text)
    {
        fwrite(STDOUT, $text.PHP_EOL);
        return trim(fgets(STDIN));
    }

}