<?php

/*
    Categories => [ Manage | Edit | Update | Add | Insert | Delete | Stats]
*/

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// if ( isset($_GET['do'])) {
//   $do = $_GET['do'];
// } else {
//     $do = 'Manage';
// }

// If the Page is the main Page 
if ($do == 'Manage') {
    echo 'Welcome You Are in Manage Category Page';
    echo '<a href="page.php?do=Add">Add New Category +</a>';
}
elseif ($do == 'Add') {
    echo 'Welcome You Are in Add Category Page';
}
elseif ($do == 'Insert') {
    echo 'Welcome You Are in Insert Category Page';
}
// if ($do == 'Manage') {
//     echo 'Welcome You Are in Manage Category Page';
// }
else {
    echo 'Error, there\'s No Page';
}