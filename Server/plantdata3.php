<?php
session_start();
$user = $_SESSION['username'];
$plant_id = $_GET['id'];
$_SESSION['plant_id'] = $plant_id;

if(isset ($_POST['submit']))
{
    $_SESSION['begindatum'] = $_POST['begindatum'];
    $_SESSION['einddatum'] = $_POST['einddatum'];
}
if(!$_SESSION)
{
    header("location: http://casnetwork.tk/plant/homepagina.php");
}
else 
{
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
                echo "<a class='test' href='http://casnetwork.tk/plant/logout.php' class='nav-item'>Log uit</a>"; 
            }
            ?>
            </div>
        </div>
    </div>
    <div class='p'>
        <p>Hieronder staat een overzicht van de lichtgegevens van <?php echo $get ?></p>
        <img src='http://casnetwork.tk/plant/light.php'>
        </br>
        <form action='' method ='POST'>
            <table>
                <tr>
                    <td><p>Begindatum(yyyy/mm/dd): </p><input type='text' name='begindatum'></input></td>
                    <td><p>Einddatum(yyyy/mm/dd): </p><input type='text' name='einddatum'></input></td>
                    <td><input type='submit' name='submit' value='Zoek gegevens'></td>
                </tr>
            </table>
        </form>
    </div>
    </br>
</body>
</html>
<?php
include 'plant.php';
}