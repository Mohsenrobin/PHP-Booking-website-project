<?php

//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';

//getting the connection in order to work connect to the database
$conn = getConnection();

//I Created a new table in the databse clalled exploreIran in order to store information
// about explore section.
$sql = "SELECT * FROM exploreIran";

if ($result = mysqli_query($conn, $sql)) {

    $results = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    errorHandling('Can not load data from the database');
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- Getting the header -->
<?php echo getHeader("Homepage") ?>

<!-- Main grid which takes care of all main elements -->
<div id=mainContainer>
    <div id="homeImage">
        <!-- Loading main image  -->
        <img src="assets/images/reza-ghasemi-e3RxNRSLABo-unsplash.jpg" width="1525" height="752">
    </div>
    <!-- calling main search -->
    <?php mainSearchBar() ?>

    <div class="exploreTitle">
        Explore Iran: Tehran, Shiraz, Neyshaboor and Ramsar
    </div>

    <!-- calling getImage function to set explore section with data loaded from the database -->
    <div class="explore">

        <!-- A loop to get all 4 elements from the database -->
        <?php foreach ($results as $res) {
            echo getImage("assets/images/" . $res['imageURL'], $res['area'], $res['description'], $res['name']);
        }  ?>
    </div>
</div>
<!-- Getting the Footer  -->
<?php echo getFooter() ?>

</html>