<?php

// Start een sessie
session_start();

// Maak een sessie variable aan
$user = $_SESSION['username'];

// Als er geen sessie is gestart, ga terug naar de homepagina
if (!$_SESSION) {
    header("location: http://casnetwork.tk/plant/homepagina.php");
}

// Is er wel een sessie gestart, include dan select_plant.php
else {
    include 'select_plant.php';
}
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
        }
        // Als er wel een sessie is gestart, toon dan de onderstaande tekst in de header
        else {
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
    </br>
</body>
</html>