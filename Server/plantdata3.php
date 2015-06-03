<?php
session_start();
$user = $_SESSION['username'];
$get = $_GET['id'];
$_SESSION['plant_name'] = $get;

if(!$_SESSION)
{
    header("location: http://plant.serverict.nl/homepagina.php");
}
else 
{
include 'plant.php';
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
                            <li><a href='register.php'>Registreer een plant</a></li>
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
        if(!$_SESSION)
        {
        ?>
        <div class='title'><p>Project Plant</p>
        </div>
        <?php
        }
        else
        {
        ?>
            <div class='title'><p>Ingelogd als <?php echo $user?></p>
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
            if($_SESSION)
            {
                echo "<a class='test' href='http://plant.serverict.nl/logout.php' class='nav-item'>Log uit</a>"; 
            }
            ?>
            </div>
        </div>
    </div>
    <div class='p'>
        <p>Hieronder staat een overzicht van de lichtgegevens van <?php echo $get ?></p>
        <img src='http://plant.serverict.nl/light.php'>
    </div>
    </br>
</body>
</html>