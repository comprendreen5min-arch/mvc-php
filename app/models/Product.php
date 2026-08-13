<?php
Class Product extends Model{

    
    protected $db;


    public function __construct()
    {
        $this->db = new Database;

    }
    




}