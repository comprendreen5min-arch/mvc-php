<?php

class Pages extends Controller
{
    private $mod;
    private $help;
    private $session;


    public function __construct()
    {
        $this->mod = $this->model('Page');
        $this->help = new Helper();
        $this->session = new SessionHelper();
        // Only Admins & Manager
        $this->session->requireRoles([1, 2], 'errors/error');
    }

    public function index()
    {

        $data = [
            "test" => "nouaman",

        ];

        $this->view('pages/index', $data);
    }



    public function sessions_search_error()
    {
        $this->view("pages/sessions_search_error");
    }
}
