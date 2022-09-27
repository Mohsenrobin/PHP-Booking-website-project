<?php

include 'functions.php';
$conn = getConnection();
$results = array();
$selected = "";
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
if (isset($_SESSION['id'])) {
    if (isset($_REQUEST['bookButton'])) {

        if (
            //temp del 23/09 13:14
            // isset($_SESSION['accommodation']) or 
            isset($_SESSION['numberOfPeople']) or
            isset($_SESSION['start_date']) or isset($_SESSION['end_date'])
        ) {

            //temp del 23/09 13:14
            // print_r($_SESSION['accommodation']);

            if (array_key_exists('id_to_save', $_REQUEST)) :
                $idToSaveInput = $_REQUEST['id_to_save'];
            endif;
        
            $idToSave = mysqli_real_escape_string($conn, $idToSaveInput);

            if (array_key_exists('bookingNote', $_REQUEST)) :
                $noteInput = $_REQUEST['bookingNote'];
            endif;
        
            $note = mysqli_real_escape_string($conn, $noteInput);

            $numberOfPeople = mysqli_real_escape_string($conn, $_SESSION['numberOfPeople']);
            $start_date = mysqli_real_escape_string($conn, $_SESSION['start_date']);
            $end_date = mysqli_real_escape_string($conn, $_SESSION['end_date']);
            $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
            $totalPrice = $_SESSION['totalPrice'];


            $sql = "INSERT INTO booking (accommodationID, customerID, `start_date`, end_date,
            num_guests, total_booking_cost, booking_notes) VALUES (?,?,?,?,?,?,?)";


            if ($stmt = mysqli_prepare($conn, $sql)) {

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
                //temp del 23/09 13:14
                // unset($_SESSION['accommodation']);
                unset($_SESSION['numberOfPeople']);
                unset($_SESSION['start_date']);
                unset($_SESSION['end_date']);
            }
        } else {
            echo "information of your booking is lost";
        }
    }
} else {
    header('location: login.php');
    echo "Please login first";
}


$sql = "SELECT * FROM  accommodation a, booking b, images i
    WHERE customerID = ? and a.accommodationID = b.accommodationID and a.accommodationID = i.accommodationID ";
if ($stmt = mysqli_prepare($conn, $sql)) {

    mysqli_stmt_bind_param($stmt, "i", $userID);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (isset($_POST['deleteButton'])) {
    $selected = mysqli_real_escape_string($conn, $_REQUEST['id_to_delete']);
    echo $selected . "dsdddddddddddd";

    $sql = "DELETE FROM  booking WHERE customerID = ? and bookingID = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {

        mysqli_stmt_bind_param($stmt, "ii", $userID, $selected);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    }
    header('location: profile.php');
    mysqli_free_result($result);
}
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<?php echo getHeader('Login'); ?>

<div id=profileGridContainer>

    <div id=profileNav>
        <ul>
            <li><a href="">Edit your information</a></li>
            <li><a href="">Delete your profile</a></li>
            <li><a href="">Customer service</a></li>
        </ul>
    </div>

    <div class="myBookings">
        <?php foreach ($results as $res) { ?>
            <div class="searchResultProfile">
                <img src="<?php echo $res['imageName']; ?>" border="0" alt="W3Schools" width="250" height="175">

                <div class="descriptionProfile">
                    <p class="listingNameProfile"><?php echo $res['accommodation_name']; ?></p>
                    <!-- Total booking cost : <?php echo $res['total_booking_cost']; ?> -->
                    <p class="locationProfile"><?php echo $res['location']; ?><br></p>
                    <div class="listingDetails2Profile">Check-In : <?php echo $res['start_date']; ?><br>
                        Check-Out : <?php echo $res['end_date']; ?><br></div>
                    <p id="priceProfile">£<?php echo $res['price_per_night'] ?></p>
                    <p id="perNightProfile"> per night</p><br><br>
                    <p id="totalProfile"> Total price </p>
                    <p id="totalPriceProfile">£<?php echo $res['total_booking_cost'] ?></p>
                    <span>
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

<?php echo getFooter(); ?>


</html>