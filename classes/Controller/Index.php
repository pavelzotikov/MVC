<?php defined('DOCROOT') OR die('No direct script access.');

class Controller_Index extends Controller_Base_Index {

    public function action_index()
    {
        /** @var Redis $redis */
        $redis = Methods_Redis::instance();

        /** @var _Memcache $memcache */
        $memcache = Methods_Memcache::instance();

        /** @var Methods_Lemon $memcache */
        $lemon = Methods_Lemon::instance();

        $this->template->content = View::render('templates/body');
    }

}