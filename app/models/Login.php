<?php
class Login extends Model {
    
    // Maximum failed attempts before lockout
    const MAX_FAILED_ATTEMPTS = 5;
    
    // Lockout duration in minutes
    const LOCKOUT_DURATION = 1;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Authenticate user
     */
    public function connect($email, $password)
    {
        $user = $this->table('users')
            ->select('*')
            ->where('email', $email)
            ->where('user_status', 1)
            ->where('soft_delete', 1)
            ->first();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    /**
     * Get user ID by email
     */
    public function getUserIdByEmail($email)
    {
        $user = $this->table('users')
            ->select('id')
            ->where('email', $email)
            ->first();
        
        return $user ? $user->id : null;
    }

    /**
     * Check if account is locked due to too many failed attempts
     * Returns true if locked, false if not locked
     */
    public function checkAccountLocked($userId)
    {
        // Calculate the cutoff time
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-' . self::LOCKOUT_DURATION . ' minutes'));
        
        $failedCount = $this->table('login_logs')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->where('login_time', $cutoffTime, '>')
            ->count();
        
        return $failedCount >= self::MAX_FAILED_ATTEMPTS;
    }

    /**
     * Get number of remaining login attempts before lockout
     */
    public function getRemainingAttempts($userId)
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-' . self::LOCKOUT_DURATION . ' minutes'));
        
        $failedCount = $this->table('login_logs')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->where('login_time', $cutoffTime, '>')
            ->count();
        
        $remaining = self::MAX_FAILED_ATTEMPTS - $failedCount;
        
        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Clear failed login attempts after successful login
     */
    public function clearFailedAttempts($userId)
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-' . self::LOCKOUT_DURATION . ' minutes'));
        
        return $this->table('login_logs')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->where('login_time', $cutoffTime, '>')
            ->delete();
    }

    /**
     * Get failed login count for user
     */
    public function getFailedLoginCount($userId)
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-' . self::LOCKOUT_DURATION . ' minutes'));
        
        return $this->table('login_logs')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->where('login_time', $cutoffTime, '>')
            ->count();
    }

    /**
     * Get time remaining until account unlock (in minutes)
     */
    public function getTimeUntilUnlock($userId)
    {
        $result = $this->table('login_logs')
            ->select('login_time')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->orderBy('login_time', 'ASC')
            ->limit(1)
            ->first();
        
        if (!$result) {
            return 0;
        }
        
        $firstFailedTime = strtotime($result->login_time);
        $unlockTime = $firstFailedTime + (self::LOCKOUT_DURATION * 60);
        $now = time();
        
        $remainingSeconds = $unlockTime - $now;
        
        return $remainingSeconds > 0 ? ceil($remainingSeconds / 60) : 0;
    }

    /**
     * Check if user account exists and is active
     */
    public function isAccountActive($email)
    {
        $user = $this->table('users')
            ->select('user_status')
            ->where('email', $email)
            ->where('soft_delete', 1)
            ->first();

        if (!$user) {
            return false;
        }

        return $user->user_status == 1;
    }

    /**
     * Get all failed login attempts for a user (for admin dashboard)
     */
    public function getFailedLoginHistory($userId, $limit = 10)
    {
        return $this->table('login_logs')
            ->select('login_time, ip_address, browser, operating_system, device_type')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->orderBy('login_time', 'DESC')
            ->limit($limit)
            ->get();
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
     * Get recent successful logins
     */
    public function getRecentSuccessfulLogins($userId, $limit = 5)
    {
        return $this->table('login_logs')
            ->select('login_time, ip_address, browser, device_type')
            ->where('user_id', $userId)
            ->where('status', 'success')
            ->orderBy('login_time', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if there are suspicious login attempts from different IPs
     */
    public function hasSuspiciousActivity($userId)
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-1 hour'));
        
        // Get unique IPs in last hour
        $logs = $this->table('login_logs')
            ->select('ip_address')
            ->where('user_id', $userId)
            ->where('login_time', $cutoffTime, '>')
            ->get();
        
        $uniqueIps = [];
        foreach ($logs as $log) {
            if (!in_array($log->ip_address, $uniqueIps)) {
                $uniqueIps[] = $log->ip_address;
            }
        }
        
        // Flag as suspicious if more than 3 different IPs in 1 hour
        return count($uniqueIps) > 3;
    }

    /**
     * Get user by token (for password reset, email verification, etc.)
     */
    public function getUserByToken($token)
    {
        return $this->table('users')
            ->where('user_token', $token)
            ->where('soft_delete', 1)
            ->first();
    }

    /**
     * Update last login time
     */
    public function updateLastLogin($userId)
    {
        return $this->table('users')
            ->where('id', $userId)
            ->update(['last_login' => date('Y-m-d H:i:s')])
            ->save();
    }

    /**
     * Get locked accounts (for admin dashboard)
     */
    public function getLockedAccounts()
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-' . self::LOCKOUT_DURATION . ' minutes'));
        
        // Get user IDs with 5+ failed attempts
        $logs = $this->table('login_logs')
            ->select('user_id, COUNT(*) as failed_count')
            ->where('status', 'failed')
            ->where('login_time', $cutoffTime, '>')
            ->get();
        
        $lockedUserIds = [];
        foreach ($logs as $log) {
            if ($log->failed_count >= self::MAX_FAILED_ATTEMPTS) {
                $lockedUserIds[] = $log->user_id;
            }
        }
        
        if (empty($lockedUserIds)) {
            return [];
        }
        
        // Get user details
        return $this->table('users')
            ->select('id, first_name, last_name, email')
            ->whereIn('id', $lockedUserIds)
            ->where('soft_delete', 1)
            ->get();
    }

    /**
     * Manually unlock account (admin function)
     */
    public function unlockAccount($userId)
    {
        // Delete all failed login attempts
        return $this->table('login_logs')
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->delete();
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
            ->select('login_time, ip_address, browser')
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