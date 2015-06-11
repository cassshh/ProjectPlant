<?php

// Start een sessie
session_start();

// Zet een aantal sessie variabelen
$user = $_SESSION['username'];
$plant_id = $_SESSION['plant_id'];
$plant_name = $_SESSION['name'];

// Als er geen session is gezet wordt je doorverwezen naar de homepagina
if (!$_SESSION) {
    header("location: http://casnetwork.tk/plant/homepagina.php");
}
// Is er wel een sessie gestart, run dan de onderstaande code
else {
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
    <html>
        <head>
            <title>Website</title>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">   
            <link href="css.css" rel="stylesheet" type="text/css"/>
        </head>
        <body>
        <nav class='nav-main'>
            <a href='homepagina.php' class='home'>Home</a>
            <ul>
                <li>
                    <a href='#' class='nav-item'>Planten</a>
                    <div class='nav-content'>
                        <div class='nav-sub'>
                            <ul>
                                <li><a href='registeredplants.php'>Planten info</a></li>
                                <li><a href='add_plant.php'>Registreer een plant</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li>	
                    <a href='#' class='nav-item'>Mijn planten</a>
                    <div class='nav-content'>
                        <div class='nav-sub'>
                            <ul>
                                <li><a href='plants.php'>Kies je plant</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
            </ul>
    <?php
    // Als er geen sessie is gestart, toon dan de onderstaande link in de header
    if (!$_SESSION) {
        ?>
                <div class='title'><p>Project Plant</p>
                </div>
                <?php
            } else {
                // Is er wel een sessie gezet, echo dan de onderstaande regel
                ?>
                <div class='title'><p>Ingelogd als <?php echo $user ?></p>
                </div>
                <?php
            }
            ?>
        </nav>	
        <div class='content'>
            <div class="footer">
                <div id="footerlinks">&copy; 2015</div>
                <div id="footerrechts">
    <?php
    // Als er een sessie is gestart, toon de log uit knop
    if ($_SESSION) {
        echo "<a class='test' href='http://casnetwork.tk/plant/logout.php' class='nav-item'>Log uit</a>";
    }
    ?>
                </div>
            </div>
        </div>
        <div class='p'><p>Hieronder staat een overzicht van alle geregistreerde planten met hun bijhorende gegevens</p></div>
        </br>
    </body>
    </html>
    <?php
// Include plantgegevens.php..
    include 'plantgegevens.php';
}
?>