<?php include 'functions.php';


$tehran = "Tehran, the Capital of Iran locating on the hillside of the Alborz mountain chain and near Damavand,
 the highest volcanic peak in Iran. Demonstrating traditional and modern architecture,
  Azadi square symbolized Tehran in the past and Milad tower plays this role, at present.
   Placed in a large area, Tehran is one of the biggest,
    most significant metropolitans in the world with an extremely increasing population.";
$tehranIMG = "img/arman-taherian-c4pe3HEhnig-unsplash.jpg";
$tehranSection = "exp1";

$shiraz = "Shiraz is known as the most significant tourism center, the 6th populous city,
 the cultural capital of the country, 2nd literary city in the world, the 3rd religious city,
  the 3rd holy shrine of Iran, and the city of poetry, wine, and flower.
   The tombs of several poets such as Hafez and Sa’adi are placed in Shiraz which embraces a major part of Iran’s ancient history,
    historical, cultural, religious, and natural attractions.";
$shirazIMG = "img/steven-su-AxhfHp6fJ2M-unsplash.jpg";
$shirazSection = "exp2";

$neyshaboor = "Neyshabur is one of the oldest cities and most important cultural, historical,
 tourism, industrial, and population centers of Iran, which was located on the Silk Road in the past. This city has been registered on UNESCO intangible heritage list.
  Neyshabur is known for its turquoise due to having many mines and turquoise cutting manufactories which is one of the main occupations in this city.";
$neyshaboorIMG = "img/mohammad-rahighi-18AYh3PHQ8w-unsplash.jpg";
$neyshaboorSection = "exp3";

$ramsar = "Ramsar is known as the most beautiful city in the north of Iran. 
The Caspian Sea bounds this city from the north and Alborz mountain range from the south. 
The climate of Ramsar is hot and humid in summer and mild in winter. 
The Ramsar Convention is an international agreement in which member states to study and support the world's most important wetlands, especially those inhabited by waterfowl.";
$ramsarIMG = "img/benyamin-bohlouli-8Wvsc5S0iz8-unsplash.jpg";
$ramsarSection = "exp4"

?>

<!DOCTYPE html>
<html lang="en">
<?php echo getHeader("Homepage") ?>

<div id=mainGridContainer>
    <div id="homeImage">
        <img src="img/steven-su-AxhfHp6fJ2M-unsplash.jpg" width="1520" height="571">
    </div>

    <?php mainSearchBar() ?>

    <div class="explore">
        <?php
        echo getImage($tehranIMG, $tehranSection, $tehran, "Tehran");
        echo getImage($shirazIMG, $shirazSection, $shiraz, "Shiraz");
        echo getImage($neyshaboorIMG, $neyshaboorSection, $neyshaboor, "Neyshaboor");
        echo getImage($ramsarIMG, $ramsarSection, $ramsar, "Ramsar");
        ?>
    </div>
</div>

<?php echo getFooter() ?>

</html>