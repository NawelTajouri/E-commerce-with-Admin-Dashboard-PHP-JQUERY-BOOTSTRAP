<?php

/*
===================================
** Manage Comments Page
**  Edit | Delete comments | Approve comments
===================================
*/

session_start();

$pageTitle = 'Members';

if(isset($_SESSION['Username'])) {
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    
    if ($do == 'Manage') {  //Manage Comments Page

    
        //SELECT ALL Comments
        
        $stmt = $con ->prepare("SELECT comments.*, items.Name as item_name, users.Username as member  FROM comments INNER JOIN items ON items.item_id = comments.item_id INNER JOIN users ON users.UserId = comments.user_id");
        //EXECUTE THE STATEMENT
        $stmt -> execute();
        //ASSIGN TO VARIABLE
        $rows= $stmt ->fetchAll();
        if(!empty($rows)) {
            ?>
            <h1 class="text-center"> Manage Comments </h1>
             <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Comment Date</td>
                            <td>Control</td>
                            
                        </tr>
                        <?php foreach($rows as $row) {
                            echo "<tr>";
                                echo "<td>" . $row['c_id'] . "</td>";
                                echo "<td>" . $row["comment"] . "</td>";
                                echo "<td>" . $row['item_name'] . "</td>";
                                echo "<td>" . $row['member'] . "</td>";
                                echo "<td>" . $row['comment_date'] . "</td>";
                                echo "<td>
                                <a href='comments.php?do=Edit&cid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                <a href='comments.php?do=Delete&cid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
    
                                if($row['status'] === 0) {
                                    echo "<a href='comments.php?do=Approve&cid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                }
                                echo "</td>";
                            echo "</tr>";
                            }
                        ?>
    
                    </table>
                </div>
               
    
             </div>
       <?php } else {
                echo '<div class="container">';
                echo '<div class="alert-message">There\'s No Record To Show</div>';
                echo '</div>';
       }?>
   
    <?php }

    elseif($do == 'Edit') { //Edit Members Page
    
        //Check if Get Request userid is Numeric & Get the Integer Value Of it
        $cid = (isset($_GET['cid']) && is_numeric($_GET['cid'])) ? intval($_GET['cid']) : 0 ;
        //Select All Data Based On This ID
        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ? " );
        //Execute the Query
        $stmt ->execute(array($cid));
        //Fetch The Data
        $row = $stmt ->fetch();
        //The Row Count
        $count = $stmt->rowCount(); //number of rows
        //If There's such Id show the Form
        if ($count > 0)  { ?> 
         <h1 class="text-center"> Edit Comment </h1>
         <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="cid" value="<?php echo $cid ?>"/>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Comment Text</label>
                    <div class="col-sm-10 col-md-6">
                      <textarea class="form-control" name="comment"><?php echo $row['comment']; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Save Modifications" class="btn btn-success btn-lg" />
                    </div>
                </div>

            </form>
         </div> 
        <?php 
        } else {
        
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>There is No Such UserId</div>";
            redirectHome($theMsg);
            echo "</div>";
        }
    } 
    elseif($do == 'Update')  { //Update Members Page
        echo "<h1 class='text-center'> Update Comment </h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Get Variables From The Form
            $id = $_POST['cid'];
            $comment = $_POST['comment'];

                //Update The DataBase with these infos
                $stmt = $con ->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt ->execute(array($comment, $id));

                //Echo Success Message
                $theMsg= "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated' ."</div>";
                redirectHome($theMsg, 'back');
        
        } 
        else {
            $theMsg = "<div class='alert alert-danger'> Sorry You cannot Browse This Page Directly!</div>";
            redirectHome($theMsg);
        }
        echo "</div>";

    } 
    elseif($do == 'Delete') {//Delete Member Page
        echo '<h1 class="text-center"> Delete comment</h1>';
        echo    '<div class="container">';
        //Check if Get Request userid is Numeric & Get the Integer Value Of it
        $cid = (isset($_GET['cid']) && is_numeric($_GET['cid'])) ? intval($_GET['cid']) : 0 ;
        //Select All Data Based On This ID
        $check = checkItem('c_id', 'comments', $cid);
        //If There's such Id show the FormINSERT
        if ($check > 0) {
            $stmt = $con -> prepare("DELETE FROM comments WHERE c_id = :zid");
            $stmt->bindParam(":zid", $cid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted' ."</div>";
            redirectHome($theMsg, 'back');
        } else {
            $theMsg= "<div class='alert alert-danger'>" . 'This Id is not Exist !' ."</div>";
            redirectHome($theMsg);
        }
        echo '</div>';
    } 
    elseif ($do == 'Approve') {
        echo '<h1 class="text-center"> Activate Member </h1>';
            echo    '<div class="container">';
            //Check if Get Request userid is Numeric & Get the Integer Value Of it
            $cid = (isset($_GET['cid']) && is_numeric($_GET['cid'])) ? intval($_GET['cid']) : 0 ;
            //Select All Data Based On This ID
            $check = checkItem('c_id', 'comments', $cid);
            //If There's such Id show the FormINSERT
            if ($check > 0) {
                $stmt = $con -> prepare("UPDATE comments SET status = 1 WHERE c_id =?");
                $stmt->execute(array($cid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved' ."</div>";
                redirectHome($theMsg);
            } else {
                $theMsg= "<div class='alert alert-danger'>" . 'This Id is not Exist !' ."</div>";
                redirectHome($theMsg);
            }
        echo '</div>';
    }


    include $tpl . 'footer.php';
} else {
    
    header('location: index.php');
    exit();
}
