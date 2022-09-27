<?php
//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<!-- Getting the Footer  -->
<?php echo getHeader("Wireframes") ?>

<!-- Main grid which takes care of all wireframe elements -->
<div id=wireframeContainer>
    <div>
        <h1>Wireframes</h1>
    </div>
    <div id="wireframImages">
        <img src="assets/images/mainPage.jpg" border="0" alt="W3Schools"><br>
        <img src="assets/images/ListingPage.jpg" border="0" alt="W3Schools"><br>
        <img src="assets/images/DetailsPage.jpg" border="0" alt="W3Schools"><br>
        <img src="assets/images/LoginPage.jpg" border="0" alt="W3Schools"><br>
        <img src="assets/images/RegisterPage.jpg" border="0" alt="W3Schools"><br>
        <img src="assets/images/profilePage.jpg" border="0" alt="W3Schools"><br>

    </div>

</div>

<!-- Getting the Footer  -->
<?php echo getFooter() ?>

</html>
