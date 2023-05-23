<?php
    session_start();
    $pageTitle = 'Login';

    if(isset($_SESSION['user'])) {
        header('location: index.php');
    }

    include 'init.php';
    // Start Login
    // check if user coming from HTTP POST Request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['login'])) {
            $user = $_POST['username'];
            $pass = $_POST['password'];

            //Hide Password 
            $hashedPass = sha1($pass); //sha function

            // check if the user exist already
            $stmt = $con->prepare("SELECT UserId, Username, Password FROM users WHERE Username = ? AND Password = ? " );
            $stmt ->execute(array($user, $hashedPass));
            $get = $stmt ->fetch();
            $count = $stmt->rowCount(); //number of rows

            // if count > 0, this mean that the database contain record (the user)
            if ($count > 0) {
                $_SESSION['user'] = $user ; //Register Session Name
                $_SESSION['uid'] = $get['UserId'];
                header('location: index.php'); // Redirect to Dashboard Page
                exit();
                
            }
        } else {
            $formErrors= array();

            $username = $_POST['username'];
            $password = $_POST['password'];
            $passwordconfirm = $_POST['passwordconfirm'];
            $email = $_POST['email'];
            $pass = sha1($password);
            $passConf = sha1($passwordconfirm);
            if (isset($username)) {
                
                    $filteredUser = strip_tags($username);
                    if(strlen($filteredUser) < 4) {
                        $formErrors[] ='Username must be larger than 4 characters';
                    }
            }

            if (isset($password) && isset($passwordconfirm)) {
                if(empty($_POST['password'])) {
                    $formErrors[] = 'Sorry Password Can\'t be empty!';
                }
                
                if($pass !== $passConf) {
                    $formErrors[] = 'Sorry Password Does Not match';
                }
            }

            if (isset($email)) {
                $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if(filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[]= 'Please Enter A valid Email!';
                }
            }

            //Check if there is no error proceed the Sign Up of the user
            if(count($formErrors)==0){

                //Check If User Exist in Database
                $check = checkItem("Username", "users", $username);
                if ($check == 1) {
                    $formErrors[]= 'This User is Already Exist!';
                    $formErrors[]= 'Try with another Username';
                  
                }
                else {
                //Insert new Member to the Database
               
                $stmt = $con ->prepare("INSERT INTO users(Username, Password, Email, RegStatus, Date) VALUES (:zuser, :zpass, :zmail, 0, now())");
                $stmt ->execute(array(
                    'zuser' => $user,
                    'zpass' => $pass,
                    'zmail' => $email,
                    
                    
                ));
    
                //Echo Success Message
                $successMsg = 'Congrats, You Are Successfully Registered';
            }
         }


        }

    }
?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h1>
    </h1>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Your Username" required />
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Password" required />
        </div>
        
        <input class="btn btn-primary btn-block" name="login" type="submit"  value="Login" />
    </form>

    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Your Username"  />
        </div>
        <div class="input-container">
            <input class="form-control" type="text" name="email" autocomplete="off" placeholder="Enter a Valid Email"/>
        </div>
        <div class="input-container">
            <input  class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Password"  />
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="passwordconfirm" autocomplete="new-password" placeholder="Confirm Your Password" />
        </div> 
        <input class="btn btn-primary btn-block" name="signup" type="submit"  value="SignUp" />
    </form>

    <div class="the-errors text-center">
        <?php 
            if(!empty($formErrors)) {
                foreach($formErrors as $error) {
                    echo '<div class="msg error">' . $error . '</div>';
                }
            }

            if (isset($successMsg)) {
                echo '<div class="msg success">' . $successMsg . '</div>';
            }
        ?>

    </div>
</div>

<?php
    include $tpl . 'footer.php';
?>