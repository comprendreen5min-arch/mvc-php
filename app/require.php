<?php
define('APP_INIT', true);

define('APP_ENV', getenv('APP_ENV') ?: 'production');

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}




require_once __DIR__ . '/../app/bootstrap/env.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/SessionHelper.php';
require_once __DIR__ . '/helpers/ComponentFiles.php';
require_once __DIR__ . '/libraries/Core.php';
require_once __DIR__ . '/libraries/Controller.php';
require_once __DIR__ . '/libraries/Model.php';
require_once __DIR__ . '/libraries/QueryBuilder.php';
require_once __DIR__ . '/libraries/Database.php';
require_once __DIR__ . '/libraries/ssp.class.php';
require_once __DIR__ . '/libraries/Helper.php';
require_once __DIR__ . '/libraries/Emails.php';
require_once __DIR__ . '/libraries/Twilio.php';

require_once __DIR__ . '/../vendor/autoload.php';


$init = new Core();
