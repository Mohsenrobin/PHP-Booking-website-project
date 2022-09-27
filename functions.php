<?php function mainSearchBar()
{
?>
    <form class="searchForm" action="listingPage.php" method="GET">
        <label><input type="text" placeholder="Destination" id="Destination" name="destination"></label>
        <label><input type="number" placeholder="number of guests" id="numberOfPeople" name="numberOfPeople"></label>
        <label id="fromTo">From</label>
        <label><input type="date" id="start" name="tripStart" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="2022-12-31"></label>
        <label id="fromTo">To</label>
        <label><input type="date" id="end" name="tripEnd" value="<?php echo date('Y-m-d', time() + 86400); ?>" min="<?php echo date('Y-m-d', time() + 86400); ?>" max="2022-12-31"></label>
        <button type="submit" name="submit">Submit</button>
    </form>

<?php } ?>

<?php function getConnection()
{

    define('DB_NAME', 'unn_w21067284');
    define('DB_USER', 'unn_w21067284');
    define('DB_PASSWORD', 'Western@1');
    define('DB_HOST', 'localhost');

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Can not connect to DB");

    return $conn;
} ?>

<?php function getFooter()
{ ?>

    <footer>
        <div id=footerGridContainer>
            <div>
                Copyright 2022 IR-Roof. All Rights Reserved.
            </div>
        </div>
    </footer>
    </body>

<?php } ?>


<?php function getHeader()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
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
    }

?>

    <head>
        <meta charset="utf-8" />

        <link rel='stylesheet' type="text/css" href='style.css?version=51'>

        <title>HomePage</title>
    </head>

    <body>
        <header>
            <div id=headerGridContainer>

                <div id="logoDiv">
                    <a href="index.php"><img src="img/LOGO2.jpg" border="0" alt="W3Schools" width="300" height="50"></a>
                </div>

                <div id="headerNav">
                    <ul>
                        <?php getUserFunction($user, $userID) ?>
                        <li><a href="">Credits</a></li>
                        <li><a href="">Security Report</a></li>
                        <li><a href="">Wireframes</a></li>
                        <li><a href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>

        </header>
        <main>

        <?php } ?>


        <?php function getUserFunction($user, $userID)
        {
            if (!isset($_SESSION['forename'])) {
        ?>
                <li><p>Hi Guest</p></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>

            <?php } else {

                if (isset($_POST['submit11'])) {
                    signOut();
                }
            ?>
                <li>
                <li>
                    <div class="signOutBtn"><form method="POST"><button type="submit" name="submit11">sign out</button></form></div>
                </li>
                <li><a href="profile.php"><?php echo "Hi $user user ID: $userID" ?> </a></li>
                </li>
        <?php
            }
        } ?>

        <?php function signOut()
        {
            unset($_SESSION['forename']);
            unset($_SESSION['id']);
            header('location: index.php');
        } ?>