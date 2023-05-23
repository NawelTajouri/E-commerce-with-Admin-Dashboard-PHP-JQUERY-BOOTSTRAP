<?php
    session_start();
    $pageTitle = 'Profile';
    include 'init.php';

    if (isset($_SESSION['user'])) {
        $getUser = $con -> prepare("SELECT * FROM users WHERE Username = ?");
        $getUser->execute(array($sessionUser));
        $infos = $getUser -> fetch();
        $userid = $infos['UserId'];
?>
        <h1 class="text-center">My Profile</h1>
        <div class="information block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Information</div>
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fa fa-unlock-alt fa-fw"></i>
                                <span>Name</span> : <?php echo $infos['Username']; ?></li>
                            <li>
                            <i class="fa fa-envelope-o fa-fw"></i>
                                <span>Email</span> : <?php echo $infos['Email']; ?></li>
                            <li>
                            <i class="fa fa-user fa-fw"></i>
                                <span>Full Name</span> : <?php echo $infos['FullName']; ?></li>
                            <li>
                            <i class="fa fa-calendar fa-fw"></i>
                                <span>Register Date</span> : <?php echo $infos['Date']; ?></li>
                            <li>
                            <i class="fa fa-tags fa-fw"></i>
                                <span>Favourite Category</span> : </li>
                        </ul>
                        <a href="" class="btn btn-success pull-right">Edit Information</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="my-ads" class="ads block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Advertisements</div>
                    <div class="panel-body">
                        
                        <?php
                            $myItems = getAllFrom("*", "items", "WHERE Member_ID = $userid", "", "item_id");
                            if(!empty($myItems)) {
                                echo '<div class="">';
                                    
                                    foreach($myItems  as $item) {
                                        echo '<div class="col-sm-6 col-md-4">';
                                            echo '<div class="thumbnail item-box">';
                                            
                                            if($item['Approve'] == 0)
                                             {echo '<span class="approve-status">Waiting Approval</span>';}
                                                echo '<span class="price-tag">' . $item['Price'] .'</span>';
                                                echo '<img class="img-responsive" src="img.jpg" alt="" />';
                                                echo '<div class="caption">';

                                                    echo '<h3><a href="items.php?itemid='. $item['item_id'] .' ">'. $item['Name'] .'</a></h3>';
                                                    echo '<p>'. $item['Description'] .'</p>';
                                                    echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';
                                echo '</div>';
                                }
                            } else {
                                echo ' There\'s No Ads To show! Create <a href="newad.php">New Ad</a>';
                            }
                        ?>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="comments block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">Latest Comments</div>
                    <div class="panel-body">
                        <?php 
                            $myComments = getAllFrom("comment", "comments", "WHERE user_id = $userid", "", "c_id");
                            
                            if (!empty($myComments)) {
                                foreach($myComments as $comment) {
                                    echo '<span>' . $comment['comment'] . '</span></br>';
                                }
                            } else {
                                echo 'There\'s No Comments To Show';
                            }
                           

                        ?>
                    </div>
                </div>
            </div>
        </div>

<?php 
} else {
    header('Location: login.php');
    exit();
}
    include $tpl . 'footer.php';
?>

    
    
