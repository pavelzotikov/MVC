<?php defined('DOCROOT') OR die('No direct script access.');

class Controller_Index extends Controller_Base_Index
{

    /*
     * JSON:
     * $this->auto_render = false;
     * $this->response->headers('Content-Type', 'application/json; charset=utf-8');
     * $this->response->body = @json_encode(array('content' => View::render('templates/test', $this->view)));
     */

    /*
     * Exception:
     * throw new Exception(message);
     */

    public function action_index()
    {
        $this->view->id = $this->request->param('id');
        $this->template->content = View::render('templates/body', $this->view);

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