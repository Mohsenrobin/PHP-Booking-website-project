<?php
//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';

//getting the connection
$conn = getConnection();
$results = array();
$selected = "";

//checking sessions status
if (session_status() === PHP_SESSION_NONE) {
    session_start();

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
}
//Check if user is not signed in, unset sessions and send it to the login page 
if (!isset($_SESSION['id'])) {
    unset($_SESSION['numberOfPeople']);
    unset($_SESSION['start_date']);
    unset($_SESSION['end_date']);
    header('location: login.php');
}

// Getting data from accommodation , booking and images tables to show all booked properties
$sql = "SELECT * FROM  accommodation a, booking b, images i
    WHERE customerID = ? and a.accommodationID = b.accommodationID and a.accommodationID = i.accommodationID ";


//Prepare statement
if ($stmt = mysqli_prepare($conn, $sql)) {

    mysqli_stmt_bind_param($stmt, "i", $userID);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    //Storing the result of the sql query
    $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

//Checking for deleteButton
if (isset($_POST['deleteButton'])) {

    //Stroing the id of the booking that needs to be removed.
    $selected = mysqli_real_escape_string($conn, $_REQUEST['id_to_delete']);

    //Sql query to delete the Booking
    $sql = "DELETE FROM  booking WHERE customerID = ? and bookingID = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {

        mysqli_stmt_bind_param($stmt, "ii", $userID, $selected);

        mysqli_stmt_execute($stmt);

        //
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
    //Refreshing the page after the item is deleted.
    header('location: profile.php');

    //Free the result
    mysqli_free_result($result);
}
//Free the resources
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<!-- Getting the Header  -->
<?php echo getHeader('User Profile'); ?>

<!-- The grid which takes care of the profile elements  -->
<div id=profileContainer>

    <!-- Printing out all booked accommodations  -->
    <div class="myBookings">

        <?php
        if (empty($results)) {
            errorHandling("You haven't booked any property yet");
        }
        foreach ($results as $res) { ?>
            <div class="searchResultProfile">
                <img src="assets/images/<?php echo $res['imageName']; ?>" border="0" alt="W3Schools" width="250" height="175">
                <!-- Printing out all details  -->
                <div class="descriptionProfile">
                    <p class="listingNameProfile"><?php echo $res['accommodation_name']; ?></p>
                    <p class="locationProfile"><?php echo $res['location']; ?><br></p>
                    <div class="listingDetails2Profile">Check-In : <?php echo $res['start_date']; ?><br>
                        Check-Out : <?php echo $res['end_date']; ?><br></div>
                    <p id="priceProfile">£<?php echo $res['price_per_night'] ?></p>
                    <p id="perNightProfile"> per night</p><br><br>
                    <p id="totalProfile"> Total price </p>
                    <p id="totalPriceProfile">£<?php echo $res['total_booking_cost'] ?></p>
                    <span>
                        <!-- The form that is responsible for deleting the item  -->
                        <form id="deleteMyBooking" action="profile.php" method="POST">
                            <input type="hidden" name="id_to_delete" value="<?php echo $res['bookingID'] ?>">
                            <input id="button" type="submit" name="deleteButton" value="Cancel Reservation">
                        </form>
                    </span>
                </div>

            </div>
        <?php } ?>
    </div>
</div>

<!-- Getting the Footer  -->
<?php echo getFooter(); ?>


</html>