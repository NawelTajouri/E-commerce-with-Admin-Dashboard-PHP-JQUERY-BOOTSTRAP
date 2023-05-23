<?php
    session_start();
    $pageTitle = 'Show Item';
    include 'init.php';
    //Check if Get Request userid is Numeric & Get the Integer Value Of it
    $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0 ;
    //Select All Data Based On This ID
    $stmt = $con->prepare("SELECT items.*, categories.Name As category_name, users.Username As username
                            FROM 
                            items
                            INNER JOIN
                                categories
                            ON 
                                categories.ID = items.Cat_ID
                            INNER JOIN
                                users
                            ON 
                                users.UserId = items.Member_ID
                            WHERE 
                                item_id = ? 
                            AND 
                                Approve = 1" );
    //Execute the Query
    $stmt ->execute(array($itemid));
    $count = $stmt ->rowCount();

    if($count > 0) {
    $row = $stmt ->fetch();
    ?>
        <h1 class="text-center"><?php echo $row['Name'] ?></h1>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <img class="img-responsive img-thumbnail center-block" src="img.jpg" alt="" />
                </div>
                <div class="col-md-9 item-info">
                    <h2><?php echo $row['Name'] ?></h2>
                    <p><?php echo $row['Description'] ?></p>
                    <ul class="list-unstyled">
                        <li><span>Added Date</span> : <?php echo $row['Add_Date'] ?></li>
                        <li><span>Price</span> : <?php echo $row['Price'] ?></li>
                        <li><span>Made In</span> : <?php echo $row['Country_Made'] ?></li>
                        <li><span>Category</span> : <a href="categories.php?pageid=<?php echo $row['Cat_ID'] ?>"><?php echo $row['category_name']; ?></a></li>
                        <li><span>Added By</span> : <?php echo $row['username']; ?></li>
                        <li class="tags-items"><span>Tags</span> :
                            <?php 
                                $allTags = explode(",", $row['tags']);
                                foreach ($allTags as $tag) {
                                    $tag = str_replace(' ','', $tag);
                                    $tag = strtolower($tag);
                                    if (!empty($tag)) {
                                        echo '<a href="tags.php?name=' . $tag . '">' . $tag . '</a>';
                                    }
                                   

                                }
                            ?>
                        </li>
                    </ul>
                    
                </div>
            </div>

            <hr class="custom-hr">
            <?php if(isset($_SESSION['user'])) {?>
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h4>Add Your comment</h4>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $row['item_id'] ?>" method="POST" >
                            
                            <textarea name="comment" required></textarea>
                            <input class="btn btn-success" type="submit" value="Add comment" >
                            
                        </form>
                        <?php
                            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $comment = strip_tags($_POST['comment']);
                                $itemid = $row['item_id'];
                                $userid = $_SESSION['uid'];
                                

                                if(!empty($comment)) {
                                    $stmt = $con ->prepare("INSERT INTO comments(comment, status, comment_date, item_id, user_id) VALUES (:zcomment, 0, now(), :zitemid, :zuserid)");
                                    $stmt->execute(array(
                                        'zcomment' => $comment,
                                        'zitemid' => $itemid,
                                        'zuserid' => $userid
                                    ));
                                    if($stmt) {
                                        echo '<div class="alert alert-success">Comment Added</div>';
                                    
                                    }
                                }
                            }
                        ?>
                    </div>
                    
                </div>
                
            </div>
            <?php  
                } else {
                echo '<a href="login.php">Login Or Regiter to Add comment</a>';
                } 
            ?>
            <hr class="custom-hr">
            <?php
                $stmt = $con ->prepare("SELECT comments.*, users.Username as member  FROM comments INNER JOIN users ON users.UserId = comments.user_id WHERE item_id = ? AND status = 1 ORDER BY c_id DESC ");
                //EXECUTE THE STATEMENT
                $stmt -> execute(array(
                    $itemid
                ));
                $rows = $stmt ->fetchAll();
                    foreach($rows as $row) { ?>
                        <div class="comment-box">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                    <img class="img-responsive img-thumbnail img-circle center-block"  src="img.jpg" alt="" />
                                    <?php echo $row['member'] ?>
                                </div>
                                <div class="col-sm-10">
                                    <p class="lead"><?php echo $row['comment'] ?></p>
                                </div>
                            </div>
                        </div>
                        <hr class="comment-hr">
                    <?php } ?>             
        </div>
    <?php  
    }  else {
        echo '<div class="alert alert-danger">There\'s No Such Item With this ID Or This Item Is waiting to Approve!</div>';
    } 
    

    
    include $tpl . 'footer.php';
?>

    
    
