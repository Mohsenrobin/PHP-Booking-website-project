<?php
//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';

//checking sessions status
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// storing connection to the conn variable 
$conn = getConnection();

//storing temparary results form the first query to this varibale
$bookingTempResults = array();
//storing the result from the second query to this variubale
$bookingResults = array();


//checking for array key exist for all the fields 
if (array_key_exists('destination', $_REQUEST)) {
  $destinationInput = $_REQUEST['destination'];
} else {
  errorHandling("Array key dose not exist");
}

$destination = mysqli_real_escape_string($conn, $destinationInput);

if (array_key_exists('numberOfPeople', $_REQUEST)) {
  $numberOfPeopleInput = $_REQUEST['numberOfPeople'];
} else {
  errorHandling("Array key dose not exist");
}

$numberOfPeople = $numberOfPeopleInput;

if (array_key_exists('tripStart', $_REQUEST)) {
  $tripStartInput = $_REQUEST['tripStart'];
} else {
  errorHandling("Array key dose not exist");
}

$tripStart = $tripStartInput;

if (array_key_exists('tripEnd', $_REQUEST)) {
  $tripEndInput = $_REQUEST['tripEnd'];
} else {
  errorHandling("Array key dose not exist");
}

$tripEnd =  $tripEndInput;

//checking for empty varibales
if (
  !empty($destination) and !empty($numberOfPeople) and !empty($tripStart)
  and !empty($tripEnd)
) {

  // First sql query which search for any accommodation that is booked 
  // and have conflict with the start and end date of user trip to be excluded from the result
  $sql = "SELECT a.accommodationID,accommodation_name,`description`,price_per_night,`location`,country,shortDesc FROM accommodation a, booking b
      WHERE (`location` = ? or  country = ? ) and (( `start_date` <= ? and `end_date` > ? ) or 
      ( `start_date` < ? and `end_date` >= ? ) or ( `start_date` >= ? and `end_date` < ? )) and a.accommodationID = b.accommodationID ORDER BY `start_date`";

  // using prepare method
  if ($stmt = mysqli_prepare($conn, $sql)) {

    //passing parameters
    mysqli_stmt_bind_param($stmt, "ssssssss", $destination, $destination, $tripStart, $tripStart, $tripEnd, $tripEnd, $tripStart, $tripEnd);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $results = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //storing ids of alrady booked accommodations
    foreach ($results as $res) {
      $bookingTempResults[] = $res['accommodationID'];
    }

    // deleting duplicated ids
    $bookingUnique = array_unique($bookingTempResults, SORT_REGULAR);

    //A new table created in the databse called images in order to store images name and url
    //Second sql query which is for getting all the accommodations and their images
    $sql2 = "SELECT a.accommodationID,accommodation_name,`description`,price_per_night,`location`,country,accommodationType, shortDesc , imageID, imageName FROM accommodation a, images i
      WHERE (`location` = ? or  country = ? ) and a.accommodationID = i.accommodationID";
    //Prepare statement
    if ($stmt2 = mysqli_prepare($conn, $sql2)) {

      //passing parameters
      mysqli_stmt_bind_param($stmt2, "ss", $destination, $destination);

      mysqli_stmt_execute($stmt2);

      $result2 = mysqli_stmt_get_result($stmt2);

      $results2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);


      //Here we delete all the accommodations that are already booked at the specefic date
      // and store the rest of them to the $bookingResults varible to be printed out on the page
      foreach ($results2 as $res) {
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
      //Storing the number of people, start date and end date into session global varible to 
      // be passed to details and profile page
      $_SESSION['numberOfPeople'] = mysqli_real_escape_string($conn, $numberOfPeople);
      $_SESSION['start_date'] = mysqli_real_escape_string($conn, $tripStart);
      $_SESSION['end_date'] = mysqli_real_escape_string($conn, $tripEnd);
    }
  } else {
    errorHandling("Seomthing went wrong, data is not loaded from the database");
  }
  //Free resources
  mysqli_free_result($result);
  mysqli_free_result($result2);
  mysqli_close($conn);
} else {
  errorHandling("At least one of the variables is empty");
}

?>

<!DOCTYPE html>
<html lang="en">

<!-- Getting the Header  -->
<?php echo getHeader('Listing') ?>

<!-- listing content wraps all the contents of the page -->
<div class="listingContent">
  <!-- this grid will tyake carte of all the elements -->
  <div id=listingGridContainer>

    <!-- calling main search method  -->
    <?php mainSearchBar() ?>

    <!-- this div lists all the results of the second query -->
    <div class="listing">
      <?php
      if (empty($bookingResults)) {
        errorHandling("There is no any property for this period of time, please change the date or the destinatoin");
      }
      foreach ($bookingResults as $res) {
      ?>
        <a class="searchResult" href="details.php?id=<?php echo $res['accommodationID'] ?>">
          <img src="assets/images/<?php echo $res['imageName']; ?>" border="0" alt="" width="350" height="247">
          <div class="description">
            <p class="listingName"><?php echo $res['accommodation_name']; ?></p>
            <p class="listingName"><?php echo $res['accommodationID']; ?></p>
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

<!-- Getting the Footer  -->
<?php echo getFooter() ?>


</html>