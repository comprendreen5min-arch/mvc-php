<?php

class Errors extends Controller
{



    public function __construct() {}

    public function error()
    {
        $this->view('errors/error');
    }

    public function _404()
    {
        $this->view('errors/404');
    }

    public function sessions_search_error()
    {
        $this->view('errors/sessions_search_error');
    }
}
