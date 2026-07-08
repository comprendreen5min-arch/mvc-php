<?php

class LoginTracker extends Model{

    protected $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function loginLogs($table, $data){

        $this->insert($table, $data);

    }
}