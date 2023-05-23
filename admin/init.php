<?php

    include 'connect.php';

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

    //  i   nclude Navbar on all pages expect the one with $noNavbar Variable

    if (!isset($noNavbar)) {
        include $tpl . 'navbar.php';
    }
   
?>
