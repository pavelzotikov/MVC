<?php defined('DOCROOT') OR die('No direct script access.');

class Controller_Index extends Controller_Base_Index
{

    public function action_index()
    {
        if (!empty(Request::$params['id'])) {
            echo Debug::vars(Request::$params);exit();
        }
        $this->template->content = View::render('templates/body');
    }

    public function action_test()
    {
        /** @var Redis $redis */
        // $redis = Methods_Redis::instance();

        /** @var Memcache $memcache */
        // $memcache = Methods_Memcache::instance();

        /** @var Methods_Lemon $lemon */
        // $lemon = Methods_Lemon::instance();

        $this->template->content = View::render('templates/body', $this->view);
    }

}