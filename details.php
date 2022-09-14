<?php 
    
    session_start();

    include 'functions.php';
    $conn = getConnection();

    $accommodation = $_SESSION['accommodation'] ?? ''; 
    $accommodationId = $numberOfPeople = $start_date = $end_date = '';
    print_r($accommodation);
    // echo '    FOURTH     ';
    // print_r($accommodation);
    echo $_SESSION['forename'];
    if(isset($_REQUEST['id'])){

        //IMPORTANT MAYBE WE NEED TO CHECK IF THE KEY EXIST , 
        //LIKE WHAT WE DID IN THE REGISTER FORM : if (array_key_exists('forename',$_REQUEST)):
        $accommodationId = mysqli_real_escape_string($conn,$_REQUEST['id']);
        $_SESSION['accommodationId'] = $accommodationId;

        $_SESSION['numberOfPeople'] = mysqli_real_escape_string($conn,$_REQUEST['people']);
        $_SESSION['start_date'] = mysqli_real_escape_string($conn,$_REQUEST['std']);
        $_SESSION['end_date'] = mysqli_real_escape_string($conn,$_REQUEST['edd']);

        $sql = "SELECT * FROM accommodation WHERE accommodationID = ?";

        if($stmt = mysqli_prepare($conn,$sql)){

            mysqli_stmt_bind_param($stmt,"i",$accommodationId);
            
            mysqli_stmt_execute($stmt);

            $queryResult = mysqli_stmt_get_result($stmt);

            if($queryResult){
            $accommodation = mysqli_fetch_assoc($queryResult);
            }
        
        }

        $_SESSION['accommodation'] = $accommodation;
        // echo '   FIRST    ';
        // print_r($accommodation);
        mysqli_free_result($queryResult);
        mysqli_close($conn);

    }

    if(isset($_POST['book'])){

        $idToSave = mysqli_real_escape_string($conn,$_REQUEST['id_to_save']);
        echo $_SESSION['id'] . "sssssssss";
        if(isset($_SESSION['id'])){
            //echo 'ysssssssssssssssss';
            $numberOfPeople = mysqli_real_escape_string($conn,$_SESSION['numberOfPeople']);
            $start_date = mysqli_real_escape_string($conn,$_SESSION['start_date']);
            $end_date = mysqli_real_escape_string($conn,$_SESSION['end_date']);
            $userId = mysqli_real_escape_string($conn,$_SESSION['id']);
            $note = mysqli_real_escape_string($conn,$_REQUEST['bookingNote']);
            $accommodation['price_per_night'] = ((strtotime($end_date) - strtotime($start_date)) / 86400 ) * $accommodation['price_per_night'] ; 


            $sql = "INSERT INTO booking (accommodationID, customerID, `start_date`, end_date,
            num_guests, total_booking_cost, booking_notes) VALUES (?,?,?,?,?,?,?)";


            if($stmt = mysqli_prepare($conn,$sql)){

            mysqli_stmt_bind_param($stmt,"iissids",$accommodation['accommodationID'],$userId,$start_date,
            $end_date,$numberOfPeople,$accommodation['price_per_night'],$note);

            $queryResult = mysqli_stmt_execute($stmt);

            }


        } else {
            print_r($accommodation) ;
            print_r($_SESSION['accommodation']);
            //header('location: signIn.php');
            ;
        }
    }
    
    
    // echo '    SECOND    ';
    // print_r($accommodation);
    // echo '    THIRS     ';
    // print_r($_SESSION['accommodation']);
    $accommodation = $_SESSION['accommodation'];
    // echo '    FOURTH     ';
    // print_r($accommodation);
    echo $_SESSION['forename'];


?>

<!DOCTYPE html>
<html lang="en">

    <?php echo getHeader() ?>

    <label for="searchResult"><a href="details.php?id=<?php echo $results['accommodationID']?>">
              <h4 class="h4">ID : <?php echo $accommodation['accommodationID'];?>
              Name :  <?php echo $accommodation['accommodation_name'];?></h4>
              Description : <?php echo $accommodation['description'];?>
              Price_per_night : <?php echo $accommodation['price_per_night'];?>
              Location : <?php echo $accommodation['location'];?>
              Country : <?php echo $accommodation['country'];?><br>
              </a></label>

              <form action="details.php" method="POST">
                <input type="hidden" name="id_to_save" value="<?php echo $accommodation['accommodationID']?>">
                <input type="text" name="bookingNote" placeholder="Notes">
                <input type="submit" name="book" value="Make a reservation">
              </form>
              
    <?php echo getFooter() ?>

</body>
</html>