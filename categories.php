<?php 
session_start();
$pageTitle = 'Categories';
include 'init.php'; 
?>

<div class="container">
    <h1 class="text-center">Show Category Items</h1>
    <div class="row">
        <?php
            //$categoryid = (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) ? intval($_GET['pageid']) : 0 ;
            if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
                $categoryid = intval($_GET['pageid']);
                $allItems= getAllFrom("*", "items", "WHERE Cat_ID = {$categoryid}","AND Approve = 1", "item_id");
            foreach($allItems as $item) {
                echo '<div class="col-sm-6 col-md-4">';
                    echo '<div class="thumbnail item-box">';
                        echo '<span class="price-tag">' . $item['Price'] .'</span>';
                        echo '<img src="img.jpg" alt="" />';
                        echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid='. $item['item_id'] .' ">'. $item['Name'] .'</a></h3>';
                            echo '<p>'. $item['Description'] .'</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
            }
            else {
                echo "You must choose id of category ID";
            }
            
        
            
        ?>
    </div>
</div>
 
 <?php   include $tpl . 'footer.php'; ?>
