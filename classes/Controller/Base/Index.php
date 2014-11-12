<?php defined('DOCROOT') OR die('No direct script access.');

class Controller_Base_Index {

    public $view;
    public $template;

    public function __construct()
    {
        $this->view = $this->template = json_decode('{}');
        $this->template->file = 'index';
        $this->template->content = '';
    }

    public function __destruct()
    {
        echo View::render($this->template->file, array('content' => $this->template->content));
    }

}