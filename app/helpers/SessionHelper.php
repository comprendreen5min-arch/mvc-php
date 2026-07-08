<?php


session_start();

class SessionHelper
{

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUserRole()
    {
        return $_SESSION['user_role'] ?? null;
    }

    public function getUserEmail()
    {
        return $_SESSION['email'] ?? null;
    }

    public function getUserName()
    {
        $firstName = $_SESSION['first_name'] ?? '';
        $lastName = $_SESSION['last_name'] ?? '';
        return trim($firstName . ' ' . $lastName);
    }

    /**
     * Get the role name based on the role ID
     * @param int|null $roleId Optional role ID, defaults to current user's role
     * @return string Role name or 'Unknown' if role not found
     */
    public function getRoleName($roleId = null)
    {
        $roleId = $roleId ?? $this->getUserRole();

        $roles = [
            1 => 'Admin',
            2 => 'Manager',
            3 => 'Partner',
            4 => 'Transporter',
            5 => 'Driver',
            6 => 'Pilot',
            7 => 'Accounter',
            8 => 'Greeter'
        ];

        return $roles[$roleId] ?? 'Unknown';
    }

    public function isAdmin()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 1;
    }

    public function isManager()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 2;
    }

    public function isPartner()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 3;
    }
    public function isTransporter()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 4;
    }
    public function isDriver()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 5;
    }
    public function isPilot()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 6;
    }
    public function isAccounter()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 7;
    }
    public function isGreeter()
    {
        return $this->isLoggedIn() && $this->getUserRole() == 8;
    }

    public function hasRole($roleId)
    {
        return $this->isLoggedIn() && $_SESSION['user_role'] == $roleId;
    }

    public function requireLogin($redirect = 'logins/index')
    {
        if (!$this->isLoggedIn()) {
            Helper::flash('login_message', 'Please login to continue', 'alert-warning');
            Helper::redirect($redirect);
        }
    }

    public function requireAdmin($redirect = 'errors/error')
    {
        if (!$this->isAdmin()) {
            Helper::flash('error_message', 'Access denied', 'alert-danger');
            Helper::redirect($redirect);
        }
    }

    public function requirePartner($redirect = 'errors/error')
    {
        if (!$this->isPartner()) {
            Helper::flash('error_message', 'Access denied', 'alert-danger');
            Helper::redirect($redirect);
        }
    }

    public function requireRoles(array $allowedRoles, $redirect = 'errors/error')
    {
        if (!$this->isLoggedIn() || !in_array($this->getUserRole(), $allowedRoles)) {
            Helper::flash('error_message', 'Access denied', 'alert-danger');
            Helper::redirect($redirect);
        }
    }

    public function redirectByRole()
    {
        if (!isset($_SESSION['user_role'])) {
            Helper::redirect('logins/index');
            exit;
        }

        switch ((int)$_SESSION['user_role']) {
            case 1:
                Helper::redirect('pages/index');
                break;
            case 2:
                Helper::redirect('managers/index');
                break;
            case 3:
                Helper::redirect('partners/index');
                break;
            case 4:
                Helper::redirect('transporters/index');
                break;
            case 5:
                Helper::redirect('drivers/index');
                break;
            case 6:
                Helper::redirect('pilots/index');
                break;
            case 7:
                Helper::redirect('accountants/index');
                break;
            case 8:
                Helper::redirect('greeters/index');
                break;
            default:
                Helper::redirect('pages/index');
        }
        exit;
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
    }
}
