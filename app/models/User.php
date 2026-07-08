<?php
class User extends Model
{

    protected $db;

    public function __construct()
    {
        $this->db = new Database;
    }



    // return all partners
    public function allPartners()
    {
        $columns = [
            ['db' => 'first_name', 'dt' => 0],
            ['db' => 'last_name', 'dt' => 1],
            ['db' => 'email', 'dt' => 2],
            ['db' => 'phone', 'dt' => 3],
            ['db' => 'user_role', 'dt' => 4],
            ['db' => 'user_status', 'dt' => 5],
            ['db' => 'id', 'dt' => 6]
        ];


        return $this->dataTable('users', $columns, 'user_role = 3');
    }


    // return all transporters
    public function allTransporters()
    {
        $columns = [
            ['db' => 'first_name', 'dt' => 0],
            ['db' => 'last_name', 'dt' => 1],
            ['db' => 'email', 'dt' => 2],
            ['db' => 'phone', 'dt' => 3],
            ['db' => 'user_role', 'dt' => 4],
            ['db' => 'user_status', 'dt' => 5],
            ['db' => 'id', 'dt' => 6]
        ];


        return $this->dataTable('users', $columns, 'user_role = 4');
    }

    // return all drivers
    public function allDrivers()
    {
        $columns = [
            ['db' => 'first_name', 'dt' => 0],
            ['db' => 'last_name', 'dt' => 1],
            ['db' => 'email', 'dt' => 2],
            ['db' => 'phone', 'dt' => 3],
            ['db' => 'user_role', 'dt' => 4],
            ['db' => 'user_status', 'dt' => 5],
            ['db' => 'id', 'dt' => 6]
        ];


        return $this->dataTable('users', $columns, 'user_role = 5');
    }

    // count the users
    public function countUsers()
    {
        // count the users
        $user_count = $this->count('users');

        // count the active users
        $active_users = $this->countWhere('users', 'user_status', 1);

        // count the inactive users
        $inactive_users = $this->countWhere('users', 'user_status', 0);

        // count the admin users
        $admin_users = $this->countWhere('users', 'user_role', 1);
        // count the Manager
        $manager_users = $this->countWhere('users', 'user_role', 2);
        // count the Partner
        $partner_users = $this->countWhere('users', 'user_role', 3);
        // count the Transporter
        $transporter_users = $this->countWhere('users', 'user_role', 4);
        // count the Driver
        $driver_users = $this->countWhere('users', 'user_role', 5);
        // count the Pilot
        $pilot_users = $this->countWhere('users', 'user_role', 6);
        // count the Accounter
        $accounter_users = $this->countWhere('users', 'user_role', 7);
        // count the Greeter
        $greeter_users = $this->countWhere('users', 'user_role', 8);

        return [
            'user_count' => $user_count,
            'active_users' => $active_users,
            'inactive_users' => $inactive_users,
            'admin_users' => $admin_users,
            'manager_users' => $manager_users,
            'partner_users' => $partner_users,
            'transporter_users' => $transporter_users,
            'driver_users' => $driver_users,
            'pilot_users' => $pilot_users,
            'accounter_users' => $accounter_users,
            'greeter_users' => $greeter_users
        ];
    }

    /**
     * Get all login history for a user
     */
    public function getLoginHistory($userId, $limit = 20)
    {
        return $this->table('login_logs')
            ->select('login_time, ip_address, browser, operating_system, device_type, status')
            ->where('user_id', $userId)
            ->orderBy('login_time', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get login statistics
     */
    public function getLoginStats($userId)
    {
        $totalLogins = $this->table('login_logs')
            ->where('user_id', $userId)
            ->where('status', 'success')
            ->count();

        $failedLogins = $this->table('login_logs')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->count();

        $lastLogin = $this->table('login_logs')
            ->select('*')
            ->where('user_id', $userId)
            ->where('status', 'success')
            ->orderBy('login_time', 'DESC')
            ->first();

        return [
            'total_logins' => $totalLogins,
            'failed_logins' => $failedLogins,
            'last_login' => $lastLogin
        ];
    }
}
