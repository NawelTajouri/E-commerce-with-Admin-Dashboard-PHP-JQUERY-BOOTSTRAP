<?php 
session_start();
$pageTitle = 'Item By tags';
include 'init.php'; 
?>

<div class="container">
    
    <div class="row">
        <?php
            if(isset($_GET['name'])) {
                
                echo '<h1 class="text-center">Show Items By Tags</h1>';
                $tagname= $_GET['name'];
                $tagItems= getAllFrom("*", "items", "WHERE tags like '%$tagname%'","AND Approve = 1", "item_id");
            foreach($tagItems as $item) {
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
                echo "You must Enter a Tag Name";
            }
            
        
            
        ?>
    </div>
</div>
 
 <?php   include $tpl . 'footer.php'; ?>
