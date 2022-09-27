<?php

    include 'functions.php';
    $conn = getConnection();
    $r1 = array();

    //if statement one by one??????????????????????????????????????????????????????????????
    if(!empty($_REQUEST['destination']) and !empty($_REQUEST['numberOfPeople']) and !empty($_REQUEST['trip-start'])
      and !empty($_REQUEST['trip-end'])){
      
      $destination = mysqli_real_escape_string($conn,$_REQUEST['destination']);
      $numberOfPeople = mysqli_real_escape_string($conn,$_REQUEST['numberOfPeople']);
      $trip_start = mysqli_real_escape_string($conn,$_REQUEST['trip-start']);
      $trip_end = mysqli_real_escape_string($conn,$_REQUEST['trip-end']);

      $sql = "SELECT accommodationID , accommodation_name, price_per_night, location FROM accommodation
      WHERE `location` = ? or  country = ?";
      //
      if($stmt = mysqli_prepare($conn,$sql)){

        mysqli_stmt_bind_param($stmt,"ss",$destination,$destination);
            
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
      
        $r1 = mysqli_fetch_all($result,MYSQLI_ASSOC);
        
        //CHECK WHICH WAY THE TUTOR USED TO GET EACH ROW???????????????????????????????????????????????????
        //CHECK THE END DATE TO BE GREATER THAN START DATE

      // if ($result->num_rows > 0) {
      //   // output data of each row
      //   while($row = $result->fetch_assoc()) {
      //     $res = "id: " . $row["accommodationID"]. " - Name: " . $row["accommodation_name"]. " " .
      //     $row["price_per_night"] .$row["location"]. "<br>";
      //   }
      // } else {
      //   echo "0 results";
      // }
    }
      mysqli_free_result($result);
      mysqli_close($conn);
  }
?>

<!DOCTYPE html>
<html lang="en">

    <?php echo getHeader() ?>

    <main>

      <div id =mainGridContainer2>

        <?php setSearchBar() ?>

        <div class="nav">

          This is the Nav bar

        </div>

        <div class="main2">
          <?php foreach($r1 as $results){ ?>
              <label for="searchResult"><a href="details.php?id=<?php echo $results['accommodationID']?>
              &people=<?php echo $numberOfPeople?>
              &std=<?php echo $trip_start?>&edd=<?php echo $trip_end?>">
              <h4 class="h4">ID : <?php echo $results['accommodationID'];?>
              Name :  <?php echo $results['accommodation_name'];?>
              Price_per_night : <?php echo $results['price_per_night'];?>
              Location : <?php echo $results['location'];?><br></h4>
              </a></label>
          <?php } ?>
        </div>
        

      </div>

    </main>

    <?php echo getFooter() ?>


</html>