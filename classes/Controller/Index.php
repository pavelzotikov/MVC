<?php

class Controller_Index extends Controller_Base_Index
{

    public function action_index()
    {
        $this->template->content = View::render('templates/body');
    }

    public function action_test()
    {
        var_dump(123);exit();
    }

}