<?php
    /*
    ===================================
    == Items Page
    ====================================
    */

    session_start();

    $pageTitle = 'Items';

    if(isset($_SESSION['Username'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') {
 
            //SELECT ALL USERS EXCEPT ADMINS
            
            $stmt = $con ->prepare("SELECT items.*, categories.Name As category_name, users.Username AS UserName FROM items 
            INNER JOIN categories ON categories.ID = items.Cat_ID 
            INNER JOIN users ON users.UserId = items.Member_ID");
            //EXECUTE THE STATEMENT
            $stmt -> execute();
            //ASSIGN TO VARIABLE
            $rows= $stmt ->fetchAll();

            if(!empty($rows)) {
                ?>
        
                <h1 class="text-center"> Manage Items </h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Name</td>
                                <td>Description</td>
                                <td>Price</td>
                                <td>Adding Date</td>
                                <td>Category</td>
                                <td>User Name</td>
                                <td>Control</td>
                            </tr>
                            <?php foreach($rows as $row) {
                                echo "<tr>";
                                    echo "<td>" . $row['item_id'] . "</td>";
                                    echo "<td>" . $row["Name"] . "</td>";
                                    echo "<td>" . $row['Description'] . "</td>";
                                    echo "<td>" . $row['Price'] . "</td>";
                                    echo "<td>" . $row['Add_Date'] . "</td>";
                                    echo "<td>" . $row['category_name'] . "</td>";
                                    echo "<td>" . $row['UserName'] . "</td>";
                                    echo "<td>
                                    <a href='items.php?do=Edit&itemid=" . $row['item_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                                    <a href='items.php?do=Delete&itemid=" . $row['item_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";

                                    if($row['Approve'] === 0) {
                                        echo "<a href='items.php?do=Approve&itemid=" . $row['item_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                    }
                                    echo "</td>";
                                echo "</tr>";
                                }
                            ?>
        
                        </table>
                    </div>
                    <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Item</a>
        
                </div>
           <?php } else {
                        echo '<div class="container">';
                        echo '<div class="alert-message">There\'s No Record To Show</div>';
                        echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Item</a>';
                        echo '</div>';
           }?>
            
            
            <?php 
            
        }
        elseif ($do == 'Add') { ?>
            <h1 class="text-center"> Add New Item </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="Name of The Item"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control"  autocomplete="off" required="required" placeholder="Description of The Item"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control"  autocomplete="off" required="required" placeholder="Price of The Item"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country Made</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control"  autocomplete="off" required="required" placeholder="Country of Made of The Item"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="member">
                                <option value="0">...</option>
                                <?php 
                                    $users = getAllFrom("*", "users", "", "", "UserId");
                                    
                                    foreach ($users as $user) {
                                        echo '<option value="' . $user['UserId'] . '">' . $user['Username'] .'</option>';
                                    }
                                
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="category">
                                <option value="0">...</option>
                                <?php 
                                    $cats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID");
                                   
                                    foreach ($cats as $cat) {
                                        echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] .'</option>';
                                        $childCats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}", "", "ID");
                                        foreach ($childCats as $child) {
                                            echo '<option value="' . $child['ID'] . '">---' . $child['Name'] .'</option>';
                                        }
                                    }
                                
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control"  autocomplete="off"  placeholder="Separate Tags with Comma (,)"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-success btn-sm" />
                        </div>
                    </div>

                </form>
            </div> 
           
           <?php 
        }
        elseif ($do == 'Insert') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
                echo "<h1 class='text-center'> Insert An Item </h1>";
                echo "<div class='container'>";
                //Get Variables From The Form
                
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $member = $_POST['member'];
                $category = $_POST['category'];
                $tags = $_POST['tags'];
                //Validate Form

                $formErrors = array();
                
                if (empty($name)) {
                    $formErrors[] = 'Name cannot be <strong>empty</strong>';  
                }
                if (empty($desc)) {
                    $formErrors[] = 'Description cannot be <strong>empty</strong>';  
                }
                if (empty($price)) {
                    $formErrors[] = 'Price cannot be <strong>empty</strong>';
                }
                if (empty($country)) {
                    $formErrors[] = 'Country cannot be <strong>empty</strong>';
                }
                if ($status === '0') {
                    $formErrors[] = 'You must choose the <strong>status</strong>';
                }
                if ($member === '0') {
                    $formErrors[] = 'You must choose the <strong>member</strong>';
                }
                if ($category === '0') {
                    $formErrors[] = 'You must choose the <strong>category</strong>';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . "</div>";
                }
                //Check if there is no error
                if(empty($formErrors)){
                    //Insert new Item to the Database                  
                    $stmt = $con ->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags) 
                    VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcatid, :zmemberid, :ztags )");
                    $stmt ->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zprice' => $price,
                        'zcountry' => $country,
                        'zstatus' => $status, 
                        'zcatid' => $category,
                        'zmemberid' => $member,
                        'ztags' => $tags
                        
                        
                    ));
        
                    //Echo Success Message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Item Successfully Inserted' ."</div>";
                    redirectHome($theMsg, 'back');
                    
                    
                }

                
            } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>Sorry You cannot Browse This Page Directly</div>";
                redirectHome($theMsg);
                echo "</div>";
            }
            echo "</div>";


        }
        elseif ($do == 'Edit') {
            //Check if Get Request userid is Numeric & Get the Integer Value Of it
            $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0 ;
            //Select All Data Based On This ID
            $stmt = $con->prepare("SELECT * FROM items WHERE item_id = ? " );
            //Execute the Query
            $stmt ->execute(array($itemid));
            //Fetch The Data
            $row = $stmt ->fetch();
            //The Row Count
            $count = $stmt->rowCount(); //number of rows
            //If There's such Id show the Form
            if ($count > 0)  { ?> 
            <h1 class="text-center"> Edit Item </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>"/>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" value= "<?php echo $row['Name']; ?>" autocomplete="off" required="required" placeholder="Name of The Item"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" value= "<?php echo $row['Description']; ?>"  autocomplete="off" required="required" placeholder="Description of The Item"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control" value= "<?php echo $row['Price']; ?>"  autocomplete="off" required="required" placeholder="Price of The Item"/>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country Made</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control" value= "<?php echo $row['Country_Made']; ?>" autocomplete="off" required="required" placeholder="Country of Made of The Item"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="status">
                                <option value="0">...</option>
                                <option value="1" <?php if ($row['Status'] == 1) { echo 'selected';} ?>>New</option>
                                <option value="2" <?php if ($row['Status'] == 2) { echo 'selected';} ?>>Like New</option>
                                <option value="3" <?php if ($row['Status'] == 3) { echo 'selected';} ?>>Used</option>
                                <option value="4" <?php if ($row['Status'] == 4) { echo 'selected';} ?>>Old</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="member">
                                <option value="0">...</option>
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user) {
                                        echo '<option value="' . $user['UserId'] . '"'; 
                                        if ($row['Member_ID'] == $user['UserId']) { echo 'selected';}
                                        echo '>' . $user['Username'] .'</option>';
                                    }
                                
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="form-control" name="category">
                                <option value="0">...</option>
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $cats = $stmt->fetchAll();
                                    foreach ($cats as $cat) {
                                        echo '<option value="' . $cat['ID'] . '"';
                                        if ($row['Cat_ID'] == $cat['ID']) { echo 'selected';}
                                        echo '>' . $cat['Name'] .'</option>';
                                    }
                                
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="tags" class="form-control"  autocomplete="off"  value="<?php echo $row['tags']; ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Modifications" class="btn btn-success btn-lg" />
                        </div>
                    </div>

                </form>

                <?php
                //SELECT ALL Comments
                
                $stmt = $con ->prepare("SELECT comments.*,  users.Username as member  FROM comments INNER JOIN users ON users.UserId = comments.user_id WHERE item_id = ?");
                //EXECUTE THE STATEMENT
                $stmt -> execute(array($itemid));
                //ASSIGN TO VARIABLE
                $rows= $stmt ->fetchAll();
                if (!empty($rows)){
                    ?>
                    <h3 class="text-center"> Manage [<?php echo $row['Name'] ;?>] Comments </h3>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                
                                <td>Comment</td>
                                
                                <td>User Name</td>
                                <td>Comment Date</td>
                                <td>Control</td>
                                
                            </tr>
                            <?php foreach($rows as $row) {
                                echo "<tr>";
                                    
                                    echo "<td>" . $row["comment"] . "</td>";
                                   
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
              <?php  } ?>

                
            </div> 
            <?php 
            } else {
            
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>There is No Such ItemId</div>";
                redirectHome($theMsg);
                echo "</div>";
            }
        }
        elseif ($do == 'Update') {
            echo "<h1 class='text-center'> Update Item </h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Get Variables From The Form
                $id = $_POST['itemid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $member = $_POST['member'];
                $category = $_POST['category'];
                $tags = $_POST['tags'] ;

                $formErrors = array();
                
                if (empty($name)) {
                    $formErrors[] = 'Name cannot be <strong>empty</strong>';  
                }
                if (empty($desc)) {
                    $formErrors[] = 'Description cannot be <strong>empty</strong>';  
                }
                if (empty($price)) {
                    $formErrors[] = 'Price cannot be <strong>empty</strong>';
                }
                if (empty($country)) {
                    $formErrors[] = 'Country cannot be <strong>empty</strong>';
                }
                if ($status === '0') {
                    $formErrors[] = 'You must choose the <strong>status</strong>';
                }
                if ($member === '0') {
                    $formErrors[] = 'You must choose the <strong>member</strong>';
                }
                if ($category === '0') {
                    $formErrors[] = 'You must choose the <strong>category</strong>';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . "</div>";
                }
                if(empty($formErrors)){
                    //Update The DataBase with these infos
                    $stmt = $con ->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ? , Member_ID = ? , Cat_ID = ?, tags= ? WHERE item_id = ?");
                    $stmt ->execute(array($name,$desc,$price,$country, $status, $member, $category, $tags, $id));

                    //Echo Success Message
                    $theMsg= "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated' ."</div>";
                    redirectHome($theMsg, 'back');
                } 
            } 
            else {
                $theMsg = "<div class='alert alert-danger'> Sorry You cannot Browse This Page Directly!</div>";
                redirectHome($theMsg);
            }
            echo "</div>";
        }
        elseif ($do == 'Delete') {
            echo '<h1 class="text-center"> Delete Item </h1>';
            echo    '<div class="container">';

            //Check if Get Request itemid is Numeric & Get the Integer Value Of it
            $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0 ;
            //Select All Data Based On This ID
            $check = checkItem('item_id', 'items', $itemid);
            //If There's such Id show the FormINSERT
            if ($check > 0) {
                $stmt = $con -> prepare("DELETE FROM items WHERE item_id = :zid");
                $stmt->bindParam(":zid", $itemid);
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
            echo '<h1 class="text-center"> Approve Item </h1>';
            echo    '<div class="container">';
            //Check if Get Request userid is Numeric & Get the Integer Value Of it
            $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0 ;
            //Select All Data Based On This ID
            $check = checkItem('item_id', 'items', $itemid);
            //If There's such Id show the FormINSERT
            if ($check > 0) {
                $stmt = $con -> prepare("UPDATE items SET Approve = 1 WHERE item_id =?");
                $stmt->execute(array($itemid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Approved' ."</div>";
                redirectHome($theMsg, 'back');
            } else {
                $theMsg= "<div class='alert alert-danger'>" . 'This Id is not Exist !' ."</div>";
                redirectHome($theMsg, 'back');
            }
        echo '</div>';
        }
        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
    }

    
?> 