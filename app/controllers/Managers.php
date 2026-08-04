<?php
class Managers extends Controller{

private $mod;
private $session;

public function __construct()
{
    $this->mod = $this->model('Manager');
    $this->session = new SessionHelper();
    $this->session->requireRoles([1,2]);

}

public function index(){

    $this->view('managers/index');






}






}