<?php

/*
===================================
** Manage Members Page
** Add | Edit | Delete Memebers
===================================
*/

session_start();

$pageTitle = 'Members';

if(isset($_SESSION['Username'])) {
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    
    if ($do == 'Manage') {  //Manage Members Page

        $query = '';
        if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query ='AND RegStatus = 0';
        }
        //SELECT ALL USERS EXCEPT ADMINS
        
        $stmt = $con ->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserId DESC");
        //EXECUTE THE STATEMENT
        $stmt -> execute();
        //ASSIGN TO VARIABLE
        $rows= $stmt ->fetchAll();
        if (!empty($rows)) {
    ?>

        <h1 class="text-center"> Manage Members </h1>
         <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Photo</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>
                    <?php foreach($rows as $row) {
                        echo "<tr>";
                            echo "<td>" . $row['UserId'] . "</td>";
                            echo "<td>";
                            if (empty($row['avatar'])) {
                                echo 'No Image';
                            } else {
                                echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
                            }
                                
                            echo "</td>" ;
                            echo "<td>" . $row["Username"] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                            <a href='members.php?do=Edit&userid=" . $row['UserId'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                            <a href='members.php?do=Delete&userid=" . $row['UserId'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";

                            if($row['RegStatus'] === 0) {
                                echo "<a href='members.php?do=Activate&userid=" . $row['UserId'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";
                            }
                            echo "</td>";
                        echo "</tr>";
                        }
                    ?>

                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Member</a>

         </div>

         <?php } else {
            echo '<div class="container">';
                echo '<div class="alert-message">There\'s No Record To Show</div>';
                echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Member</a>';
            echo '</div>';
         } ?>
    <?php }

    elseif($do == 'Add') { //Add Members Page?>
       
        <h1 class="text-center"> Add New Member </h1>
         <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control"  autocomplete="off" required="required" placeholder="Username to Login"/>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Password must be Hard and Complexe" />
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control"  required="required" placeholder="Email Must be Valid"/>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="full" class="form-control"  required="required" placeholder="Full Name of the User" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">User Avatar</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" name="avatar" class="form-control"  required="required" placeholder="Choose Profile Image" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Save" class="btn btn-success btn-lg" />
                    </div>
                </div>

            </form>
         </div> 
        <?php } 
    elseif ($do == 'Insert') { //Insert Mmember Page
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'> Insert Member </h1>";
            echo "<div class='container'>";
            // Upload Variables
            $avatar = $_FILES['avatar'];
            
            $avatarname = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];


            // Allowed Files Types To Upload
            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
            
            // Get Avatar Extension
            $avatarExtension = explode('.', $avatarname);
            $avatarExtension = end($avatarExtension);
            $avatarExtension = strtolower($avatarExtension);
             
            //Get Variables From The Form
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            $hashPass = sha1($_POST['password']);
    
            //Validate Form
    
            $formErrors = array();
            if (strlen($user) < 4) {
                $formErrors[] = 'UserName cannot be <strong>less Than 4 characters</strong>';
            }
            if (empty($user)) {
                $formErrors[] = 'UserName cannot be <strong>empty</strong>';  
            }
            if (empty($pass)) {
                $formErrors[] = 'Password cannot be <strong>empty</strong>';  
            }
            if (empty($name)) {
                $formErrors[] = 'FullName cannot be <strong>empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'Email cannot be <strong>empty</strong>';
            }
            if(!empty($avatarname) && !in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] = 'Extension File is not <strong>allowed</strong>';
            }  

            if(empty($avatarname)) {
                $formErrors[] = 'Image is <strong>Required</strong>';
            }  
            if($avatarSize > 4194304) {
                $formErrors[] = 'Image cannot be <strong>Larger Than 4MB</strong>';
            }  
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . "</div>";
            }
            
            //Check if there is no error proceed the update
            if(count($formErrors)==0){
                
                $avatar = rand(0, 1000000) . '_' . $avatarname ;
                move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
                
                //Check If User Exist in Database
                $check = checkItem("Username", "users", $user);
                if ($check == 1) {
                    $theMsg= "<div class='alert alert-danger'>Sorry This User Is Exist</div>" ;
                    redirectHome($theMsg, 'back');
                }
                else {
                //Insert new Member to the Database
               
                $stmt = $con ->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date, avatar) VALUES (:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar)");
                $stmt ->execute(array(
                    'zuser' => $user,
                    'zpass' => $hashPass,
                    'zmail' => $email,
                    'zname' => $name, 
                    'zavatar' => $avatar
                    
                ));
    
                //Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted' ."</div>";
                redirectHome($theMsg, 'back');
            }
            
         }
           
        } else {
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>Sorry You cannot Browse This Page Directly</div>";
            // redirectHome($theMsg);
            echo "</div>";
        } 
        echo "</div>";

    }
    elseif($do == 'Edit') { //Edit Members Page
    
        //Check if Get Request userid is Numeric & Get the Integer Value Of it
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0 ;
        //Select All Data Based On This ID
        $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1" );
        //Execute the Query
        $stmt ->execute(array($userid));
        //Fetch The Data
        $row = $stmt ->fetch();
        //The Row Count
        $count = $stmt->rowCount(); //number of rows
        //If There's such Id show the Form
        if ($count > 0)  { ?> 
         <h1 class="text-center"> Edit Member </h1>
         <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid ?>"/>
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control" value= "<?php echo $row['Username']; ?>" autocomplete="off" required="required"/>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="hidden" name="oldpassword" value= "<?php echo $row['Password']; ?>"/>
                        <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave it blank if don't want to change" />
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control" value= "<?php echo $row['Email']; ?>" required="required"/>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="full" class="form-control" value= "<?php echo $row['FullName']; ?>" required="required" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Save" class="btn btn-success btn-lg" />
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
        echo "<h1 class='text-center'> Update Member </h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Get Variables From The Form
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            //Password Trick
            //Condition ? true: false
            $pass= empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']) ;

            //Validate Form

            $formErrors = array();
            if (strlen($user) < 4) {
                $formErrors[] = '<div class="alert alert-danger">UserName cannot be <strong>less Than 4 characters</strong></div>';
            }
            if (empty($user)) {
                $formErrors[] = '<div class="alert alert-danger">UserName cannot be <strong>empty</strong></div>';  
            }
            if (empty($name)) {
                $formErrors[] = '<div class="alert alert-danger">FullName cannot be <strong>empty</strong></div>';
            }
            if (empty($email)) {
                $formErrors[] = '<div class="alert alert-danger">Email cannot be <strong>empty</strong></div>';
            }
            foreach ($formErrors as $error) {
                echo $error . "<br/>";
            }

            //Check if there is no error proceed the update
            if(count($formErrors)==0){

                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserId != ?");
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();
                if ($count == 1) {
                    $theMsg = '<div class="alert alert-danger">This User is exist</div>';
                    redirectHome($theMsg, 'back');
                } else {
                    
                    //Update The DataBase with these infos
                    $stmt = $con ->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                    $stmt ->execute(array($user,$email,$name,$pass, $id));

                    //Echo Success Message
                    $theMsg= "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated' ."</div>";
                    redirectHome($theMsg, 'back');
                
                }
                
            }
            
        } 
        else {
            $theMsg = "<div class='alert alert-danger'> Sorry You cannot Browse This Page Directly!</div>";
            redirectHome($theMsg);
        }
        echo "</div>";

    } 
    elseif($do == 'Delete') {//Delete Member Page
        echo '<h1 class="text-center"> Delete Member </h1>';
        echo    '<div class="container">';
        //Check if Get Request userid is Numeric & Get the Integer Value Of it
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0 ;
        //Select All Data Based On This ID
        $check = checkItem('userid', 'users', $userid);
        //If There's such Id show the FormINSERT
        if ($check > 0) {
            $stmt = $con -> prepare("DELETE FROM users WHERE UserId = :zid");
            $stmt->bindParam(":zid", $userid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted' ."</div>";
            redirectHome($theMsg, 'back');
        } else {
            $theMsg= "<div class='alert alert-danger'>" . 'This Id is not Exist !' ."</div>";
            redirectHome($theMsg);
        }
        echo '</div>';
    } 
    elseif ($do == 'Activate') {
        echo '<h1 class="text-center"> Activate Member </h1>';
            echo    '<div class="container">';
            //Check if Get Request userid is Numeric & Get the Integer Value Of it
            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0 ;
            //Select All Data Based On This ID
            $check = checkItem('userid', 'users', $userid);
            //If There's such Id show the FormINSERT
            if ($check > 0) {
                $stmt = $con -> prepare("UPDATE users SET RegStatus = 1 WHERE UserId =?");
                $stmt->execute(array($userid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated' ."</div>";
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
