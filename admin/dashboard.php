<?php
    session_start();


    if(isset($_SESSION['Username'])) {
        $pageTitle = "Dashboard";
        include 'init.php';

        /* Start Dashboard Page */
        $numUsers = 4 ; //Number of Latest Users
        $latestUsers = getLatest("*", "users", "UserId",$numUsers);
        
        $numItems = 2; //Number of latest Items
        $latestItems = getLatest("*", "items", "item_id", $numItems);

        $numComments = 3; //Number of latest Items
        


        ?>

        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo countItems('UserId', 'users'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus', 'users', 0); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo countItems('item_id', 'items'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItems('c_id', 'comments'); ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class= "container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registered Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-sort-desc fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php 
                                    if(!empty($latestUsers)) {
                                        foreach ($latestUsers as $last) {
                                            echo '<li>';
                                            echo $last['Username'];
                                            echo  '<a href="members.php?do=Edit&userid=' . $last['UserId'] . '">';
                                            echo '<span class="btn btn-success pull-right">';
                                            echo '<i class="fa fa-edit"></i>
                                            Edit';
                                            if($last['RegStatus'] === 0) {
                                                echo "<a href='members.php?do=Activate&userid=" . $last['UserId'] . "' class='btn btn-info activate pull-right'><i class='fa fa-check'></i>Activate</a>";
                                            }
                                            echo '</span>';
                                            echo '</a>';
                                            echo '</li>';
                                            
                                        }
                                    }
                                    else { echo 'There\'s No User'; }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php  echo $numItems ;?> Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-sort-desc fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                    <?php 
                                        if(!empty($latestItems)) {
                                            foreach ($latestItems as $last) {
                                                echo '<li>';
                                                echo $last['Name'];
                                                echo  '<a href="items.php?do=Edit&itemid=' . $last['item_id'] . '">';
                                                echo '<span class="btn btn-success pull-right">';
                                                echo '<i class="fa fa-edit"></i>
                                                Edit';
                                                if($last['Approve'] === 0) {
                                                    echo "<a href='items.php?do=Approve&itemid=" . $last['item_id'] . "' class='btn btn-info activate pull-right'><i class='fa fa-check'></i>Approve</a>";
                                                }
                                                echo '</span>';
                                                echo '</a>';
                                                echo '</li>';
                                                
                                            }
                                        } else {echo 'There\'s no record to show';}
                                        
                                    ?>
                                </ul>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Start Latest Comments  -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            <i class="fa fa-comments"></i> Latest <?php echo $numComments; ?> Comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-sort-desc fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                                $stmt = $con ->prepare("SELECT comments.*,  users.Username as member  FROM comments INNER JOIN users ON users.UserId = comments.user_id ORDER BY c_id DESC LIMIT $numComments");
                                $stmt -> execute();
                                //ASSIGN TO VARIABLE
                                $comments= $stmt ->fetchAll();
                                if (!empty($comments)){
                                    foreach ($comments as $comment) {
                                        echo '<div class="comment-box">';
                                            echo '<span class="member-name">' . $comment['member'] . '</span>';
                                            echo '<p class="member-comment">' . $comment['comment'] . '</p>';
                                        echo '</div>';
                                    }
                                } else { echo 'There\'s No Record To Show!';}
                                
                            ?>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- End Latest Comment -->
                                        
        </div>
        <?php
        /* End Dashboard Page */

        include $tpl . 'footer.php';
    } else {
        
        header('location: index.php');
        exit();
    }
   