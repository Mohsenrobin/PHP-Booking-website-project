<?php
//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';

//checking sessions status
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
//getting the connection
$conn = getConnection();

//This varibale is used to print out the error.
$error = null;
// Check if submit button is pressed by user, so we can evaluate the fields.
if (isset($_POST['submit'])) {

  //Checking for array key exist
  if (array_key_exists('email', $_REQUEST)) {
    $emailInput = $_REQUEST['email'];
  } else {
    errorHandling("Array key dose not exist");
  }


  $username = mysqli_real_escape_string($conn, $emailInput);

  if (array_key_exists('Password', $_REQUEST)) {
    $passwordInput = $_REQUEST['Password'];
  } else {
    errorHandling("Array key dose not exist");
  }


  $password = mysqli_real_escape_string($conn, $passwordInput);

  //Making sure fields are not empty
  if (!empty($username) or !empty($password)) {

    //looking for the user in the databse.
    $sql = "SELECT * FROM customers WHERE username = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {

      mysqli_stmt_bind_param($stmt, "s", $username);

      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);
    }
    if ($result) {
      //Here we check if user has found then evaluate the password. By using password_verify method we can 
      // get the password and compare it to the password that user entered.
      while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password_hash'])) {
          $_SESSION["forename"] = $row['customer_forename'];
          $_SESSION['id'] = $row['customerID'];
          echo $_SESSION["forename"] . $_SESSION['id'] . " sdfasdf" . $row['customer_forename'];
          header('location: index.php');
        }
      }
      //Printing out an error if user or password was incorrect
      if (empty($error)) {
        $error = errorHandling("Username or password  is incorrect");
      }
    }
    //Free the result
    mysqli_free_result($result);
  }
}

//Free the result
mysqli_close($conn);


?>


<!DOCTYPE html>
<html lang="en">
<!-- Getting the Header  -->
<?php echo getHeader('Login') ?>
<!-- form wrapper -->
<div class="loginBox">
  <form class="loginForm" action="login.php" method="POST">
    <h1>Login</h1>
    <p>Please enter your username and password to sign in.</p>
    <label for="email"><b>Email</b></label><br>
    <input type="text" placeholder="Email" name="email" id="email" required>
    <br>
    <label for="psw"><b>Password</b></label><br>
    <input type="password" placeholder="Password" name="Password" id="Password" required>
    <br><br>
    <button type="submit" name="submit" class="signInbtn">Login</button>
    <br>
    <p>Don'n have an account ? create one <a href="register.php">Register</a>.</p>
  </form>
</div>
<!-- Getting the Footer  -->
<?php echo getFooter() ?>


</html>