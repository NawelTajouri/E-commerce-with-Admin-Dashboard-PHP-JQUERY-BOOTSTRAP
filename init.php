<?php

    // Error Reporting
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);


    include 'admin/connect.php';

    $sessionUser ='';
    if(isset($_SESSION['user'])) {
        $sessionUser = $_SESSION['user'];
    }

    //Routes
    $tpl = 'includes/templates/'; //Template Directory
    $lang = 'includes/languages/'; //languages directory
    $func = 'includes/functions/'; //functions directory
    $css = 'layout/css/'; //CSS Directory
    $js = 'layout/js/'; //js Directory


    //Include important files
    include $func . 'functions.php';
    include $lang . 'english.php';
    include $tpl . 'header.php';
   

?>
