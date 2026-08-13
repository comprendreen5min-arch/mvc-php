<?php
Class Products extends Controller{
       

    private $mod;
    private $help;
    private $session;
    private $adminId;
    private $adminName;


    public function __construct()
    {
        $this->mod = $this->model('Product');
        $this->help = new Helper();
        $this->session = new SessionHelper();
        //Only admin
        $this->session->requireRoles([1],'errors/error');
        $this->adminId = $this->session->getUserId();
        $this->adminName = $this->session->getUserName();   

    }


    public function index(){

        $this->view('products/index');


    }

    public function new(){

        $this->view('products/new');

    }


   











}