<?php
include 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = getConnection();
//temp del 23/09 13:14
// if (isset($_SESSION['accommodation'])) {
//     $accommodation = $_SESSION['accommodation'];
// }
$accommodationId = $numberOfPeople = $start_date = $end_date = $totalPrice = '';

if (isset($_REQUEST['id'])) {

    //IMPORTANT MAYBE WE NEED TO CHECK IF THE KEY EXIST , 
    //LIKE WHAT WE DID IN THE REGISTER FORM : if (array_key_exists('forename',$_REQUEST)):

    if (array_key_exists('id', $_REQUEST)) :
        $idInput = $_REQUEST['id'];
    endif;

    $accommodationId = mysqli_real_escape_string($conn, $idInput);

    $_SESSION['accommodationId'] = $accommodationId;

    $start_date = mysqli_real_escape_string($conn, $_SESSION['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_SESSION['end_date']);

    $sql = "SELECT * FROM accommodation a, images i WHERE a.accommodationID = ? and a.accommodationID = i.accommodationID";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $accommodationId);

        mysqli_stmt_execute($stmt);

        $queryResult = mysqli_stmt_get_result($stmt);

        if ($queryResult) {
            $accommodation = mysqli_fetch_assoc($queryResult);
            mysqli_free_result($queryResult);
        }
    }
    $price = mysqli_real_escape_string($conn, $accommodation['price_per_night']);
    $totalPrice = ((strtotime($end_date) - strtotime($start_date)) / 86400) * $price;
    $_SESSION['totalPrice'] = $totalPrice;
    $_SESSION['accommodation'] = $accommodation;
    // echo '   FIRST    ';
    // print_r($accommodation);
    mysqli_close($conn);
}
//temp del 23/09 13:14
// $accommodation = $_SESSION['accommodation'];
// print_r($_SESSION['accommodation']);

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

        <form id="book" action="profile.php" method="POST">
            <input type="hidden" name="id_to_save" value="<?php echo $accommodation['accommodationID'] ?>">
            Notes:<textarea id="notes" name="bookingNote" cols="40" rows="5"></textarea>
            <input id="button" type="submit" name="bookButton" value="Make a reservation">
        </form>
    </div>
</div>
<?php echo getFooter() ?>

</body>

</html>