<?php
    /*
    ===================================
    == Categories Page
    ====================================
    */

    session_start();

    $pageTitle = 'Categories';

    if(isset($_SESSION['Username'])) {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') {
            $sort = "ASC";
            $sort_array = array('ASC', 'DESC');
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                $sort = $_GET['sort'];
            }
            $statement = $con -> prepare("SELECT * FROM categories Where parent = 0 ORDER BY Ordering $sort");
            $statement -> execute();
            $cats = $statement->fetchAll(); 
            if (!empty($cats)) {
                ?>

            
            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Manage Categories
                        <div class="ordering pull-right">
                            <i class="fa fa-sort"></i>Ordering: [
                            <a class="<?php if($sort == 'ASC') { echo 'active';} ?>" href="?sort=ASC">ASC</a> |
                            <a class="<?php if($sort == 'DESC') { echo 'active';} ?>" href="?sort=DESC">DESC</a> ]
                            <i class="fa fa-eye"></i>View: [
                            <span class="active" data-view="full">Full</span> |
                            <span>Classic</span> ]
                            
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php 
                            foreach($cats as $cat) {
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-success'>Edit</a>";
                                        echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'>Delete</a>";
                                    echo "</div>";

                                    echo '<h3>' . $cat['Name'] . '</h3>';
                                    echo '<div class="full-view">';
                                        echo '<p>'; if($cat['Description'] == '') {echo 'There is no Description' ;} else {echo $cat['Description'];} echo'</p>';
                                        if($cat['Visibility'] == 1) {echo '<span class="visibility">Hidden</span>'; } ;
                                        if($cat['Allow_Comment'] == 1) {echo '<span class="commenting">Comment Disable</span>'; } ;
                                        if($cat['Allow_Ads'] == 1) {echo '<span class="advertises">Ads Disable</span>'; } ; 
                                    echo '</div>';
                                echo "</div>";      
                                

                                //get Child Categories
                                $childCats = getAllFrom("*", "categories", "WHERE parent= {$cat['ID']}", "", "ID", "ASC");
                                if (!empty($childCats)) {
                                    echo '<h5 class="child-head">Child Categories</h5>';
                                    echo '<ul class="list-unstyled child-cats">';
                                        foreach ($childCats  as $child) {
                                            echo '<li class= "child-link">
                                                <a  href="categories.php?do=Edit&catid=' . $child['ID'] . '">' . $child['Name'] . '</a>
                                                <a class="show-delete confirm" href="categories.php?do=Delete&catid=' . $child['ID'] . '" >Delete</a>
                                                </li>';
                                        }
                                    echo '</ul>';
                                };
                                echo "<hr>";  
                                           
                            }
                        ?>
                    </div>
                </div>
                <a href="categories.php?do=Add" class=" add-category btn btn-primary"><i class="fa fa-plus"></i>Add New Category</a>
            </div>
            <?php } else {
                echo '<div class="container">';
                echo '<div class="alert-message">There\'s No Record To Show</div>';
                echo '<a href="categories.php?do=Add" class=" add-category btn btn-primary"><i class="fa fa-plus"></i>Add New Category</a>';
            echo '</div>';
            } ?>
            
        <?php  
        }
        elseif ($do == 'Add') { ?>
            <h1 class="text-center"> Add New Category </h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="Name of The Category"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control"   placeholder="Descriobe The Category" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="ordering" class="form-control"   placeholder="Number to Arrange the Categories"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
                                    foreach ($allCats as $cat) {
                                        echo '<option value="' . $cat['ID'] .  '">' . $cat['Name'] . '</option>';
                                    }    
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visibile</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="visible-yes" type="radio" name="visibility" value="0" checked />
                                <label for="visible-yes">Yes</label>
                            </div>
                            <div>
                                <input id="visible-no" type="radio" name="visibility" value="1"  />
                                <label for="visible-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="comment-yes" type="radio" name="commenting" value="0" checked />
                                <label for="comment-yes">Yes</label>
                            </div>
                            <div>
                                <input id="comment-no" type="radio" name="commenting" value="1"  />
                                <label for="comment-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1"  />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-success btn-lg" />
                        </div>
                    </div>

                </form>
            </div> 
           
           <?php   
        }
        elseif ($do == 'Insert') { //Insert Category Page
        
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
                echo "<h1 class='text-center'> Insert A Category </h1>";
                echo "<div class='container'>";
                //Get Variables From The Form
                
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $parent = $_POST['parent'];
                $order = $_POST['ordering'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads'];

                //Check If Category Exist in Database
                $check = checkItem("Name", "categories", $name);
                if ($check == 1) {
                    $theMsg= "<div class='alert alert-danger'>Sorry This Category Is Already Exist</div>" ;
                    redirectHome($theMsg, 'back');
                }
                else {
                //Insert new Category to the Database
                
                $stmt = $con ->prepare("INSERT INTO categories(Name, Description,parent, Ordering, Visibility, Allow_Comment, Allow_Ads) 
                VALUES (:zname, :zdesc,:zparent, :zorder, :zvisible, :zcomment, :zads)");
                $stmt ->execute(array(
                    'zname' => $name,
                    'zdesc' => $desc,
                    'zparent' => $parent,
                    'zorder' => $order,
                    'zvisible' => $visible,
                    'zcomment' => $comment, 
                    'zads' => $ads
                    
                ));
    
                //Echo Success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Category Successfully Inserted' ."</div>";
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
        elseif ($do == 'Edit') { // Edit Category
            //Check if Get Request category ID (catid) is Numeric & Get the Integer Value Of it
            $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0 ;
            //Select All Data Based On This ID
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1" );
            //Execute the Query
            $stmt ->execute(array($catid));
            //Fetch The Data
            $row = $stmt ->fetch();
            //The Row Count
            $count = $stmt->rowCount(); //number of rows
            //If There's such Id show the Form
            if ($count > 0)  { ?> 
            <h1 class="text-center"> Edit Category </h1>
            <div class="container">
                 <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid ?>"/>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control"  autocomplete="off" required="required" placeholder="Name of The Category" value="<?php echo $row['Name'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control"   placeholder="Descriobe The Category" value="<?php echo $row['Description'] ?>" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="ordering" class="form-control"   placeholder="Number to Arrange the Categories" value="<?php echo $row['Ordering'] ?>"/>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
                                    foreach ($allCats as $cat) {
                                        echo '<option value="' . $cat['ID'] .  '"';
                                            if($row['parent'] == $cat['ID']) {
                                                echo 'selected';
                                            }
                                        echo '>' . $cat['Name'] . '</option>';
                                    }    
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visibile</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="visible-yes" type="radio" name="visibility" value="0" <?php if($row['Visibility'] == 0) {echo 'checked';} ?>  />
                                <label for="visible-yes">Yes</label>
                            </div>
                            <div>
                                <input id="visible-no" type="radio" name="visibility" value="1" <?php if($row['Visibility'] == 1) {echo 'checked';} ?> />
                                <label for="visible-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="comment-yes" type="radio" name="commenting" value="0" <?php if($row['Allow_Comment'] == 0) {echo 'checked';} ?> />
                                <label for="comment-yes">Yes</label>
                            </div>
                            <div>
                                <input id="comment-no" type="radio" name="commenting" value="1" <?php if($row['Allow_Comment'] == 1) {echo 'checked';} ?> />
                                <label for="comment-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if($row['Allow_Ads'] == 0) {echo 'checked';} ?> />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" <?php if($row['Allow_Ads'] == 1) {echo 'checked';} ?> />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Edit Category" class="btn btn-success btn-lg" />
                        </div>
                    </div>

                </form> 
            </div> 
            <?php 
            } else {
            
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>There is No Such Category Id</div>";
                redirectHome($theMsg);
                echo "</div>";
            }
        }

        elseif ($do == 'Update') {
            echo "<h1 class='text-center'> Update Category </h1>";
            echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Get Variables From The Form
                $id = $_POST['catid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $parent = $_POST['parent'];
                $order = $_POST['ordering'];
                $visibile = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads = $_POST['ads'];

                    //Update The DataBase with these infos
                    $stmt = $con ->prepare("UPDATE categories SET Name = ?, Description = ?, parent= ?, Ordering = ?, Visibility = ?, Allow_Comment = ? , Allow_Ads = ? WHERE ID = ?");
                    $stmt ->execute(array($name,$desc,$parent,$order,$visibile, $comment, $ads, $id));

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
        elseif ($do == 'Delete') {
            echo '<h1 class="text-center"> Delete Category </h1>';
            echo    '<div class="container">';
            //Check if Get Request userid is Numeric & Get the Integer Value Of it
            $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0 ;
            //Select All Data Based On This ID
            $check = checkItem('ID', 'categories', $catid);
            //If There's such Id show the FormINSERT
            if ($check > 0) {
                $stmt = $con -> prepare("DELETE FROM categories WHERE ID = :zid");
                $stmt->bindParam(":zid", $catid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted' ."</div>";
                redirectHome($theMsg, 'back');
            } else {
                $theMsg= "<div class='alert alert-danger'>" . 'This Id is not Exist !' ."</div>";
                redirectHome($theMsg);
            }
            echo '</div>';
        }
        
        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
    }

    
?> 