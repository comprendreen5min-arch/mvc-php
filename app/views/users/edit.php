{{ComponentFiles::header()}}
<div class="row">
    <div class="col-md-12">
        {{ Helper::flash_message('user_message') }}

        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h4 class="card-title">{{$first_name}} {{$last_name}}</h4>
                <a href="{{URLROOT}}/users" class="btn btn-primary">Users</a>
            </div>
            <form action="{{URLROOT}}/users/update/{{$user_id}}" method="POST" class="update-label">

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" name="id" id="user_id_on_update" value="{{$user_id}}">
                            <div class="form-group">
                                <label for=""> First Name</label> <span class="text-danger">*</span>
                                <input type="text" name="first_name" class="form-control" value="{{$first_name}}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="">Last Name </label> <span class="text-danger">*</span>
                                <input type="text" name="last_name" class="form-control" value="{{$last_name}}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label> <span class="text-danger">*</span> <span
                                    id="user-email-on-update-message" class="text-danger"></span>
                                <input type="email" name="email" id="user-email-on-update" class="form-control" value="{{$email}}"
                                    required>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Phone</label> <span class="text-danger">*</span>
                                <input type="text" name="phone" class="form-control" value="{{$phone}}" required>

                            </div>

                            <div class="form-group">
                                <label for="">Role</label> <span class="text-danger">*</span>
                                <select name="user_role" class="form-control" required>
                                    <option value="1" {{Helper::is_selected($user_role, 1)}}>Admin</option>
                                    <option value="2" {{Helper::is_selected($user_role, 2)}}>Manager</option>
                                    <option value="3" {{Helper::is_selected($user_role, 3)}}>Partner</option>
                                    <option value="4" {{Helper::is_selected($user_role, 4)}}>Transporter</option>
                                    <option value="5" {{Helper::is_selected($user_role, 5)}}>Driver</option>
                                    <option value="6" {{Helper::is_selected($user_role, 6)}}>Pilot</option>
                                    <option value="7" {{Helper::is_selected($user_role, 7)}}>Accounter</option>
                                    <option value="8" {{Helper::is_selected($user_role, 8)}}>Greeter</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="">Status</label> <span class="text-danger">*</span>
                                <select name="user_status" class="form-control" id="" required>
                                    <option value="1" {{Helper::is_selected($user_status, 1)}}>Active</option>
                                    <option value="0" {{Helper::is_selected($user_status, 0)}}>Inactive</option>
                                </select>
                            </div>


                        </div>
                    </div>

                </div>
                <div class="card-footer bg-secondary d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-success" id="save-user-btn">Save</button>
                </div>

            </form>
        </div>

    </div>
    <!--/card -->
</div>
<!--/row-->



{{ComponentFiles::footer()}}