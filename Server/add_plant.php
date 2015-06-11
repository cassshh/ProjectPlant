<?php
// Start een sessie
session_start();

// Zet sessie variabelen
$user = $_SESSION['username'];
$id = $_SESSION['user_id'];
$plant_id = $_SESSION['plant_id'];

// Connect met de server/database
$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');

// Query om aantal gegevens uit de type tabel te halen
$query2 = "SELECT name, type_id FROM type";

// Run de query
$result2 = mysqli_query($connect, $query2);

// Als er minder rijen worden teruggegeven dan de lengte, geef dan een foutmelding
if (mysqli_num_rows($result2) < strlen($result2)) {
    echo 'Er ging iets mis met het ophalen van de planttypes';
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

            <?php
// Als er geen sessie is gestart, toon dan de onderstaande link in de header
            if (!$_SESSION) {
                ?>

                <li>	
                    <a href='register.php' class='nav-item'>Registreer</a>
                </li>

                <?php
            }
            ?>

            <li>
        </ul>

        <?php
// Als er geen sessie is gestart, toon dan de deze tekst
        if (!$_SESSION) {
            ?>

            <div class='title'>
                <p>Project Plant</p>
            </div>

            <?php
        }
// Als er wel een sessie is gestart, toon dan de onderstaande tekst in de header
        else {
            ?>

            <div class='title'>
                <p>Ingelogd als <?php echo $user ?></p>
            </div>

            <?php
        }
        ?>
    </nav>	
    <div class='content'>
        <div class='login'>
            <form action='add_plant.php' method='POST'>
                <p>Vul de onderstaande gegevens in om de registratie te voltooien</p>
                </br>
                <table>
                    <tr>
                        <td>Plantnaam</td>
                        <td><input type='text' name='plant_name'></td>
                    </tr>
                    <tr>
                        <td>Plant type</td>
                        <td>

                            <!-- Dropdown menu met daarin de plant types!-->
                            <select name="option_value" style="width: 100%">
                                <?php
                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                    echo '<option value="' . $row2['type_id'] . '">' . $row2['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2'><input type='submit' name='submit' value='Registreer'></td>
                    </tr>
                </table>
        </div>
        <div class="footer">
            <div id="footerlinks">&copy; 2015</div>
            <div id="footerrechts">

                <?php
// Als er een session is gestart, toon de log uit knop
                if ($_SESSION) {
                    echo "<a class='test' href='http://casnetwork.tk/plant/logout.php' class='nav-item'>Log uit</a>";
                }
                ?>

            </div>
        </div>
    </div>
    <div class='p'><p></p></div>
    </br>
</body>
</html>
<?php
// Als er een POST REQUEST wordt gedaan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// Als er op de submit knop wordt geklikt    
    if (isset($_POST['submit'])) {
        // Zet sessie variabele en maak een $errors array aan
        $type_id = $_POST['option_value'];
        $errors = [];

        // Als plant_name niet leeg is, run de onderstaande code
        if (!(empty($_POST['plant_name']))) {
            // Zet een sessie variabele
            $plant = $_POST['plant_name'];

            // Query om de gegevens aan de database toe te voegen
            $query = "INSERT INTO plant VALUES('NULL', '$plant', '$type_id', '$id')";

            // Run de query
            $result = mysqli_query($connect, $query) or die(mysqli_error($connect));

            // Sluit de connectie met de database
            mysqli_close($connect);

            if ($query) {
                echo 'Je plant is toegevoegd aan de database!';
            }
        }
    }
}