<?php

class Helper
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /* ================= REDIRECTS ================= */

    public static function redirect($path)
    {
        header('Location:' . URLROOT . '/' . $path);
        exit;
    }

    public static function redirect_to($path, $to)
    {
        header('Location:' . URLROOT . '/' . $path . '/' . $to);
        exit;
    }

    public static function redirect_error($path = 'errors/_404')
    {
        header('Location:' . URLROOT . '/' . $path);
        exit;
    }

    public static function redirect_sessions_error($path = 'errors/sessions_search_error')
    {
        header('Location:' . URLROOT . '/' . $path);
        exit;
    }

    public static function redirect_permissions($path = 'errors/error')
    {
        header('Location:' . URLROOT . '/' . $path);
        exit;
    }

    /* ================= RANDOM & TOKENS ================= */

    public static function token($length = 20)
    {
        return bin2hex(random_bytes($length / 2));
    }

    public static function random($min, $max)
    {
        return mt_rand($min, $max);
    }

    public static function random_time()
    {
        return time();
    }

    /* ================= FORM HELPERS ================= */

    public static function is_checked($data)
    {
        return ((int)$data === 1) ? 'selected' : '';
    }

    public static function checked($data, $value)
    {
        return ($data == $value) ? 'checked' : '';
    }

    public static function is_selected($data, $value)
    {
        return ($data == $value) ? 'selected' : '';
    }

    public static function return_date($datetime, $dateformat = 'Y-m-d')
    {
        if (!$datetime) return null;
        return date($dateformat, strtotime($datetime));
    }

    /* ================= FILE UPLOAD ================= */

    public function file_handle($file_name)
    {
        if (!isset($_FILES[$file_name])) return null;

        $name = $_FILES[$file_name]['name'];
        $tmp  = $_FILES[$file_name]['tmp_name'];

        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $base = pathinfo($name, PATHINFO_FILENAME);
        $safe = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);

        return [
            'file_name' => $safe . '.' . $ext,
            'file_temp' => $tmp
        ];
    }

    public function files_handle($file_name)
    {
        if (!isset($_FILES[$file_name])) return [];

        $files = $_FILES[$file_name];
        $output = [];

        foreach ($files['name'] as $index => $name) {

            $tmp  = $files['tmp_name'][$index];
            $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $base = pathinfo($name, PATHINFO_FILENAME);
            $safe = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);

            $output[] = [
                'file_name' => $safe . '.' . $ext,
                'file_temp' => $tmp
            ];
        }

        return $output;
    }

    public static function file_handle_empty($destination_folder, $new_file_name, $default_image = 'default_image.jpg')
    {
        $source = DOCROOT . '/' . UPLOADS . '/' . $default_image;
        $destination = DOCROOT . '/' . UPLOADS . '/' . $destination_folder . $new_file_name;

        if (file_exists($source)) {
            copy($source, $destination);
        }
    }

    /* ================= BREADCRUMB ================= */

    public static function breadcrumb_url()
    {
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $parts = explode('/', $url);

        $parts = array_values(array_filter($parts, function ($v) {
            return !empty($v) && $v !== 'index' && $v !== 'pages';
        }));

        $parts = array_slice($parts, 0, 2);

        foreach ($parts as $index => $value) {
            $link = URLROOT . '/' . $parts[0];
            echo '<li class="breadcrumb-item active"><a href="' . $link . '">' . ucfirst($value) . '</a></li>';
        }
    }

    /* ================= NAVIGATION HELPER ================= */
    public static function renderMenuItem($title, $icon, $url = 'javascript:void(0);', $subItems = [], $allowedRoles = [])
    {
        // Check permissions if restricted
        if (!empty($allowedRoles)) {
            $currentUserRole = $_SESSION['user_role'] ?? null;
            if (!$currentUserRole || !in_array($currentUserRole, $allowedRoles)) {
                return ''; // Don't render anything
            }
        }

        $hasSub = !empty($subItems);
        $arrowClass = $hasSub ? 'class="has-arrow waves-effect" aria-expanded="false"' : 'class="waves-effect"';

        $output = '<li>';
        $output .= '<a href="' . $url . '" ' . $arrowClass . '>';
        $output .= '<i class="' . $icon . '"></i>';
        $output .= '<span>' . $title . '</span>';
        $output .= '</a>';

        if ($hasSub) {
            $output .= '<ul class="sub-menu mm-collapse" aria-expanded="false">';
            foreach ($subItems as $item) {
                $itemUrl = isset($item['url']) ? $item['url'] : 'javascript:void(0);';
                $itemTitle = isset($item['title']) ? $item['title'] : '';

                // Recursion for multi-level capability (though typically 2 levels)
                if (isset($item['subItems']) && !empty($item['subItems'])) {
                    $output .= self::renderMenuItem($itemTitle, '', $itemUrl, $item['subItems']);
                } else {
                    $output .= '<li><a href="' . $itemUrl . '">' . $itemTitle . '</a></li>';
                }
            }
            $output .= '</ul>';
        }

        $output .= '</li>';

        return $output;
    }

    /* ================= STATUS ICON ================= */

    public static function status($status_value)
    {
        return ($status_value == 1)
            ? '<i class="fa fa-check-circle text-success"></i>'
            : '<i class="fa fa-pause-circle text-danger"></i>';
    }

    /* ================= FLASH MESSAGES ================= */

    public static function flash($name, $message = '', $class = '')
    {
        if (!empty($message)) {
            // Custom message with custom or default class
            $alertClass = !empty($class) ? $class : 'alert-info';
            $_SESSION[$name] = self::buildAlert($message, $alertClass);
        } elseif (method_exists(__CLASS__, $name)) {
            // Predefined message (success, danger, warning)
            $_SESSION['message'] = self::$name();
        }
    }

    public static function flash_message($name = 'message')
    {
        if (!empty($_SESSION[$name])) {
            echo $_SESSION[$name];
            unset($_SESSION[$name]);
            return;
        }

        // Check cookie as fallback
        if (isset($_COOKIE['flash_message'])) {
            $flash = json_decode($_COOKIE['flash_message'], true);
            echo self::buildAlert($flash['message'], $flash['class']);
            setcookie('flash_message', '', time() - 3600, '/'); // Delete cookie
        }
    }



    private static function buildAlert($message, $class)
    {
        $icons = [
            'alert-success' => 'fa-check-circle',
            'alert-danger' => 'fa-times-circle',
            'alert-warning' => 'fa-exclamation-triangle',
            'alert-info' => 'fa-info-circle',
            'alert-primary' => 'fa-bell',
        ];

        $icon = $icons[$class] ?? 'fa-info-circle';

        return '
    <div class="alert ' . $class . ' alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <i class="fa ' . $icon . ' me-2"></i>' . htmlspecialchars($message) . '
    </div>';
    }

    // Predefined messages (backward compatibility)
    public static function success()
    {
        return self::buildAlert('Well done! Your inquiry has been successfully processed.', 'alert-success');
    }

    public static function danger()
    {
        return self::buildAlert('Whoops! Something went wrong, please try again.', 'alert-danger');
    }

    public static function warning()
    {
        return self::buildAlert('Alright! Your inquiry has been successfully deleted.', 'alert-warning');
    }

    public static function info()
    {
        return self::buildAlert('Information: Please review the details.', 'alert-info');
    }




    /* ================= NUMBER TO TEXT ================= */

    public static function number_to_text($number, $lang = 'fr_FR')
    {
        if (!class_exists('NumberFormatter')) {
            throw new Exception('PHP intl extension is not installed.');
        }

        if (!is_numeric($number)) {
            throw new InvalidArgumentException('Input must be numeric.');
        }

        $formatter = new NumberFormatter($lang, NumberFormatter::SPELLOUT);
        return $formatter->format($number);
    }

    /* ================= ENCRYPTION ================= */

    private static $secret_key = 'MVC_Secret_Key_For_Encryption_987654321';
    private static $encryption_method = "AES-256-CBC";

    public static function encrypt($data)
    {
        $key = hash('sha256', self::$secret_key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$encryption_method));
        $encrypted = openssl_encrypt($data, self::$encryption_method, $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public static function decrypt($data)
    {
        $key = hash('sha256', self::$secret_key);
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, self::$encryption_method, $key, 0, $iv);
    }

    /* ================= SLUGIFY ================= */
    public static function slugify($text)
    {
        // Replace non-alphanumeric characters with hyphens
        $text = preg_replace('/[^a-zA-Z0-9]+/', '-', $text);
        // Convert to lowercase
        $text = strtolower($text);
        // Remove leading/trailing hyphens
        $text = trim($text, '-');
        return $text;
    }

    /* ================= FILTERS & VALIDATION ================= */

    public static function text(string $field, $source = INPUT_POST): string
    {
        return trim(filter_input($source, $field, FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    }

    /**
     * Sanitize email
     */
    public static function email(string $field, $source = INPUT_POST): string
    {
        return trim(filter_input($source, $field, FILTER_SANITIZE_EMAIL) ?? '');
    }

    /**
     * Validate integer
     */
    public static function int(string $field, $source = INPUT_POST): ?int
    {
        $value = filter_input($source, $field, FILTER_VALIDATE_INT);
        return ($value === false) ? null : $value;
    }

    /**
     * Validate float / decimal
     */
    public static function float(string $field, $source = INPUT_POST): ?float
    {
        $value = filter_input($source, $field, FILTER_VALIDATE_FLOAT);
        return ($value === false) ? null : $value;
    }

    /**
     * Sanitize URL
     */
    public static function url(string $field, $source = INPUT_POST): string
    {
        return trim(filter_input($source, $field, FILTER_SANITIZE_URL) ?? '');
    }

    /**
     * Validate boolean
     */
    public static function bool(string $field, $source = INPUT_POST): bool
    {
        return filter_input($source, $field, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get raw input safely (if needed)
     */
    public static function raw(string $field, $source = INPUT_POST): string
    {
        return trim(filter_input($source, $field, FILTER_UNSAFE_RAW) ?? '');
    }
    /**
     * Get input and convert special characters to HTML entities
     */
    public static function html_special_chars(string $field, $source = INPUT_POST): string
    {
        $value = filter_input($source, $field, FILTER_UNSAFE_RAW) ?? '';
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    /**
     * Get input and convert HTML entities to special characters
     */
    public static function html_special_chars_decode(string $field): string
    {
        return htmlspecialchars_decode(trim($field), ENT_QUOTES);
    }


    /* ================= TRUNCATE ================= */
    public static function truncate(string $text, int $limit = 100, string $end = '...'): string
    {
        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return $text;
        }

        return mb_substr($text, 0, $limit, 'UTF-8') . $end;
    }



}
