<?php
//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';

//checking sessions status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
//Getting the connection
$conn = getConnection();


//Checking if the user is signed in
if (isset($_SESSION['id'])) {

    //Requesting the Accommodation ID which is passed from the listing page
    if (isset($_REQUEST['id'])) {

        //Checking for array key
        if (array_key_exists('id', $_REQUEST)) :
            $idInput = $_REQUEST['id'];
        endif;
        //Storing the Accommodation id inside the variable
        $accommodationId = mysqli_real_escape_string($conn, $idInput);

        //Stroing the accommodation id inside the session global varibale in order to be send to the profile page
        $_SESSION['accommodationId'] = $accommodationId;

        //Getting start and end date from listing page via session global variable.
        $start_date = mysqli_real_escape_string($conn, $_SESSION['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_SESSION['end_date']);

        //Getting data from accommodation and images table to show to the user
        $sql = "SELECT * FROM accommodation a, images i WHERE a.accommodationID = ? and a.accommodationID = i.accommodationID";

        //preapre statemnet
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $accommodationId);

            mysqli_stmt_execute($stmt);

            $queryResult = mysqli_stmt_get_result($stmt);

            //Storing the query result into the accommodation varibable
            if ($queryResult) {
                $accommodation = mysqli_fetch_assoc($queryResult);
                mysqli_free_result($queryResult);
            }
        }

        //Calculating the total price based on period of stay and the price
        $price = mysqli_real_escape_string($conn, $accommodation['price_per_night']);
        $totalPrice = ((strtotime($end_date) - strtotime($start_date)) / 86400) * $price;
        $_SESSION['totalPrice'] = $totalPrice;
        //Storing total price in the session global varibale.

        
    }

    //Checking if bookButton is pressed by user
    if (isset($_REQUEST['submit'])) {
        if (

            //Checking if essential session elements are set
            isset($_SESSION['numberOfPeople']) or
            isset($_SESSION['start_date']) or isset($_SESSION['end_date'])
        ) {


            //Cheking for array key exist
            if (array_key_exists('id_to_save', $_REQUEST)) :
                $idToSaveInput = $_REQUEST['id_to_save'];
            endif;

            $idToSave = mysqli_real_escape_string($conn, $idToSaveInput);

            if (array_key_exists('bookingNote', $_REQUEST)) :
                $noteInput = $_REQUEST['bookingNote'];
            endif;

            $note = mysqli_real_escape_string($conn, $noteInput);

            //Getting data from session global variable
            $numberOfPeople = mysqli_real_escape_string($conn, $_SESSION['numberOfPeople']);
            $start_date = mysqli_real_escape_string($conn, $_SESSION['start_date']);
            $end_date = mysqli_real_escape_string($conn, $_SESSION['end_date']);
            $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
            $totalPrice =  mysqli_real_escape_string($conn, $_SESSION['totalPrice']);


            //Inserting data to the booking table 
            $sql = "INSERT INTO booking (accommodationID, customerID, `start_date`, end_date,
            num_guests, total_booking_cost, booking_notes) VALUES (?,?,?,?,?,?,?)";


            if ($stmt = mysqli_prepare($conn, $sql)) {
                echo "sadfasdfasdgasgd";
                mysqli_stmt_bind_param(
                    $stmt,
                    "iissids",
                    $idToSave,
                    $userId,
                    $start_date,
                    $end_date,
                    $numberOfPeople,
                    $totalPrice,
                    $note
                );

                $result = mysqli_stmt_execute($stmt);
                //Sending user to the profile page.
                header('location: profile.php');
            }
        } else {
            //For any reason if data is not passed through we need to send back user to the listing page
            errorHandling("information of your booking is lost
            You will be send to the Listing page in 3 seconds");
            header('Refresh:3 url=listingPage.php');
        }
    }
} else {
    //If user is not signed in, we will unset all the information that are passed through this page and 
    //send user to the login page.
    unset($_SESSION['numberOfPeople']);
    unset($_SESSION['start_date']);
    unset($_SESSION['end_date']);
    header('location: login.php');
    echo "Please login first";
}

//free resources
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<!-- Getting the Header  -->
<?php echo getHeader("Booking Details") ?>
<!-- Detail page content wrapper  -->
<div class="detailsContent">
    <!-- This grid takes care of the elements  -->
    <div id=detailsGridContainer>
        <!-- Loading the accommodation image  -->
        <div id="image1">
            <img src="assets/images/<?php echo $accommodation['imageName']; ?>" border="0" alt="" width="900" height="600">
        </div>

        <!-- Details of the property and highlights  -->
        <div class="details1">
            <p class="details1Name"><?php echo $accommodation['accommodation_name']; ?></p>
            <p class="details1location"><?php echo $accommodation['location']; ?><br></p>
            <div class="details1Details">
                <!-- <?php echo $accommodation['shortDesc']; ?>  <br> <br>-->
                <h5><?php echo $accommodation['accommodationType']; ?></h5>
                <h3>Property highlights</h3>
                <ul>
                    <li>Free Wifi</li>
                    <li>Air conditioning</li>
                    <li>Refrigerator</li>
                    <li>Refrigerator</li>
                    <li>Non-smoking</li>
                </ul>
            </div>
            <div><span>
                    <p id="details1price">£<?php echo $accommodation['price_per_night'] ?></p>
                    <p id="details1perNight"> per night</p><br>
                    <p id="total"> Total price </p>
                    <p id="totalPrice">£<?php echo $totalPrice ?></p>
                </span></div>
        </div>

        <!-- Property description  -->
        <div id="details2">
            <p class="details2Details"><?php echo $accommodation['description']; ?></p>
        </div>

        <!-- This form is responsible to send the notes to the booking details. -->
        <form class="book" action="details.php" method="POST">
            <input type="hidden" name="id_to_save" value="<?php echo $accommodation['accommodationID'] ?>">
            <label for="forename"><b>Notes:</b></label></br>
            <textarea id="notes" name="bookingNote" cols="40" rows="5"></textarea><br>
            <button type="submit" name="submit">Make a reservation</button>
        </form>
    </div>
</div>
<!-- Getting the Footer  -->
<?php echo getFooter() ?>

</body>

</html>