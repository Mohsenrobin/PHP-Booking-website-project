<?php
include 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$forename = $surename = $cusUsername = $passeword = $postcode = $addressln1 = $addressln2 = $dob = '';


$error = array();
$conn = getConnection();
if (isset($_POST['submit'])) {


    if (array_key_exists('forename', $_REQUEST)) :
        $forenameInput = $_REQUEST['forename'];
    endif;

    $forename = mysqli_real_escape_string($conn, $forenameInput);

    if (array_key_exists('surename', $_REQUEST)) :
        $surenameInput = $_REQUEST['surename'];
    endif;

    $surename = mysqli_real_escape_string($conn, $surenameInput);

    if (array_key_exists('cusUsername', $_REQUEST)) :
        $cusUsernameInput = $_REQUEST['cusUsername'];
    endif;

    $cusUsername = mysqli_real_escape_string($conn, $cusUsernameInput);

    if (array_key_exists('password', $_REQUEST)) :
        $passwordInput = $_REQUEST['password'];
    endif;

    $password = mysqli_real_escape_string($conn, $passwordInput);

    if (array_key_exists('password-repeat', $_REQUEST)) :
        $passwordRepeatInput = $_REQUEST['password-repeat'];
    endif;

    $passwordRepeat = mysqli_real_escape_string($conn, $passwordRepeatInput);

    if (array_key_exists('postcode', $_REQUEST)) :
        $postcodeInput = $_REQUEST['postcode'];
    endif;

    $postcode = mysqli_real_escape_string($conn, $postcodeInput);

    if (array_key_exists('addressln1', $_REQUEST)) :
        $addressln1Input = $_REQUEST['addressln1'];
    endif;

    $addressln1 = mysqli_real_escape_string($conn, $addressln1Input);

    if (array_key_exists('addressln2', $_REQUEST)) :
        $addressln2Input = $_REQUEST['addressln2'];
    endif;

    $addressln2 = mysqli_real_escape_string($conn, $addressln2Input);

    if (array_key_exists('dob', $_REQUEST)) :
        $dobInput = $_REQUEST['dob'];
    endif;

    $dob = mysqli_real_escape_string($conn, $dobInput);

    if (
        !empty($forename) or !empty($surename) or !empty($cusUsername) or !empty($passewordInput) or !empty($passwordRepeat) or
        !empty($postcode) or !empty($addressln1) or !empty($addressln2) or !empty($dob)
    ) {

        if ($password == $passwordRepeat) {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);




            $sql = "INSERT INTO customers (username, password_hash, customer_forename, customer_surname,
        customer_postcode, customer_address1, customer_address2, date_of_birth)
         VALUES (?,?,?,?,?,?,?,?)";

            if ($stmt = mysqli_prepare($conn, $sql)) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssssss",
                    $cusUsername,
                    $hashedPassword,
                    $forename,
                    $surename,
                    $postcode,
                    $addressln1,
                    $addressln2,
                    $dob
                );
                $queryResult = mysqli_stmt_execute($stmt);
            }

            $cusUsername = mysqli_real_escape_string($conn, $cusUsername);

            $sql = "SELECT * FROM customers WHERE username = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {

                mysqli_stmt_bind_param($stmt, "s", $cusUsername);

                mysqli_stmt_execute($stmt);

                $queryResult = mysqli_stmt_get_result($stmt);
            }

            if ($queryResult) {
                unset($_SESSION["forename"]);
                unset($_SESSION['id']);
                $user = mysqli_fetch_assoc($queryResult);
                $_SESSION["forename"] = $user['customer_forename'];
                $_SESSION['id'] = $user['customerID'];
                echo $_SESSION["forename"] . "   " . $_SESSION['id'];
                header('location: index.php');
            }
            mysqli_free_result($result);
        }
    }
}
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<?php echo getHeader('Register') ?>
<div class="registerBox">
    <form class="registerForm" action="register.php" method="POST">
        <h1>Register</h1>
        <p>Please fill in this form to create an account.</p>
        <label for="forename"><b>Forename</b></label></br>
        <input type="text" placeholder="Enter forename" name="forename" id="forename" pattern="[A-Za-z]{2}" title="At least two letters" required></br>

        <label for="surename"><b>Surname</b></label></br>
        <input type="text" placeholder="Enter surename" name="surename" id="surename" pattern="[A-Za-z]{2}" title="At least two letters"  required></br>

        <label for="cusUsername"><b>Customer Username</b></label></br>
        <input type="email" placeholder="Enter Email" name="cusUsername" id="cusUsername"  required></br>

        <label for="dob"><b>Date of birth</b></label></br>
        <input type="date" placeholder="Enter date of birth" name="dob" id="dob" value="2022-01-01" min="1930-01-01" max="2008-12-30" required></br>

        <label for="Postcode"><b>Postcode</b></label></br>
        <input type="postcode" placeholder="postcode" name="postcode" id="postcode" pattern="[a-z0-9]{6}" title="Postcode must be at least 6 chars, example EX16TL" required></br>

        <label for="addressln1"><b>Address Line 1</b></label></br>
        <input type="address line1" placeholder="Enter Address line 1" name="addressln1" id="addressln1"  required></br>

        <label for="addressln2"><b>Address Line 2</b></label></br>
        <input type="address line2" placeholder="Enter Address line 2" name="addressln2" id="addressln2"  required></br>

        <label for="password"><b>Password</b></label></br>
        <input type="password" placeholder="Enter Password" name="password" id="password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required></br>

        <label for="password-repeat"><b>Repeat Password</b></label></br>
        <input type="password" placeholder="Repeat Password" name="password-repeat" id="password-repeat" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required></br>
        <hr>

        <p>By creating an account you agree to our <a href="privacyPolicy.html">Terms & Privacy</a>.</p>
        <button type="submit" name="submit" class="registerbtn">Register</button>
    </form>
</div>
<div class="container signin">
    <p>Already have an account? <a href="login.php">Sign in</a>.</p>
</div>

<?php echo getFooter() ?>


</html>