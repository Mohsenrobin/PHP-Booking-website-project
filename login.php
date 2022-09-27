<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include 'functions.php';
$conn = getConnection();
if (isset($_POST['submit'])) {

  if (array_key_exists('email', $_REQUEST)) :
    $emailInput = $_REQUEST['email'];
  endif;

  $username = mysqli_real_escape_string($conn, $emailInput);

  if (array_key_exists('Password', $_REQUEST)) :
    $passwordInput = $_REQUEST['Password'];
  endif;

  $password = mysqli_real_escape_string($conn, $passwordInput);


  if (!empty($username) or !empty($password)) {


    $sql = "SELECT * FROM customers WHERE username = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {

      mysqli_stmt_bind_param($stmt, "s", $username);

      mysqli_stmt_execute($stmt);

      $result = mysqli_stmt_get_result($stmt);
    }
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password_hash'])) {
          $_SESSION["forename"] = $row['customer_forename'];
          $_SESSION['id'] = $row['customerID'];
          echo $_SESSION["forename"] . $_SESSION['id'] . " sdfasdf" . $row['customer_forename'];
          header('location: index.php');
        } else {
          echo "password is incorrect or ";
        }
      }  
        echo "user not find";
    }
    mysqli_free_result($result);
  }
}

mysqli_close($conn);


?>


<!DOCTYPE html>
<html lang="en">
<?php echo getHeader('Login') ?>

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
<?php echo getFooter() ?>


</html>