<?php

    include 'functions.php';
    $conn = getConnection();
    $username;
    if(isset($_POST['submit'])){

        $user = mysqli_real_escape_string($conn,$_POST['email']);
        $sql = "SELECT * FROM customers WHERE username = ?";

        if($stmt = mysqli_prepare($conn,$sql)){

            mysqli_stmt_bind_param($stmt,"s",$user);
            
            mysqli_stmt_execute($stmt);

            $queryResult = mysqli_stmt_get_result($stmt);
        
        }

        if($queryResult){

            $username = mysqli_fetch_assoc($queryResult);
            unset($_SESSION["forename"]);
            unset($_SESSION['id']);
            session_start();           
            $_SESSION["forename"] = $username['customer_forename'];
            $_SESSION['id'] = $username['customerID'];
            header('location: index.php');
        }
    }
    

?>


<!DOCTYPE html>
<html lang="en">
<?php echo getHeader() ?>


<form action="signIn.php" method="POST">
  <div class="container">
    <h1>Login</h1>
    <p>Please enter your username and password to sign in.</p>
    <hr>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" id="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" required>

    <button type="submit" name="submit" class="signInbtn">Login</button>
  </div>

  <div class="container register">
    <p>Don'n have an account ? create one <a href="register.php">Register</a>.</p>
  </div>
</form>
<?php echo getFooter() ?>


</html>