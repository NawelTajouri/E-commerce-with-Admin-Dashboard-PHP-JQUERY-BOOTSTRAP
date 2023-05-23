<?php
   /*
    ** Get All Function v2.0
    ** Function To Get All From Database [ Users, Items, Comments ]
    */
    function getAllFrom($field, $tableName, $where = NULL, $and=NULL, $orderfield = NULL, $ordering = "DESC"){
        global $con;
        
        $getAll = $con ->prepare("SELECT $field FROM $tableName $where $and ORDER BY $orderfield $ordering");
        $getAll -> execute();
        $all = $getAll->fetchAll();
        return $all;
    }

    /*
    ** Title Function v1.0
    ** Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $pageTitle And Echo Default Title for Other Pages
    */

    function getTitle() {
        global $pageTitle; //Accessible from Any Page
        if (isset($pageTitle)) {
            echo $pageTitle;
        }
        else {
            echo 'Default';
        }
    }
    /*
    ** Redirect Function v2.0
    ** Redirect Function [ This Function Accept Parameters ]
    ** $theMsg = Echo the  Message [ex: error, success, Warning]
    ** $seconds = Seconds Before Redirecting
    ** $url = The link to redirect to
    */
    function redirectHome($theMsg, $url = null, $seconds = 3) {
        if ($url === null){
            $url = 'index.php';
            $link = 'HomePage';
        } else {
            $url = isset($_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
            $link = isset($_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] !== '' ? 'Previous Page' : 'HomePage';
        }
        
        echo $theMsg;
        echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
        header("refresh:$seconds;url=$url");
        exit();
    }

    /*
    ** Check Items Function v1.0
    ** Function to Check Item In Database [ Function Accept Parameters ]
    ** $select =  The Item To select [ex: select user, select item, select category ]
    ** $from = The Table to select from [ex: users, items, categories]
    ** $value = The value of $select [ex: box, electronics ]
    */

    function checkItem($select, $from, $value) {
        global $con;
        $statement = $con -> prepare("SELECT $select FROM $from WHERE $select = ?");
        $statement -> execute(array($value));
        $count = $statement -> rowCount();
        return $count;

    }

    /*
    ** Count Number of Items Function v1.0 
    ** Function To Count Number of Items
    ** $item = Item to count
    ** $table = the table to choose from
    */

    function countItems($item, $table) {
        global $con;
        $stm2 = $con -> prepare("SELECT COUNT($item) FROM $table");
        $stm2 -> execute();
        return $stm2->fetchColumn();
    }

    /*
    ** Get Latest Records Function v1.0
    ** Function To Get Latest Items From Database [ Users, Items, Comments ]
    ** $select =  Field to select
    ** $table =  Table to choose from
    ** $limit = Number of records to select
    */
    function getLatest($select, $table, $order, $limit = 5){
        global $con;
        $getStmt = $con ->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        $getStmt -> execute();
        $rows = $getStmt->fetchAll();
        return $rows;
    }