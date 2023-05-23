<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>style.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css"/>
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css"/>

</head>
<body>
    <div class="upper-bar">
        <div class="container">
            <?php if(isset($_SESSION['user'])) { ?>
                <img class="img-thumbnail img-circle my-avatar" src="img.jpg" alt=""/>
                <div class="btn-group my-info">
                    <span class="btn dropdown-toggle" data-toggle="dropdown">
                        <?php echo $sessionUser ?>
                        <span class="caret"></span>
                    </span> 
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">My Profile</a></li>
                        <li><a href="newad.php">New Item</a></li>
                        <li><a href="profile.php#my-ads">My Items</a></li>
                        <li><a href="logout.php">Logout</a></li>

                    </ul>
                </div>
                <?php
            } else { ?>
                <a href="login.php">
                <span class="pull-right">Login/SignUp</span>
            </a> 
                <?php } ?>
            
        </div>
    </div>
    <nav class="navbar navbar-inverse"> 
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Home</a>
        </div>

        <div class="collapse navbar-collapse" id="app-nav">
        <ul class="nav navbar-nav navbar-right">
            <?php
                $categories= getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");

                foreach ($categories as $category) {
                    echo '<li><a href="categories.php?pageid=' . $category['ID'] . '">' . $category['Name'] .'</a></li>';
                }
            ?>
        </ul>
        <!-- <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Nawel <span class="caret"></span></a>
            <ul class="dropdown-menu">
             
                <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">LogOut</a></li>
            </ul>
            </li>
        </ul> -->
        </div>
    </div>
    </nav>
