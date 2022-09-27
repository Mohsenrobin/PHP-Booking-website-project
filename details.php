<?php
include 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = getConnection();
if (isset($_SESSION['accommodation'])) {
    $accommodation = $_SESSION['accommodation'];
}
$accommodationId = $numberOfPeople = $start_date = $end_date = $totalPrice = '';

if (isset($_REQUEST['id'])) {

    //IMPORTANT MAYBE WE NEED TO CHECK IF THE KEY EXIST , 
    //LIKE WHAT WE DID IN THE REGISTER FORM : if (array_key_exists('forename',$_REQUEST)):
    $accommodationId = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $_SESSION['accommodationId'] = $accommodationId;

    $_SESSION['numberOfPeople'] = mysqli_real_escape_string($conn, $_REQUEST['people']);
    $_SESSION['start_date'] = mysqli_real_escape_string($conn, $_REQUEST['std']);
    $_SESSION['end_date'] = mysqli_real_escape_string($conn, $_REQUEST['edd']);
    $_SESSION['price_per_night'] = mysqli_real_escape_string($conn, $_REQUEST['pricePerNight']);

    $price = mysqli_real_escape_string($conn, $_SESSION['price_per_night']);
    $start_date = mysqli_real_escape_string($conn, $_SESSION['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_SESSION['end_date']);

    $totalPrice = ((strtotime($end_date) - strtotime($start_date)) / 86400) * $price;

    $sql = "SELECT * FROM accommodation a, images i WHERE a.accommodationID = ? and a.accommodationID = i.accommodationID";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $accommodationId);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $accommodation = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        }
    }

    $_SESSION['accommodation'] = $accommodation;
    // echo '   FIRST    ';
    // print_r($accommodation);
    mysqli_free_result($result);
    mysqli_close($conn);
}
$accommodation = $_SESSION['accommodation'];


?>

<!DOCTYPE html>
<html lang="en">

<?php echo getHeader("Booking Details") ?>
<div class="detailsContent">
    <div id=detailsGridContainer>
        <div id="image1">
            <img src="<?php echo $accommodation['imageName']; ?>" border="0" alt="" width="900" height="630">
        </div>

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

        <div id="details2">
            <p class="details2Details"><?php echo $accommodation['description']; ?></p>
        </div>

        <form id="book" action="profile.php" method="GET">
            <input type="hidden" name="id_to_save" value="<?php echo $accommodation['accommodationID'] ?>">
            <input type="hidden" name="price_per_night" value="<?php echo $accommodation['price_per_night'] ?>">
            Notes:<textarea id="notes" name="bookingNote" cols="40" rows="5"></textarea>
            <input id="button" type="submit" name="bookButton" value="Make a reservation">
        </form>
    </div>
</div>
<?php echo getFooter() ?>

</body>

</html>