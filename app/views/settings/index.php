{{ ComponentFiles::header() }}

<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{Helper::flash_message('settings_message')}}
            {{Helper::flash_message('bank_message')}}
            {{Helper::flash_message('company_message')}}
            {{Helper::flash_message('smtp_message')}}
                                    <div class="card-body">

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-pills nav-justified" role="tablist">
                                            <li class="nav-item waves-effect waves-light" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#app-settings" role="tab" aria-selected="true">
                                                    <span class="d-none d-md-block">App Settings</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#company-info" role="tab" aria-selected="false" tabindex="-1">
                                                    <span class="d-none d-md-block">Company Info</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#bank-info" role="tab" aria-selected="false" tabindex="-1">
                                                    <span class="d-none d-md-block">Bank Info</span><span class="d-block d-md-none"><i class="mdi mdi-email h5"></i></span>
                                                </a>
                                            </li>
                                            <li class="nav-item waves-effect waves-light" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#smtp" role="tab" aria-selected="false" tabindex="-1">
                                                    <span class="d-none d-md-block">SMTP</span><span class="d-block d-md-none"><i class="mdi mdi-cog h5"></i></span>
                                                </a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active p-3" id="app-settings" role="tabpanel">
                                                <form action="{{URLROOT}}/settings/app_settings" method="POST">
                                                <div class="card">
                                                    
                                                    <div class="card-body">

                                                            <!-- <div class="form-group">
                                                                <label for="">App name </label>
                                                                <input type="text" class="form-control" name="app_name" require>

                                                            </div> -->
                                                            <div class="row mb-3">
                                                                    <label for="app-name-input" class="col-sm-2 col-form-label">App Name</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="app_name" id="app-name-input" value="{{$data['app_settings']->app_name}}">
                                                            </div>
                                                            </div>


                                                              <div class="row mb-3">
                                                                    <label for="app-desc-input" class="col-sm-2 col-form-label">Description</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="app_description" id="app-desc-input" value="{{$data['app_settings']->app_description}}">
                                                            </div>
                                                            </div>
                                                            

                                                        


                          
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>

                                               
                                                </div>
                                                

                                                </form>
                                              
                                                
                                           
                                            </div>

                                            <div class="tab-pane p-3" id="company-info" role="tabpanel">
                                            

                                            <form action="{{URLROOT}}/settings/company_info" method="POST">

                                            

                                            <div class="row">
                                                <div class="col-md-6">
                                                            <div class="row mb-3">
                                                                    <label for="cmp_name" class="col-form-label">Company Name</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text" placeholder="" name="company_name" 
                                                                          id="cmp_name" value="{{$data['company_info']->company_name}}">
                                                                    </div>
                                                            </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                            <div class="row mb-3">
                                                                    <label for="cmp_type" class="col-form-label">Company Type</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text"  name="company-type" 
                                                                          id="cmp_type" value="{{$data['company_info']->company_type}}" placeholder="SARL,LLC,LTD...">
                                                                    </div>
                                                            </div>
                                                                                               
                                                </div>
                                            </div> <!-- end row -->


                                            <div class="row">
                                                <div class="col-md-4">
                                                            <div class="row mb-4">
                                                                    <label for="cmp_email" class="col-form-label">Company Email</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="email" placeholder="example@email.com" name="company_email" 
                                                                          id="cmp_email" value="{{$data['company_info']->company_email}}">
                                                                    </div>
                                                            </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                            <div class="row mb-4">
                                                                    <label for="cmp_phone" class="col-form-label">Company Phone</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text"  name="company-phone" 
                                                                          id="cmp_phone" value="{{$data['company_info']->company_phone}}" placeholder="+212 666 777 999">
                                                                    </div>
                                                            </div>
                                                </div>                                                  
                                                
                                                <div class="col-md-4">
                                                            <div class="row mb-4">
                                                                    <label for="cmp_address" class="col-form-label">Company Address</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text"  name="company-address" 
                                                                          id="cmp_address" value="{{$data['company_info']->company_address}}" placeholder="cite massira,Marrakech">
                                                                    </div>
                                                            </div>
                                                </div>     
                                            </div> <!-- end row -->


                                            <div class="row">
                                                <div class="col-md-6">
                                                            <div class="row mb-3">
                                                                    <label for="cmp_city" class="col-form-label">City</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text" placeholder="Marrakech" name="company_city" 
                                                                          id="cmp_city" value="{{$data['company_info']->company_city}}">
                                                                    </div>
                                                            </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                            <div class="row mb-3">
                                                                    <label for="cmp_country" class="col-form-label">Country</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text"  name="company-country" 
                                                                          id="cmp_country" value="{{$data['company_info']->company_country}}" placeholder="Morocco">
                                                                    </div>
                                                            </div>
                                                                                               
                                                </div>
                                            </div> <!-- end row -->


                                            <div class="row">
                                                <div class="col-md-4">
                                                            <div class="row mb-4">
                                                                    <label for="cmp_if" class="col-form-label">Company IF</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text" name="company_if" 
                                                                          id="cmp_if" value="{{$data['company_info']->company_if}}">
                                                                    </div>
                                                            </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                            <div class="row mb-4">
                                                                    <label for="cmp_cnss" class="col-form-label">Company Cnss</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text"  name="company-cnss" 
                                                                          id="cmp_cnss" value="{{$data['company_info']->company_cnss}}">
                                                                    </div>
                                                            </div>
                                                </div>                                                  
                                                
                                                <div class="col-md-4">
                                                            <div class="row mb-4">
                                                                    <label for="cmp_rc" class="col-form-label">Company RC</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text"  name="company-rc" 
                                                                          id="cmp_rc" value="{{$data['company_info']->company_rc}}">
                                                                    </div>
                                                            </div>
                                                </div>     
                                            </div> <!-- end row -->


                                            <div class="row">
                                                <div class="col-md-6">
                                                            <div class="row mb-3">
                                                                    <label for="cmp_patente" class="col-form-label">Company Patente</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text" name="company_patente" 
                                                                          id="cmp_patente" value="{{$data['company_info']->company_patente}}">
                                                                    </div>
                                                            </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                            <div class="row mb-3">
                                                                    <label for="cmp_ice" class="col-form-label">Company ICE</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text"  name="company-ice" 
                                                                          id="cmp_ice" value="{{$data['company_info']->company_ice}}" >
                                                                    </div>
                                                            </div>
                                                                                               
                                                </div>
                                            </div> <!-- end row -->

                                            
                                            <div class="card">
                                                <div class="card-body"></div>
                                                <div class="card-footer d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                                </div>
                                            </div>
                                            </form>
                                            </div>

                                                  
                                            <div class="tab-pane p-3" id="bank-info" role="tabpanel">
                                               <form action="{{URLROOT}}/settings/app_bank" method="POST">
                                                <div class="card">
                                                    
                                                    <div class="card-body">

                                                            <!-- <div class="form-group">
                                                                <label for="">App name </label>
                                                                <input type="text" class="form-control" name="app_name" require>

                                                            </div> -->
                                                            <div class="row mb-3">
                                                                    <label for="app-name-input" class="col-sm-2 col-form-label">Account Holder</label>
                                                                    <div class="col-sm-10">
                                                                          <input class="form-control" type="text" placeholder="" name="account_holder" id="app-name-input" value="{{$data['bank_info']->account_holder}}">
                                                                    </div>
                                                            </div>


                                                              <div class="row mb-3">
                                                                    <label for="bank_name" class="col-sm-2 col-form-label">Bank Name</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="bank_name" id="bank_name" value="{{$data['bank_info']->bank_name}}">
                                                            </div>
                                                            </div>
            

                                                              <div class="row mb-3">
                                                                    <label for="bank_acc" class="col-sm-2 col-form-label">Bank account</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="bank_account" id="bank_acc" maxlength="24" value="{{$data['bank_info']->bank_account}}">
                                                            </div>
                                                            </div>

                                                        
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>

                                               
                                                </div>
                                                

                                                </form>
                                               
                                            </div>
                                            <div class="tab-pane p-3" id="smtp" role="tabpanel">
                                                  
                                                <form action="{{URLROOT}}/settings/smtp" method="POST">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                    <label for="smtp_user" class="col-sm-2 col-form-label">Usename</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="smtp_username" 
                                                                 id="smtp_user" value="{{$data['app_smtp']->smtp_username}}">
                                                            </div>
                                                            </div>


                                                              <div class="row mb-3">
                                                                    <label for="smtp_password" class="col-sm-2 col-form-label">Password</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="password" placeholder="" name="smtp_password" 
                                                                 id="smtp_password" value="">
                                                                 <label for="">Leave it blank to use existing password</label>
                                                            </div>
                                                            </div>


                                                              <div class="row mb-3">
                                                                    <label for="smtp_host" class="col-sm-2 col-form-label">Host</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="smtp_host" 
                                                                 id="smtp_host" value="{{$data['app_smtp']->smtp_host}}">
                                                            </div>
                                                            </div>


                                                              <div class="row mb-3">
                                                                    <label for="smtp_port" class="col-sm-2 col-form-label">Port</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="smtp_port" 
                                                                 id="smtp_port" value="{{$data['app_smtp']->smtp_port}}">
                                                            </div>
                                                            </div>
                                                            

                                                              <div class="row mb-3">
                                                                    <label for="smtp_encryption" class="col-sm-2 col-form-label">Encryption</label>
                                                            <div class="col-sm-10">
                                                                 <select class="form-control" type="text" placeholder="" name="smtp_encryption" 
                                                                    id="smtp_encryption" value="{{$data['app_smtp']->smtp_encryption}}">
                                                                    <option value="ssl">SSL</option>
                                                                    <option value="tls">TLS</option>
                                                                    


                                                                 </select>
                                                            </div>
                                                            </div>                                                          

                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="card-footer d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </form>
                                            
                                                
                                            </div>
                                        </div>

                                    </div>
                                </div>
      



    


    </div><!--/col-->
</div><!--/row-->

{{ ComponentFiles::footer() }}

