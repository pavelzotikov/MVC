<?php

class Debug
{

    static function vars($value)
    {
        return "<pre>" . print_r($value, true) . "</pre>";
    }

}