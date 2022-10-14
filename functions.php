<!-- This function is used for the main search form which appears on the main page and also Listing page-->
<?php function mainSearchBar()
{

    // checks if the submit button is pushed
    if (isset($_POST['submit'])) {
    }
?>
    <form class="searchForm" action="listingPage.php" method="POST">
        <label><input type="text" placeholder="Destination" id="Destination" name="destination" required></label>
        <label><input type="number" placeholder="number of guests" id="numberOfPeople" name="numberOfPeople" required></label>
        <label id="fromTo">From</label>
        <label><input type="date" id="start" name="tripStart" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="2022-12-31" required></label>
        <label id="fromTo">To</label>
        <!-- the default value for the end date is : start data + 1 day. -->
        <label><input type="date" id="end" name="tripEnd" value="<?php echo date('Y-m-d', time() + 86400); ?>" min="<?php echo date('Y-m-d', time() + 86400); ?>" max="2022-12-31" required></label>
        <button type="submit" name="submit">Submit</button>
    </form>

<?php } ?>

<!-- This function returns the connection -->
<?php function getConnection()
{

    define('DB_NAME', 'unn_w21067284');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');
    //There is a condition here which returns the connection or printing out the failture.
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die(errorHandling('Can not connect to the database'));

    return $conn;
} ?>

<!-- This Function will set the footer of the each page  -->
<?php function getFooter()
{ ?>
    </main>
    <footer>
        <div id=footerContainer>
            <p>
                Copyright 2022 IR-Roof. All Rights Reserved.
            </p>
        </div>
    </footer>
    </body>

<?php } ?>

<!-- This function is mainly for greeting the user and activating signOut button if the user is singed in  -->
<?php function getUserFunction($user)
{
    // Here we check if user signed in 
    if (!isset($_SESSION['forename'])) {
?>
        <li>
            <p>Hi Guest</p>
        </li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>

    <?php } else {

        if (isset($_POST['signOutButton'])) {
            signOut();
        }
    ?>
        <li>
        <li>
            <div class="signOutBtn">
                <form method="POST"><button type="submit1" name="signOutButton">sign out</button></form>
            </div>
        </li>
        <li>
            <div class="userGreeting"><a href="profile.php"><?php echo "Hi $user" ?> </a></div>
        </li>
        </li>
<?php
    }
} ?>

<!-- signOut function to destroy all sessions  -->
<?php function signOut()
{
    session_destroy();

    //temp  del 23/09 21:06
    // unset($_SESSION['forename']);
    // unset($_SESSION['id']);
    header('location: index.php');
} ?>

<!-- This function is used to printout the error on the top of the page  -->
<?php function errorHandling($errorText)
{ ?>
    <div class='errorText'>
        <?php echo $errorText ?>
    </div>

<?php } ?>

<!-- This function will set the header of the page which is common among all pages  -->
<?php function getHeader($title)
{
    // first check if session is not started, we start it here on the top of the page 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    //Here we check if session is not set yet, we give the user a deafult value as the guest until they sign in
    $user = $userID = null;
    if (!isset($_SESSION['forename'])) {
        $user = 'Guest';
    } else {
        $user = $_SESSION['forename'];
    }
    if (!isset($_SESSION['id'])) {
        $userID = null;
    } else {
        $userID = $_SESSION['id'];
    } ?>

    <head>
        <meta charset="utf-8" />

        <link rel='stylesheet' type="text/css" href='assets/stylesheets/style.css?version=51'>
        <!-- $title will be set wherever we call the header function  -->
        <title><?php echo $title ?></title>
    </head>

    <body>
        <header>
            <!-- header grid which takes care of header elements -->
            <div id=headerGridContainer>

                <div id="logoDiv">
                    <a href="index.php"><img src="assets/images/Logo.png" border="0" alt="W3Schools" width="200" height="40"></a>
                </div>

                <div id="websiteBio">Take your faimly to a unique trip to Iran</div>

                <div id="headerNav">
                    <ul>
                        <li><?php getUserFunction($user) ?></li>
                        <li><a href="credits.php">Credits</a></li>
                        <li><a href="securityReport.php">Security Report</a></li>
                        <li><a href="wireframes.php">Wireframes</a></li>
                        <li><a href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>

        </header>
        <main>
        <?php
    }
        ?>

        <!-- This function is used to get the images for the explore section -->
        <?php function getImage($imageUrl, $section, $text, $city)
        { ?>
            <div class=<?php echo $section ?>>
                <img src=<?php echo $imageUrl ?> border="0" alt="image" width="500" height="355">
                <a href="listingPage.php?destination=<?php echo $city ?>&numberOfPeople=2&tripStart=<?php echo date('Y-m-d'); ?>&tripEnd=<?php echo date('Y-m-d', time() + 86400); ?>">
                    <div class="img__description">
                        <div class="text">
                            <h4><?php echo $city ?></h4>
                            <?php echo $text ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php }   ?>
