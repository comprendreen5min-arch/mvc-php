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
        $appSettings = $this->mod->getAppSettings();
        $bankInfo = $this->mod->getBankInfo();
        $companyInfo = $this->mod->getCompanyInfo();
 

        $data = [
            "URLROOT" => URLROOT,
            "app_settings" => $appSettings,
            'bank_info' => $bankInfo,
            'company_info' => $companyInfo

        ];

        $this->view('settings/index', $data);


    }


    public function app_settings(){

        if(REQUEST !== 'POST'){
            Helper::flash('settings_message' , 'Invalid Request' , 'alert-danger');
            Helper::redirect('settings/index');

         }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        $data = [
            'app_name' => trim($_POST['app_name']),
            'app_description' => trim($_POST['app_description']),
            'admin_id' => $this->adminId,
            'admin_name' => $this->adminName

        ];

        if($this->mod->updateAppSettings($data)){
            Helper::flash('settings_message', 'Settings updated successfully','alert-success');
            Helper::redirect('settings/index');            

        }else{
            Helper::flash('settings_message', 'Settings updated failed','alert-danger');
            Helper::redirect('settings/index');            
        }
         

    
    }


    public function app_bank(){

        if(REQUEST !== 'POST'){
            Helper::flash('settings_message' , 'Invalid Request' , 'alert-danger');
            Helper::redirect('settings/index');

        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = [
            'account_holder' => trim($_POST['account_holder']),
            'bank_name' => trim($_POST['bank_name']),
            'bank_account' => trim($_POST['bank_account']),
            'admin_id' => $this->adminId,
            'admin_name' => $this->$adminName

        
        ];

        if($this->mod->updateBankInfo($data)){
            Helper::flash('bank_message', 'Bank Info updated successfully','alert-success');
            Helper::redirect('settings/index');            

        }else{
            Helper::flash('bank_message', 'Bank Info updated failed','alert-danger');
            Helper::redirect('settings/index');            
        }

          
  }


  public function company_info(){
        if(REQUEST !== 'POST'){
            Helper::flash('settings_message' , 'Invalid Request' , 'alert-danger');
            Helper::redirect('settings/index');

         }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = [
            'company_name' => trim($_POST['company_name']),
            'company_type' => trim($_POST['company_type']),
            'company_address' => trim($_POST['company_adress']),
            'company_email' => trim($_POST['company_email']), 
            'company_phone' => trim($_POST['company_phone']), 
            'company_city' => trim($_POST['company_city']), 
            'company_country' => trim($_POST['company_country']),
            'company_if' => trim($_POST['company_if']),
            'company_cnss' => trim($_POST['company_cnss']), 
            'company_patente' => trim($_POST['company_patente']),
            'company_rc' => trim($_POST['company_rc']), 
            'company_ice' => trim($_POST['company_ice']),
            'admin_id' => $this->admin_id,
            'admin_name' => $this->adminName

        ];

        if($this->mod->updateCompanyInfo($data)){
            Helper::flash('company_message', 'company Info updated successfully','alert-success');
            Helper::redirect('settings/index');            

        }else{
            Helper::flash('company_message', 'company Info updated failed','alert-danger');
            Helper::redirect('settings/index');            
        }


        

      

  }





}

