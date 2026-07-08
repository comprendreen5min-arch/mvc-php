<?php
$session_check = new SessionHelper();
$session_check->requireLogin();
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title><?php print SITENAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MVC PHP 1.0.0" name="description">
    <meta content="9-SHEN" name="author">
    <script>
        window.URLROOT = "<?php print URLROOT; ?>";
    </script>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php print URLROOT . '/' . THEME_IMAGES; ?>/favicon.ico">

    <!-- Select 2 -->
    <link href="<?php print URLROOT . '/' . THEME_PLUGINS; ?>/select2/css/select2.min.css" rel="stylesheet"
        type="text/css">

   

    <!-- Datatables -->
    <link href="<?php print URLROOT . '/' . PLUGINS; ?>/datatables.net/dataTables.bootstrap4.min.css"
        rel="stylesheet" />
    <link href="<?php print URLROOT . '/' . PLUGINS; ?>/datatables.net/rowReorder.dataTables.min.css"
        rel="stylesheet" />
    <link href="<?php print URLROOT . '/' . PLUGINS; ?>/datatables.net/responsive.dataTables.min.css"
        rel="stylesheet" />
    <link href="<?php print URLROOT . '/' . PLUGINS; ?>/datatables.net/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="<?php print URLROOT . '/' . PLUGINS; ?>/datatables.net/buttons.dataTables.min.css" rel="stylesheet" />

    

    <!-- Bootstrap Css -->

    <link href="<?php print URLROOT . '/' . THEME_CSS; ?>/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="<?php print URLROOT . '/' . THEME_CSS; ?>/icons.min.css" rel="stylesheet" type="text/css">



    <!-- App Css -->
    <link href="<?php print URLROOT . '/' . THEME_CSS; ?>/app.min.css" id="app-style" rel="stylesheet" type="text/css">
    <!-- Master Css -->
    <link href="<?php print URLROOT . '/' . CSS; ?>/master.css" id="app-style" rel="stylesheet" type="text/css">

</head>

<body data-sidebar="dark" data-bs-theme="light" data-topbar="light">