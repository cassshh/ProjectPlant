<?php
// Start een sessie
session_start();

// Zet sessie variabelen
$user = $_SESSION['username'];
$image = $_SESSION['image'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html>
    <head>
        <title>Website test</title>  
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
                            <?php
                            // Als er een sessie is gestart, toon dan de onderstaande link in de header
                            if ($_SESSION) {
                                ?>
                                <li><a href='add_plant.php'>Registreer een plant</a></li>
                                <?php
                            }
                            ?>
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
// Als er geen sessie is gestart, toon dan de onderstaande link in de header
        if (!$_SESSION) {
            ?>
            <div class='title'><p>Project Plant</p>
            </div>
            <?php
        }
        // Is er wel een sessie gezet, echo dan de onderstaande regel
        else {
            ?>
            <div class='title'><p>Ingelogd als <?php echo $user ?></p>
            </div>
            <?php
        }
        ?>   
    </nav>
    <div class='content'>
        <div class='login'>
            <?php
            // Als er niet op de submit knop is geklikt en er geen sessie is gestart, toon dan het inlogmenu
            if (!$_POST['submit']) {
                if (!$_SESSION) {
                    ?>
                    <p>Log in met je gebruikernaam en wachtwoord</p>
                    </br>
                    <form action='homepagina.php' method='POST'>
                        <table>
                            <tr>
                                <td>Gebruikersnaam</td>
                                <td><input type='text' name='username'></td>
                            </tr>
                            <tr>
                                <td>Wachtwoord</td>
                                <td><input type='password' name='password'></td>
                            </tr>
                            <tr>
                                <td colspan='2'><input type='submit' name='submit' value='login'></td>
                            </tr>
                        </table>
                        <?php
                    }
                }
                ?>
                <?php
                // Als er een POST REQUEST wordt gedaan
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Als er op de submit knop wordt geklikt
                    if (isset($_POST['submit'])) {
                        // Maak een $errors array aan
                        $errors = [];

                        // Als er geen username of wachtwoord is ingevuld, push dan de bijhorende error naar $errors
                        if (empty($_POST['username'])) {
                            array_push($errors, 'Er is geen gebruikersnaam ingevuld');
                        } else if (empty($_POST['password'])) {
                            array_push($errors, 'Er is geen wachtwoord ingevoerd');
                        }
                        // Als er errors zijn, echo deze
                        if (count($errors) > 0) {
                            foreach ($errors as $error) {
                                echo '</br><p>' . $error . '</p>';
                            }
                        }
                        // Als er geen errors zijn
                        else {
                            // Connect met de mysql database
                            $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');

                            // Login check query
                            $query = "
                                SELECT * 
                                FROM login 
                                WHERE username='" . mysqli_real_escape_string($connect, $_POST['username']) . "'
                                AND password='" . mysqli_real_escape_string($connect, $_POST['password']) . "'";

                            // Run de query
                            $result = mysqli_query($connect, $query);

                            // Haal de rijen op van de uitgevoerde query
                            $row = mysqli_fetch_assoc($result);

                            // Als er geen rijen zijn, echo..
                            if (!$row) {
                                echo '<p></br>deze combinatie van gebruikersnaam en wachtwoord bestaat niet</p>';
                            }
                            // Zijn er wel rijen, zet dan een aantal $_SESSION variabelen
                            else if ($row == TRUE) {
                                $_SESSION['username'] = $_POST['username'];
                                $_SESSION['password'] = $_POST['password'];
                                $_SESSION['user_id'] = $row['user_id'];
                                $_SESSION['image'] = $row['image'];
                                $_SESSION['image_name'] = $row['image_name'];

                                // Query om plantgegevens op te halen
                                $query2 = "
                                    SELECT plant_id,
                                    name,
                                    type_id,
                                    user_id,
                                    plant_type
                                    FROM plant
                                    ";

                                // Run de query
                                $result2 = mysqli_query($connect, $query2);

                                // Haal de rijen op van de uitgevoerde query
                                $row2 = mysqli_fetch_assoc($result2);

                                // Zet weer een aantal $_SESSION variabelen
                                if ($row2 == TRUE) {
                                    $_SESSION['plant_id'] = $row2['plant_id'];
                                    $_SESSION['name'] = $row2['name'];
                                    $_SESSION['plant_type'] = $row2['plant_type'];
                                    $_SESSION['type_id'] = $row2['type_id'];
                                }

                                // Tel het aantal rijen dat wordt opgehaald
                                $count = mysqli_num_rows($result);

                                // Is het aantal rijen gelijk aan 1, dan wordt je doorverwezen naar de homepagina
                                if ($count == 1) {
                                    echo '</br>Je bent succesvol ingelogd';
                                    header("location: http://casnetwork.tk/plant/homepagina.php");
                                }
                            }
                        }
                    }
                }
                ?>
            </form>
        </div>
        <div class="footer">
            <div id="footerlinks">&copy; 2015</div>
            <div id="footerrechts">
                <?php
                // Als er een session is gestart, toon dan de log uit knop
                if ($_SESSION) {
                    echo "<a class='test' href='http://casnetwork.tk/plant/logout.php' class='nav-item'>Log uit</a>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>