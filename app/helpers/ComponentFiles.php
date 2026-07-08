<?php
class ComponentFiles
{

    public static function header()
    {
        require_once '../app/views/inc/header.php';
        require_once '../app/views/inc/navbar.php';
        require_once '../app/views/inc/aside.php';
        require_once '../app/views/inc/breadcrumb.php';
    }

    public static function footer()
    {
        require_once '../app/views/inc/copyright.php';
        require_once '../app/views/inc/footer.php';
    }

    public static function _header()
    {

        require_once '../app/views/inc/_header.php';
    }

    public static function _footer()
    {
        require_once '../app/views/inc/_footer.php';
    }
}
