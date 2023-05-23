<?php
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login';

    if(isset($_SESSION['Username'])) {
        header('location: dashboard.php');
    }
   
    include 'init.php';


    // check if user coming from HTTP POST Request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['user'];
        $password = $_POST['pass'];

        //Hide Password 
        $hashedPass = sha1($password); //sha function

        // check if the user exist already

        $stmt = $con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1" );
        $stmt ->execute(array($username, $hashedPass));
        $row = $stmt ->fetch();
        $count = $stmt->rowCount(); //number of rows

        // if count > 0, this mean that the database contain record (the user)
        if ($count > 0) {
            $_SESSION['Username'] = $username ; //Register Session Name
            $_SESSION['ID'] = $row['UserID'];
            header('location: dashboard.php'); // Redirect to Dashboard Page
            exit();
            
        }

    }
  
?>
    
    <form class ="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
        <input class="form-control input-lg" type="text" name="user" placeholder= "Username" autocomplete="off"/>
        <input class="form-control input-lg" type="password" name="pass" placeholder= "Password" autocomplete="new-password"/>
        <input class="btn btn-success btn-lg btn-block"type="submit" value="Login" />

    </form>

<?php
    include $tpl . 'footer.php';
