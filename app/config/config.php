<?php
defined('APP_INIT') or die('No direct access allowed');

// Database

define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_NAME', getenv('DB_NAME'));

define('BACKUP_MASTER_HASH', getenv('BACKUP_MASTER_HASH'));

// $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
// $host = $_SERVER['HTTP_HOST'];
// $folder = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(dirname(__DIR__)));

// define('URLROOT', $protocol . $host . $folder);


// APP

define('APPROOT', dirname(dirname(__FILE__)));
define('DOCROOT', dirname(dirname(dirname(__FILE__))));
define('URLROOT', 'http://localhost:8888/ecommerce_app');
define('SITENAME', 'MVC PHP 1.0.0');



// CSS & JS

define('CSS', 'public/css');
define('JS', 'public/javascript');
define('IMG', 'public/images');
define('UPLOADS', 'public/uploads');
define('PLUGINS', 'public/plugins');
define('LOGOS', 'public/uploads/logos');
define('LOGOS_PATH', DOCROOT . '/public/uploads/logos');




// THEME

define('THEME_BASE', 'public/theme/theme-1/Admin/dist/assets');

define('THEME_CSS', THEME_BASE . '/css');
define('THEME_JS', THEME_BASE . '/js');
define('THEME_IMAGES', THEME_BASE . '/images');
define('THEME_PLUGINS', THEME_BASE . '/libs');


define('THEME', 'public/theme');
define('THEME_ASSETS', 'public/theme/theme-1/Admin/dist/assets');
define('THEME_FONTS', '');


// REQUEST METHOD
define('REQUEST', $_SERVER['REQUEST_METHOD']);
