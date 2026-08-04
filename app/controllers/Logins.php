<?php
class Logins extends Controller {
    private $mod;
    private $tracker;
    private $session;

    public function __construct()
    {
        $this->mod = $this->model('Login');
        $this->tracker = $this->model('LoginTracker');
        $this->session = new SessionHelper();
        
    }

    public function index()
    {
        // Check the session is already set and redirect
        if ($this->session->isLoggedIn()) {
           // redirect based on role id
          $this->session->redirectByRole();

        }

        if (REQUEST == 'POST') {
            $this->handleLogin();
        }

        $this->renderLoginPage();
    }

    private function handleLogin()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

       

        // Validate input
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            Helper::flash('login_message', 'Please enter both email and password', 'alert-danger');
            Helper::redirect('logins/index');
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Helper::flash('login_message', 'Invalid email format', 'alert-danger');
            Helper::redirect('logins/index');
        }

        // Get user ID by email
        $userId = $this->mod->getUserIdByEmail($email);

        // Check if account is locked BEFORE attempting login
        if ($userId && $this->mod->checkAccountLocked($userId)) {
            Helper::flash('login_message', 'Your account has been temporarily locked due to multiple failed login attempts. Please try again in 1 minute.', 'alert-danger');
            Helper::redirect('logins/index');
        }

        // Attempt login
        $user = $this->mod->connect($email, $password);

        if ($user) {
            // Successful login
            $this->createSession($user);
            $this->logLoginAttempt($user->id, true);
            
            // Optional: Clear failed attempts after successful login
            $this->mod->clearFailedAttempts($user->id);
            
           // Create flash message and redirect based on role id
            Helper::flash('dashboard_message', 'Welcome back, ' . $user->first_name . '!', 'alert-success');
            $this->session->redirectByRole();


        } else {
            // Failed login - log the attempt
            if ($userId) {
                $this->logLoginAttempt($userId, false);
                
                // Check how many failed attempts remain
                $remainingAttempts = $this->mod->getRemainingAttempts($userId);
                
                if ($remainingAttempts > 0) {
                    Helper::flash('login_message', "Invalid email or password. You have {$remainingAttempts} attempts remaining before account lockout.", 'alert-danger');
                } else {
                    Helper::flash('login_message', 'Your account has been temporarily locked due to multiple failed login attempts. Please try again in 15 minutes.', 'alert-danger');
                }
            } else {
                Helper::flash('login_message', 'Invalid email or password', 'alert-danger');
            }
            
            Helper::redirect('logins/index');
        }
    }

    private function createSession($user)
    {
        // Regenerate session ID for security
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = (int)$user->user_role;
        $_SESSION['email'] = $user->email;
        $_SESSION['first_name'] = $user->first_name;
        $_SESSION['last_name'] = $user->last_name;
        $_SESSION['login_time'] = time();
        
    }

    private function renderLoginPage()
    {
        $data = [
            "SITENAME" => SITENAME,
            "URLROOT" => URLROOT,
            "UPLOADS" => UPLOADS,
            "THEME_IMAGES" => THEME_IMAGES,
            "THEME_CSS" => THEME_CSS,
            "THEME_PLUGINS" => THEME_PLUGINS,
            "THEME_JS" => THEME_JS,
            
        ];

        $this->view('logins/index', $data);
    }

    private function logLoginAttempt($userId, $success)
    {
        $trackingData = [
            'user_id' => $userId,
            'login_time' => date('Y-m-d H:i:s'),
            'ip_address' => $this->getIpAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'device_type' => $this->getDeviceType(),
            'browser' => $this->getBrowser(),
            'operating_system' => $this->getOS(),
            'status' => $success ? 'success' : 'failed'
        ];

        $this->tracker->loginLogs('login_logs', $trackingData);
    }

    private function getIpAddress()
    {
        $ipHeaders = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipHeaders as $header) {
            if (isset($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }

    private function getDeviceType()
    {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
        
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            return 'Tablet';
        }
        
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $userAgent)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    private function getBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $browsers = [
            'Edge' => '/edg/i',
            'Chrome' => '/chrome/i',
            'Firefox' => '/firefox/i',
            'Safari' => '/safari/i',
            'Opera' => '/opera|OPR/i',
            'IE' => '/msie|trident/i'
        ];

        foreach ($browsers as $browser => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $browser;
            }
        }

        return 'Unknown';
    }

    private function getOS()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $os_array = [
            'Windows' => '/windows|win32/i',
            'macOS' => '/macintosh|mac os x/i',
            'Linux' => '/linux/i',
            'iOS' => '/iphone|ipad|ipod/i',
            'Android' => '/android/i'
        ];

        foreach ($os_array as $os => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $os;
            }
        }

        return 'Unknown';
    }

    public function logout()
    {
        // Log the logout
        if (isset($_SESSION['user_id'])) {
            $trackingData = [
                'user_id' => $_SESSION['user_id'],
                'login_time' => date('Y-m-d H:i:s'),
                'ip_address' => $this->getIpAddress(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'device_type' => $this->getDeviceType(),
                'browser' => $this->getBrowser(),
                'operating_system' => $this->getOS(),
                'status' => 'logout'
            ];
            
            $this->tracker->loginLogs('login_logs', $trackingData);
        }

        // Destroy session
        session_unset();
        session_destroy();
        
        // Use cookie for flash message (expires in 1 minute)
        setcookie('flash_message', json_encode([
            'message' => 'You have been logged out successfully',
            'class' => 'alert-success'
        ]), time() + 60, '/');
        
        Helper::redirect('logins/index');
    }


}












