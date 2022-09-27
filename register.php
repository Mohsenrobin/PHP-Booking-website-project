<?php
//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';

//checking sessions status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// storing connection to the conn variable 
$conn = getConnection();

//Checking if user pushed the submit button
if (isset($_POST['submit'])) {


    // Checking for all array keys and stroing them to their varibales
    if (array_key_exists('forename', $_REQUEST)) {
        $forenameInput = $_REQUEST['forename'];
    } else {
        errorHandling("Array key dose not exist");
    }

    $forename = mysqli_real_escape_string($conn, $forenameInput);

    if (array_key_exists('surename', $_REQUEST)) {
        $surenameInput = $_REQUEST['surename'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $surename = mysqli_real_escape_string($conn, $surenameInput);

    if (array_key_exists('cusUsername', $_REQUEST)) {
        $cusUsernameInput = $_REQUEST['cusUsername'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $cusUsername = mysqli_real_escape_string($conn, $cusUsernameInput);

    if (array_key_exists('password', $_REQUEST)) {
        $passwordInput = $_REQUEST['password'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $password = mysqli_real_escape_string($conn, $passwordInput);

    if (array_key_exists('password-repeat', $_REQUEST)) {
        $passwordRepeatInput = $_REQUEST['password-repeat'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $passwordRepeat = mysqli_real_escape_string($conn, $passwordRepeatInput);

    if (array_key_exists('postcode', $_REQUEST)) {
        $postcodeInput = $_REQUEST['postcode'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $postcode = mysqli_real_escape_string($conn, $postcodeInput);

    if (array_key_exists('addressln1', $_REQUEST)) {
        $addressln1Input = $_REQUEST['addressln1'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $addressln1 = mysqli_real_escape_string($conn, $addressln1Input);

    if (array_key_exists('addressln2', $_REQUEST)) {
        $addressln2Input = $_REQUEST['addressln2'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $addressln2 = mysqli_real_escape_string($conn, $addressln2Input);

    if (array_key_exists('dob', $_REQUEST)) {
        $dobInput = $_REQUEST['dob'];
    } else {
        errorHandling("Array key dose not exist");
    }
    $dob = $dobInput;

    //Checking for variables to not be empty
    if (
        !empty($forename) or !empty($surename) or !empty($cusUsername) or !empty($passewordInput) or !empty($passwordRepeat) or
        !empty($postcode) or !empty($addressln1) or !empty($addressln2) or !empty($dob)
    ) {

        //Checking id passwords are the same
        if ($password == $passwordRepeat) {


            //Using password_hash function to create a hashed password to be stored to the database.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);



            //Sql query for storing the user information to the custmores table
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

            //Stroing email address to the variable in order to get their details for the second query
            $cusUsername = mysqli_real_escape_string($conn, $cusUsername);

            //I used a separate sql to get the user right after we stored their data to make sure we have succesfully stored the user data into database. 
            $sql = "SELECT * FROM customers WHERE username = ?";

            //Prepare statemnet
            if ($stmt = mysqli_prepare($conn, $sql)) {

                mysqli_stmt_bind_param($stmt, "s", $cusUsername);

                mysqli_stmt_execute($stmt);

                //Storing the user data into queryResult
                $queryResult = mysqli_stmt_get_result($stmt);
            }

            // Unset any prevoius data that stored in the session global, then store the current user data into it.
            if ($queryResult) {
                unset($_SESSION["forename"]);
                unset($_SESSION['id']);
                $user = mysqli_fetch_assoc($queryResult);
                $_SESSION["forename"] = $user['customer_forename'];
                $_SESSION['id'] = $user['customerID'];
                header('Refresh:2 url=index.php');
            } else {
                //In case connection lost, We send user to the login page to sign in.
                errorHandling("Something went wrong please try login. you will be send to the login page in 3 seconds");
                header('Refresh: 3, url=index.php');
            }
            //Free results
            mysqli_free_result($result);
        } else {
            errorHandling("Password fields must be the same");
        }
    } else {
        errorHandling("At least one field is empty");
    }
}
//Free resources
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<!-- Getting the Header  -->
<?php echo getHeader('Register') ?>
<!-- wrapper of the register content  -->
<div class="registerBox">
    <!-- Register form  -->
    <form class="registerForm" action="register.php" method="POST">
        <h1>Register</h1>
        <p>Please fill in this form to create an account.</p>

        <hr>
        <!-- First part: Personal data  -->
        <label for="forename"><b>Personal information</b></label></br>
        <input type="text" placeholder="Enter forename" name="forename" id="forename" pattern="[A-Za-z]{2}" title="At least two letters" required>

        <input type="text" placeholder="Enter surename" name="surename" id="surename" pattern="[A-Za-z]{2}" title="At least two letters" required></br>

        <input type="email" placeholder="Enter Email" name="cusUsername" id="cusUsername" required>

        <input type="date" placeholder="Enter date of birth" name="dob" id="dob" value="2022-01-01" min="1930-01-01" max="2008-12-30" required></br>

        <hr>
        <!-- Second part: New passwords  -->
        <label for="password"><b>Set up new password</b></label></br>
        <input type="password" placeholder="Enter Password" name="password" id="password" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>

        <input type="password" placeholder="Repeat Password" name="password-repeat" id="password-repeat" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required></br>

        <hr>
        <!-- Third part: Address  -->
        <label for="Postcode"><b>Address</b></label></br>
        <input type="postcode" placeholder="postcode" name="postcode" id="postcode" pattern="[a-z0-9]{6}" title="Postcode must be at least 6 chars, example EX16TL" required>

        <input type="address line1" placeholder="Enter Address line 1" name="addressln1" pattern="[a-z0-9]" title="please only use numbers and the letters" id="addressln1" required></br>

        <input type="address line2" placeholder="Enter Address line 2" name="addressln2" pattern="[a-z0-9]" title="please only use numbers and the letters"  id="addressln2" required></br>

        <hr>

        <button type="submit" name="submit" class="registerbtn">Register</button>
    </form>
</div>
<!-- Sending users who have accounts to the login page  -->
<div class="container signin">
    <p>Already have an account? <a href="login.php">Sign in</a>.</p>
</div>

<!-- Getting the Footer  -->
<?php echo getFooter() ?>


</html>