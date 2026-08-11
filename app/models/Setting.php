<?php
class Setting extends Model{

    protected $db;

    public function __construct()
    {
        $this->db = new Database;

    }
    
    public function getAppSettings(){
        $appSettings = $this->table('app_settings')
                        ->where('id',1)
                        ->first();

    return $appSettings;

    }

    public function updateAppSettings($data){

         $appSettings = $this->table('app_settings')
                         ->where('id',1)
                         ->update([
                            'app_name' => $data['app_name'],
                            'app_description' => $data['app_description'],
                            'admin_id' => $data['admin_id'],
                            'admin_name' => $data['admin_name']

                         ])
                         ->save();
        
        return $appSettings;

    }


    public function getBankInfo(){
        $bank = $this->table('bank_info')
                      ->where('id',1)
                      ->first();
    
        return $bank;



    }


    public function updateBankInfo($data){

         $bank_info = $this->table('bank_info')
                         ->where('id',1)
                         ->update([
                            'account_holder' => $data['account_holder'],
                            'bank_name' => $data['bank_name'],
                            'bank_account' => $data['bank_account'],
                            'admin_id' => $data['admin_id'],
                            'admin_name' => $data['admin_name']

                         ])
                         ->save();
        
        return $bank_info;

    }


    public function updateCompanyInfo($data){

         $company_info = $this->table('company_info')
                         ->where('id',1)
                         ->update([
                            'company_name' => $_data['company_name'],
                            'company_type' => $_data['company_type'],
                            'company_address' => $_data['company_adress'],
                            'company_email' => $_data['company_email'], 
                            'company_phone' => $_data['company_phone'], 
                            'company_city' => $_data['company_city'], 
                            'company_country' => $_data['company_country'],
                            'company_if' => $_data['company_if'],
                            'company_cnss' => $_data['company_cnss'], 
                            'company_patente' => $_data['company_patente'],
                            'company_rc' => $_data['company_rc'], 
                            'company_ice' => $_data['company_ice'],
                            'admin_id' => $_data['admin_id'], 
                            'admin_name' => $_data['admin_name']


                         ])
                         ->save();
        
        return $company_info;


    }

    public function getCompanyInfo(){
        $company = $this->table('company_info')
                      ->where('id',1)
                      ->first();
    
        return $company;



    }









}