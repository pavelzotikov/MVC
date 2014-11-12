<?php defined('DOCROOT') OR die('No direct script access.');

class Model_User {

    public $id;

    public function __construct($user_id)
    {
        $this->id = $user_id;
    }

}