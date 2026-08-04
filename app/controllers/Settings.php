<?php
class Settings extends Controller{

    private $mod;
    private $help;
    private $session;
    private $adminId;
    private $adminName;

    public function __construct()
    
    {
        $this->mod = $this->model('Setting');
        $this->help = new Helper();

        $this->session = new SessionHelper();
        //Only admin can see this 
        $this->session->requireRoles([1],'errors/error');
        $this->adminId = $this->session->getUserId();
        $this->adminName = $this->session->getUserName();

    }

    
    public function index()
    {
        $this->view('settings/index');


    }












}