{{ComponentFiles::header()}}

<div class="row">
    {{ Helper::flash_message('user_message') }}

</div>

<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h4 class="card-title">Users</h4>
                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#add-new-user"><i
                        class="fa fa-plus"></i></button>
            </div>
            <div class="card-body">

                <table id="all-users" class="table table-bordered dt-responsive nowrap datatable-style">
                    <thead class="table-primary">
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email </th>
                            <th>Phone </th>
                            <th>Role</th>
                            <th>Status</th>
                            <th><i class="fa fa-cogs"></i></th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
        <!--/card -->
    </div>
    <!--/col-->


</div>
<!--/row-->



<div class="modal fade" id="add-new-user" aria-hidden="true">
    <form action="<?php print URLROOT; ?>/users/new" method="POST" class="add-label">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> First Name</label> <span class="text-danger">*</span>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Last Name </label> <span class="text-danger">*</span>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label> <span class="text-danger">*</span> <span
                                    id="user-email-check-message" class="text-danger"></span>
                                <input type="email" name="email" id="user-email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Phone</label> <span class="text-danger">*</span> <span
                                    id="user-phone-check-message" class="text-danger"></span>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                    <input type="number" name="phone" id="user-phone" class="form-control" placeholder="+212669611393" required>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Password</label> <span class="text-danger">*</span>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">Role</label> <span class="text-danger">*</span>
                                <select name="user_role" class="form-control" required>
                                    <option value="1">Admin</option>
                                    <option value="2">Manager</option>
                                    <option value="3">Partner</option>
                                    <option value="4">Transporter</option>
                                    <option value="5">Driver</option>
                                    <option value="6">User</option>
                                    <option value="7">Accounter</option>
                                    <option value="8">Greeter</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label> <span class="text-danger">*</span>
                                <select name="user_status" class="form-control" id="" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Token</label> <span class="text-danger">*</span>
                                <input type="text" name="" class="form-control"
                                    value="<?php print $data['user_token']; ?>" disabled>
                                <input type="hidden" name="user_token" class="form-control"
                                    value="<?php print $data['user_token']; ?>">
                            </div>

                        </div>
                    </div>


                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="add-user-btn">Add</button>
                </div>
            </div>
        </div>
    </form>

</div>



{{ComponentFiles::footer()}}