<?php

class Users extends Controller
{

    private $mod;
    private $help;
    private $session;

    public function __construct()
    {
        $this->mod = $this->model('User');
        $this->help = new Helper();

        $this->session = new SessionHelper();
        // Only Admins
        $this->session->requireRoles([1], 'errors/error');
    }

    // Index Page
    public function index()
    {
        $userStats = $this->mod->countUsers();

        $token = $this->help->token(40);

        $data = [
            'user_token' => $token,
            'user_stats' => $userStats
        ];

        $this->view('users/index', $data);
    }

    // Add New User
    public function new()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, 513);
            $hashed_password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

            $admin_name = $this->mod->userField('first_name');
            $admin_id = $this->mod->userField('id');

            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => $hashed_password,
                'phone' => trim($_POST['phone']),
                'user_role' => trim($_POST['user_role']),
                'user_status' => trim($_POST['user_status']),
                'user_token' => trim($_POST['user_token']),
                'admin_id' => $admin_id,
                'admin_name' => $admin_name
            ];

            if ($this->mod->insert('users', $data)) {
                Helper::flash('user_message', 'The User ' . $data['first_name'] . ' has been created successfully.', 'alert-success');

                Helper::redirect('users/index');
            }
        } else {
            Helper::redirect_error();
        }
    }

    // Get All Users To Datatable
    public function all_users()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helper::flash('user_message', 'Invalid request', 'alert-danger');
            Helper::redirect('users/index');
        }

        // Get DataTables parameters safely
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
        $orderColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
        $orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc';

        // Define columns that can be ordered (security - whitelist)
        $orderableColumns = ['first_name', 'last_name', 'email', 'phone', 'user_role', 'user_status', 'id'];
        $orderColumn = isset($orderableColumns[$orderColumnIndex]) ? $orderableColumns[$orderColumnIndex] : 'id';

        // Validate order direction
        $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';

        // Columns to select
        $columns = ['id', 'first_name', 'last_name', 'email', 'phone', 'user_role', 'user_status'];

        // Columns to search in
        $searchColumns = ['first_name', 'last_name', 'email'];

        // Get data from model
        $result = $this->mod->getDataTableData(
            'users',
            $columns,
            $searchColumns,
            $orderColumn,
            $orderDir,
            $start,
            $length,
            $searchValue
        );

        // Prepare response
        $response = [
            'draw' => $draw,
            'recordsTotal' => $result['totalRecords'],
            'recordsFiltered' => $result['filteredRecords'],
            'data' => $result['data']
        ];

        header('Content-Type: application/json');
        print json_encode($response);
        exit;
    }



    // Toggle status
    public function user_status($id = null)
    {
        if (REQUEST !== 'POST') {
            Helper::redirect_error();
        }

        if (!$id) {
            Helper::flash('user_message', 'Invalid user ID', 'alert-danger');
            Helper::redirect('users/index');
        }

        if ($this->mod->toggleStatus('users', $id, 'user_status')) {
            Helper::flash('user_message', 'Status updated successfully!', 'alert-success');
        } else {
            Helper::flash('user_message', 'User not found', 'alert-danger');
        }

        Helper::redirect('users/index');
    }

    // Password Page
    public function password($id = null)
    {

        // check the exist of the user
        $userCheck = $this->mod->exists('users', 'id', $id);

        if ($userCheck == true) {

            // get the user data 
            $user_info = $this->mod->findById('users', $id);
            $user_status = $user_info->user_status;

            // get login history raw
            $loginHistory = $this->mod->getLoginHistory($id, 6);
            // login stats
            $loginStats = $this->mod->getLoginStats($id);

            $data = [
                'URLROOT' => URLROOT,
                'first_name' => $user_info->first_name,
                'last_name' => $user_info->last_name,
                'email' => $user_info->email,
                'user_status' => $user_status,
                'user_id' => $user_info->id,
                'login_history' => $loginHistory,
                'login_stats' => $loginStats
            ];

            $this->view('users/password', $data);
        } else {
            Helper::redirect_error();
        }
    }

    // edit user page 

    public function edit($id = null)
    {

        $check_user = $this->mod->exists('users', 'id', $id);
        if (!$check_user) {
            Helper::flash('user_message', 'The user not found', 'alert-danger');
            Helper::redirect('users/index');
        }

        if ($id == 1) {
            Helper::flash('user_message', 'The admin user cannot be edited', 'alert-danger');
            Helper::redirect('users/index');
        }

        // Get user info
        $user_info = $this->mod->table('users')
            ->where('id', $id)
            ->first();
        $data = [
            'URLROOT' => URLROOT,
            'first_name' => $user_info->first_name,
            'last_name' => $user_info->last_name,
            'email' => $user_info->email,
            'phone' => $user_info->phone,
            'user_status' => $user_info->user_status,
            'user_id' => $user_info->id,
            'user_role' => $user_info->user_role,
            'user_token' => $user_info->user_token,
        ];

        $this->view('users/edit', $data);
    }

    public function email_check_on_update()
    {

        if (REQUEST == 'POST') {

            $user_email = trim($_POST['user_email']);
            $user_id = trim($_POST['user_id']);

            $user_info = $this->mod->table('users')->where('id', $user_id)->first();

            if ($user_info->email === $user_email) {
                // Return JSON response
                print json_encode([
                    'exists' => 0,
                    'message' => 'Email available'
                ]);
                exit;
            }

            // Check if email exists
            $email_exists = $this->mod->exists('users', 'email', $user_email);

            // Return JSON response
            print json_encode([
                'exists' => $email_exists,
                'message' => $email_exists ? 'Email already taken' : 'Email available'
            ]);
            exit;
        }
    }


    public function update()
    {

        if (!REQUEST == "POST") {
            Helper::redirect('errors/error');
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);



        $admin_name = $this->mod->userField('first_name');
        $admin_id = $this->mod->userField('id');

        $data = [
            'first_name' => trim($_POST['first_name']),
            'last_name' => trim($_POST['last_name']),
            'email' => trim($_POST['email']),
            'phone' => trim($_POST['phone']),
            'user_role' => trim($_POST['user_role']),
            'user_status' => trim($_POST['user_status']),
            'admin_id' => $admin_id,
            'admin_name' => $admin_name
        ];
        $where = ['id' =>  trim($_POST['id'])];
        if ($this->mod->update('users', $data, $where)) {
            Helper::flash('user_message', 'The User ' . $data['first_name'] . ' has been updated successfully.', 'alert-success');
            Helper::redirect('users/index');
        }
    }


    // Password Check

    public function password_check()
    {

        if (REQUEST == 'POST') {

            $old_password = trim($_POST['oldPassword']);
            $user_id = trim($_POST['userID']);

            // get the user info
            $user_info = $this->mod->findById('users', $user_id);
            $user_password = $user_info->password;

            // Verify The Old Password is Same Password on Database
            $password_check = password_verify($old_password, $user_password);
            if ($password_check == true) {
                print "correct";
            } else {
                print "incorrect";
            }
        }
    }

    // check the email

    public function email_check()
    {
        if (REQUEST == 'POST') {

            $user_email = trim($_POST['user_email']);

            // Check if email exists
            $email_exists = $this->mod->exists('users', 'email', $user_email);

            // Return JSON response
            print json_encode([
                'exists' => $email_exists,
                'message' => $email_exists ? 'Email already taken' : 'Email available'
            ]);
            exit;
        }
    }



    public function password_change()
    {
        // Validate request method
        if (REQUEST !== 'POST') {
            Helper::redirect_error();
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Validate required fields
        if (empty($_POST['user_id']) || empty($_POST['new_password'])) {
            Helper::flash('user_message', 'All fields are required', 'alert-danger');
            Helper::redirect('users/password');
        }

        $user_id = trim($_POST['user_id']);
        $new_password = trim($_POST['new_password']);

        // Validate user exists
        if (!$this->mod->exists('users', 'id', $user_id)) {
            Helper::flash('user_message', 'User not found', 'alert-danger');
            Helper::redirect('users/index');
        }

        // Validate password strength (optional but recommended)
        if (strlen($new_password) < 8) {
            Helper::flash('user_message', 'Password must be at least 8 characters', 'alert-warning');
            Helper::redirect('users/password/' . $user_id);
        }

        // Update password
        $data = ['password' => password_hash($new_password, PASSWORD_BCRYPT)];
        $where = ['id' => $user_id];

        if ($this->mod->update('users', $data, $where)) {
            Helper::flash('user_message', 'Password updated successfully!', 'alert-success');
        } else {
            Helper::flash('user_message', 'Failed to update password', 'alert-danger');
        }

        Helper::redirect('users/password/' . $user_id);
    }


    public function delete($id = null)
    {
        if (REQUEST !== 'POST') {
            Helper::redirect_error();
        }

        if (!$id) {
            Helper::flash('user_message', 'Invalid user ID provided', 'alert-danger');
            Helper::redirect('users/index');
        }

        if ($this->mod->delete('users', $id)) {
            Helper::flash('user_message', 'User deleted successfully!', 'alert-success');
        } else {
            Helper::flash('user_message', 'User not found or already deleted', 'alert-danger');
        }

        Helper::redirect('users/index');
    }


    public function partners()
    {
        $data = [
            'URLROOT' => URLROOT,
        ];

        $this->view('users/partners', $data);
    }

    public function all_partners()
    {
        $data = $this->mod->allPartners();
        $this->view('users/load_all_partners_data', ['data' => json_encode($data)]);
    }

    public function transporters()
    {
        $data = [
            'URLROOT' => URLROOT,
        ];

        $this->view('users/transporters', $data);
    }

    public function all_transporters()
    {
        $data = $this->mod->allTransporters();
        $this->view('users/load_all_transporters_data', ['data' => json_encode($data)]);
    }

    public function drivers()
    {
        $data = [
            'URLROOT' => URLROOT,
        ];

        $this->view('users/drivers', $data);
    }

    public function all_drivers()
    {
        $data = $this->mod->allDrivers();
        $this->view('users/load_all_drivers_data', ['data' => json_encode($data)]);
    }
}
