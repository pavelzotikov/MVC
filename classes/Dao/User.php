<?php defined('DOCROOT') or die('No direct script access.');

class Dao_User extends Dao_Base_Mongo {

    protected $cache_key = 'Dao_User';
    protected $db = 'users';

}