{{ ComponentFiles::header() }}

<div class="row">
    <div class="col-md-12">
        <div class="card">
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
                                                                 <input class="form-control" type="text" placeholder="" name="app_name" id="app-name-input">
                                                            </div>
                                                            </div>


                                                              <div class="row mb-3">
                                                                    <label for="app-desc-input" class="col-sm-2 col-form-label">Description</label>
                                                            <div class="col-sm-10">
                                                                 <input class="form-control" type="text" placeholder="" name="app_description" id="app-desc-input">
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
                                              
                                            </div>
                                            <div class="tab-pane p-3" id="bank-info" role="tabpanel">
                                               
                                            </div>
                                            <div class="tab-pane p-3" id="smtp" role="tabpanel">
                                               
                                            </div>
                                        </div>

                                    </div>
                                </div>
      



    


    </div><!--/col-->
</div><!--/row-->

{{ ComponentFiles::footer() }}

