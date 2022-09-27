<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include 'functions.php';
$conn = getConnection();
$username;
if (isset($_POST['submit'])) {

  $user = mysqli_real_escape_string($conn, $_POST['email']);
  $sql = "SELECT * FROM customers WHERE username = ?";

  if ($stmt = mysqli_prepare($conn, $sql)) {

    mysqli_stmt_bind_param($stmt, "s", $user);

    mysqli_stmt_execute($stmt);

    $queryResult = mysqli_stmt_get_result($stmt);
  }

  if ($queryResult) {

    $username = mysqli_fetch_assoc($queryResult);
    unset($_SESSION["forename"]);
    unset($_SESSION['id']);

    $_SESSION["forename"] = $username['customer_forename'];
    $_SESSION['id'] = $username['customerID'];
    header('location: index.php');
  }
}


?>


<!DOCTYPE html>
<html lang="en">
<?php echo getHeader() ?>

<div class="loginBox">
  <form class="loginForm" action="login.php" method="POST">
    <h1>Login</h1>
    <p>Please enter your username and password to sign in.</p>
    <label for="email"><b>Email</b></label><br>
    <input type="text" placeholder="Email" name="email" id="email" required>
    <br>
    <label for="psw"><b>Password</b></label><br>
    <input type="password" placeholder="Password" name="psw" id="psw" required>
    <br><br>
    <button type="submit" name="submit" class="signInbtn">Login</button>
    <br>
    <p>Don'n have an account ? create one <a href="register.php">Register</a>.</p>
  </form>
</div>
<?php echo getFooter() ?>


</html>