<?php
    session_start();
    $pageTitle = 'Add New Item';
    include 'init.php';

    if (isset($_SESSION['user'])) {
    
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();
            $title = strip_tags($_POST['name']);
            $description = strip_tags($_POST['description']);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country = strip_tags($_POST['country']);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags = strip_tags($_POST['tags']);
            if(strlen($title) < 4) {
                $formErrors[] = 'Title Must be at least 4 characters';
            }
            if(strlen($description) < 10) {
                $formErrors[] = 'Description Must be at least 10 characters';
            }
            if(strlen($country) < 2) {
                $formErrors[] = 'Country must be at least 2 characters';
            }
            if(empty($price)) {
                $formErrors[] = 'You should enter the price of your ad';
            }
            if(empty($price)) {
                $formErrors[] = 'You should enter the price of your ad';
            }
            if(empty($status)) {
                $formErrors[] = 'You should enter the Status of your ad';
            }if(empty($price)) {
                $formErrors[] = 'You should choose the category of your ad';
            }

            //Check if there is no error
            if(empty($formErrors)){
                //Insert new Item to the Database                  
                $stmt = $con ->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags) 
                VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcatid, :zmemberid, :ztags )");
                $stmt ->execute(array(
                    'zname' => $title,
                    'zdesc' => $description,
                    'zprice' => '$' . $price,
                    'zcountry' => $country,
                    'zstatus' => $status, 
                    'zcatid' => $category,
                    'zmemberid' => $_SESSION['uid'],
                    'ztags' => $tags
                    
                    
                ));
                if ($stmt) {
                    
                    $successMsg =  'Item added';
                }
                
                
                
            }
        
        
        }
        
?>
        <h1 class="text-center">Add New Ad</h1>
        <div class="create-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">Create New Ad</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input 
                                                pattern =".{4,}"
                                                title ="This field required at least 4 characters"
                                                type="text" 
                                                name="name" 
                                                class="form-control live" 
                                                data-class=".live-title"  
                                                autocomplete="off"  
                                                placeholder="Name of The Item"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Description</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input 
                                                pattern =".{10,}"
                                                title ="This field required at least 10 characters"
                                                type="text" 
                                                name="description" 
                                                class="form-control live"  
                                                data-class=".live-desc" 
                                                autocomplete="off"  
                                                placeholder="Description of The Item"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Price</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input 
                                                type="text" 
                                                name="price" 
                                                class="form-control live"  
                                                data-class=".live-price" 
                                                autocomplete="off"  
                                                placeholder="Price of The Item"
                                                required 
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Country Made</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input 
                                                type="text" 
                                                name="country" 
                                                class="form-control"  
                                                autocomplete="off"  
                                                placeholder="Country of Made of The Item"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Status</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select class="form-control" name="status" required>
                                                <option value="">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Old</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Category</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select class="form-control" name="category" required>
                                                <option value="">...</option>
                                                <?php 
                                                    $cats = getAllFrom('*','categories','', '', 'ID');
                                                    foreach ($cats as $cat) {
                                                        echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] .'</option>';
                                                    }
                                                
                                                ?>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Tags</label>
                                        <div class="col-sm-9 col-md-9">
                                            <input type="text" name="tags" class="form-control"  autocomplete="off"  placeholder="Separate Tags with Comma (,)"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input type="submit" value="Add Item" class="btn btn-success btn-sm" />
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">$<span class="live-price">0</span></span>
                                    <img src="img.jpg" alt="" />
                                    <div class="caption">
                                        <h3 class="live-title">test</h3>
                                        <p class="live-desc">description</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Looping Through errors -->
                        <?php 
                            if(!empty($formErrors)) {
                                foreach($formErrors as $error) {
                                    echo '<div class="alert alert-danger">' . $error . '</div>';
                                }
                            }
                            if (isset($successMsg)) {
                                echo '<div class="alert alert-success msg success">' . $successMsg . '</div>';
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

    
    
