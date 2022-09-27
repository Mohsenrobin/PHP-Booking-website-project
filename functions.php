 <?php function setSearchBar()
    { ?>
     <form class="searchForm" action="listingPage.php" method="GET">
         <input type="text" placeholder="Destination" id="Destination" name="destination">
         <input type="number" placeholder="number of guests" id="numberOfPeople" name="numberOfPeople">
         From <input type="date" id="start" name="trip-start" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="2022-12-31">
         To <input type="date" id="end" name="trip-end" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="2022-12-31">
         <button type="submit" name="submit">Submit</button>
     </form>
 <?php } ?>

 <?php function getConnection()
    {

        define('DB_NAME', 'unn_w21067284');
        define('DB_USER', 'root');
        define('DB_PASSWORD', '');
        define('DB_HOST', 'localhost');

        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Can not connect to DB");

        return $conn;
    } ?>

 <?php function getFooter()
    {

    $footer = <<<FOOT
            <footer>
                <main>
                    <div id =footerGridContainer>
                    
                </div>


            </footer>
        </body>
    FOOT;

    return $footer;
    
    } ?>


 <?php function getHeader()
    {
        session_start();

        $user = $_SESSION['forename'] ?? 'Guest';
        $userID =  $_SESSION['id'] ?? null;
        $getuser = getUserFunction($userID);

        $header = <<<HEAD
        <head>
            <meta charset="utf-8" />

            <link rel='stylesheet' type="text/css" href='style/headerStyle.css?version=51'>
            <link rel='stylesheet' type="text/css" href='style/mainStyle.css?version=51'>
            <link rel='stylesheet' type="text/css" href='style/bodyStyle.css?version=51'>
            <link rel='stylesheet' type="text/css" href='style/footerStyle.css?version=51'>
            <link rel='stylesheet' type="text/css" href='style/listingPage.css?version=51'>

            <title>HomePage</title>
        </head>

        <body>
            <header>
                <div id =headerGridContainer>

                    <div id="logoDiv">
                    <a href="index.php">Logo</a>
                    </div id="greetingDiv">

                    <div id="headerNav">
                        <ul>
                            <li><a href="">Hi $user user ID : $userID </a>  </li>
                            $getuser
                            <li><a href="">Credits</a></li>
                            <li><a href="">Wireframes</a></li>
                            <li><a href="index.php">Home</a></li>


                        </ul>
                    </div>
                </div>

            </header>

    HEAD;
        return $header;
    } ?>


 <?php function getUserFunction($input2)
    {
        if (empty($input2)) {
            $gg = <<<hh
                <li><a href="register.php">Register</a></li>
                <li><a href="signIn.php">Login</a></li>
            hh;
        } else {
            if (isset($_POST['submit11'])) {
                signOut();
            } // SUBMIT RO AZAD KON CODE KAR MIKONE
            $gg = <<<hh
                <li><button type="submit" name="submit11">Submit</button></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="signIn.php">Login</a></li>
            hh;
        }
        return $gg;
    } ?>

 <?php function signOut()
    {
        unset($_SESSION['forename']);
        unset($_SESSION['id']);
    } ?>