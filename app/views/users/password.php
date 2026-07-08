{{ComponentFiles::header()}}
<div class="row">
    <div class="col-md-12">
        {{ Helper::flash_message('user_message') }}

        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h4 class="card-title">{{$first_name}} {{$last_name}}</h4>
                <a href="{{URLROOT}}/users" class="btn btn-primary">Users</a>
            </div>
            <div class="card-body">
                <div class="row d-flex align-items-center">

                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item">{{$first_name}}</li>
                            <li class="list-group-item">{{$last_name}}</li>
                            <li class="list-group-item">{{$email}}</li>
                            <li class="list-group-item">
                                @if($user_status == 1)
                                <i class="btn btn-sm btn-primary fa fa-check"></i>
                                @else
                                <i class="btn btn-sm btn-warning fa fa-pause"></i>
                                @endif
                            </li>

                        </ul>
                    </div>

                    <div class="col-md-6">
                        <form action="{{URLROOT}}/users/password_change" method="POST" class="update-label">
                            <div class="form-group">
                                <label for="">OLD Password</label> <span class="text-danger">*</span> <span
                                    class="old-password-feedback text-danger"></span>
                                <input type="password" name="old_password" placeholder="**********"
                                    class="form-control old-password" id="" autocomplete="off" required>
                                <input type="hidden" name="user_id" class="user-password-id" value="{{$user_id}}">
                            </div>
                            <div class="form-group">
                                <label for="">New Password</label> <span class="text-danger">*</span>
                                <input type="password" name="new_password" placeholder="**********"
                                    class="form-control new-password" id="" autocomplete="off" disabled required>
                            </div>

                            <div class="form-group">
                                <label for="">Confirm Password</label> <span class="text-danger">*</span>
                                <input type="password" name="confirm_password" placeholder="**********"
                                    class="form-control confirm-password" id="" autocomplete="off" disabled required>
                            </div>
                            <span id="" class="text-danger mt-3 confirm-password-feedback"></span>
                            <div class="form-group mt-3">
                                <button type="submit"
                                    class="btn btn-sm btn-success form-control update-password-btn text-dark"
                                    disabled>Update</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>

    </div>
    <!--/card -->
</div>
<!--/col-->
</div>
<!--/row-->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h4 class="card-title">Logs</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Login Time</th>
                                    <th>IP Address</th>
                                    <th>Browser</th>
                                    <th>Operating System</th>
                                    <th>Device</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($login_history as $log)
                                <tr>
                                    <td>{{ $log->login_time }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>{{ $log->browser }}</td>
                                    <td>{{ $log->operating_system }}</td>
                                    <td>{{ $log->device_type }}</td>
                                    <td>{{ $log->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="col-md-4">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary">Total Login</span></td>
                                    <td>{{$login_stats['total_logins']}}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">Total Failed</span></td>
                                    <td>{{$login_stats['failed_logins']}}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark">Last Login</span></td>
                                    <td>{{$login_stats['last_login']->login_time}}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Last Browser</span></td>
                                    <td>{{$login_stats['last_login']->browser}}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-danger">Last Device</span></td>
                                    <td>{{$login_stats['last_login']->device_type}}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-purple">OS</span></td>
                                    <td>{{$login_stats['last_login']->operating_system}}</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-peach">Last IP Address</span></td>
                                    <td><a href="https://whatismyipaddress.com/ip/{{$login_stats['last_login']->ip_address}}"
                                            target="_blank">{{$login_stats['last_login']->ip_address}}</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/card -->
    </div>
    <!--/col-->
</div>
<!--/row-->



{{ComponentFiles::footer()}}