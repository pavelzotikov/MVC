<?php defined('DOCROOT') OR die('No direct script access.');

class Controller
{

    public $action;

    public $view;
    public $template;

    public $auto_render = true;

    protected $request;
    protected $response;

    public function execute()
    {
        $this->before();

        $this->{$this->action}();

        $this->after();

        return $this->response->body;
    }

    protected function before()
    {
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();

        $this->view = json_decode('{}');

        $this->template = json_decode('{}');
        $this->template->file = 'index';
        $this->template->content = '';
    }

    protected function after()
    {
        if ($this->auto_render) {
            View::set_global('content', $this->template->content);
            $this->response->body = View::render($this->template->file, $this->view);
            foreach ($this->response->_headers as $key => $value) header("$key: $value");
        }
    }

}