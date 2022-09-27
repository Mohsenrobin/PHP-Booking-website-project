<?php

include 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$conn = getConnection();
$results = $results3 = array();
$bookingRes = array();
$destination =  $numberOfPeople = $tripStart =  $tripEnd = null;
$bookingResults = array();

if (array_key_exists('destination', $_REQUEST)) :
  $destinationInput = $_REQUEST['destination'];
endif;

$destination = mysqli_real_escape_string($conn, $destinationInput);

if (array_key_exists('numberOfPeople', $_REQUEST)) :
  $numberOfPeopleInput = $_REQUEST['numberOfPeople'];
endif;

$numberOfPeople = mysqli_real_escape_string($conn, $numberOfPeopleInput);

if (array_key_exists('tripStart', $_REQUEST)) :
  $tripStartInput = $_REQUEST['tripStart'];
endif;

$tripStart = mysqli_real_escape_string($conn, $tripStartInput);

if (array_key_exists('tripEnd', $_REQUEST)) :
  $tripEndInput = $_REQUEST['tripEnd'];
endif;

$tripEnd = mysqli_real_escape_string($conn, $tripEndInput);


// $destination = mysqli_real_escape_string($conn, $_REQUEST['destination']);
// $numberOfPeople = mysqli_real_escape_string($conn, $_REQUEST['numberOfPeople']);
// $trip_start = mysqli_real_escape_string($conn, $_REQUEST['tripStart']);
// $trip_end = mysqli_real_escape_string($conn, $_REQUEST['tripEnd']);
if (
  !empty($destination) and !empty($numberOfPeople) and !empty($tripStart)
  and !empty($tripEnd)
) {
  $sql = "SELECT a.accommodationID,accommodation_name,`description`,price_per_night,`location`,country,shortDesc FROM accommodation a, booking b
      WHERE (`location` = ? or  country = ? ) and (( `start_date` <= ? and `end_date` > ? ) or 
      ( `start_date` < ? and `end_date` >= ? ) or ( `start_date` >= ? and `end_date` < ? )) and a.accommodationID = b.accommodationID ORDER BY `start_date`";
  if ($stmt = mysqli_prepare($conn, $sql)) {

    mysqli_stmt_bind_param($stmt, "ssssssss", $destination, $destination, $tripStart, $tripStart, $tripEnd, $tripEnd, $tripStart, $tripEnd);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $results = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($results as $res) {
      $bookingRes[] = $res['accommodationID'];
    }
    $bookingUnique = array_unique($bookingRes, SORT_REGULAR);
    //print_r($bookingUnique);
    //SELECT ONLY ACCOMMOADTION STAFF TO MAKE IT UNIQE
    // $bookingUnique = array_unique($results,SORT_REGULAR);
    // print_r($bookingUnique);

    $sql2 = "SELECT a.accommodationID,accommodation_name,`description`,price_per_night,`location`,country,accommodationType, shortDesc , imageID, imageName FROM accommodation a, images i
      WHERE (`location` = ? or  country = ? ) and a.accommodationID = i.accommodationID";
    if ($stmt2 = mysqli_prepare($conn, $sql2)) {

      mysqli_stmt_bind_param($stmt2, "ss", $destination, $destination);

      mysqli_stmt_execute($stmt2);

      $result3 = mysqli_stmt_get_result($stmt2);

      $results3 = mysqli_fetch_all($result3, MYSQLI_ASSOC);


      foreach ($results3 as $res) {
        for ($i = 0; $i < count($bookingUnique); $i++) {
          if (isset($res)) {
            if ($res['accommodationID'] == $bookingUnique[$i]) {
              unset($res);
            }
          }
        }
        if (isset($res)) {
          $bookingResults[] = $res;
        }
      }
      $_SESSION['numberOfPeople'] = mysqli_real_escape_string($conn, $numberOfPeople);
      $_SESSION['start_date'] = mysqli_real_escape_string($conn, $tripStart);
      $_SESSION['end_date'] = mysqli_real_escape_string($conn, $tripEnd);
    }
  }
  mysqli_free_result($result);
  mysqli_free_result($result3);
  mysqli_close($conn);
}

if (isset($_GET['filterSubmit'])) {
  echo " havent set it yet";
}

?>

<!DOCTYPE html>
<html lang="en">

<?php echo getHeader('Listing') ?>

<div class="listingContent">
  <div id=listingGridContainer>

    <?php mainSearchBar() ?>

    <div id="nav">
      <form class="searchNav" action="listingPage.php" method="GET">
        <a class="filters"><label for="budget"> Fliters </label><br>
          <hr><br>
        </a>
        <a class="budget"><label for="budget"> Your budget <br> (per night) </label><br><br></a>
        <input type="radio" id="budget1" name="budget" value="0 to £50">
        <label for="budget1"> 0 - £50 </label><br><br>
        <input type="radio" id="budget2" name="budget" value="£50 - £100">
        <label for="budget2"> £50 - £100 </label><br><br>
        <input type="radio" id="budget3" name="budget" value="£100 - £200">
        <label for="budget3"> £100 - £200 </label><br><br>
        <hr>
        <a class="budget"><label for="Property type">Property type</label><br><br></a>
        <input type="radio" id="Property type1" name="Property type" value="Hotel">
        <label for="Property type1"> Hotel </label><br><br>
        <input type="radio" id="Property type2" name="Property type" value="Guest houses">
        <label for="Property type2"> Guest houses </label><br><br>
        <button type="submit" name="filterSubmit">Apply Filter</button>
      </form>

    </div>

    <div class="listing">
      <?php foreach ($bookingResults as $res) { ?>
        <a class="searchResult" href="details.php?id=<?php echo $res['accommodationID'] ?>">
          <img src="<?php echo $res['imageName']; ?>" border="0" alt="" width="350" height="247">
          <div class="description">
            <p class="listingName"><?php echo $res['accommodation_name']; ?></p>
            <p class="location"><?php echo $res['location']; ?><br><br><br></p>
            <p class="listingDetails1"><?php echo $res['accommodationType']; ?></p>
            <p class="listingDetails2"><?php echo $res['shortDesc']; ?></p>
            <div><span>
                <p id="price">£<?php echo $res['price_per_night']; ?></p><br>
                <p id="perNight">per night</p>
              </span></div>

          </div>
        </a>
      <?php } ?>
    </div>
  </div>
</div>

<?php echo getFooter() ?>


</html>