<?php
session_start();

$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (isset($_POST['submit']))
{
$errors = [];

if (!(empty($_POST['username'])))
{
    $query = "SELECT * FROM login WHERE username='" . mysqli_real_escape_string($connect, $_POST['username']) . "'";
    $result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) > 0)
    {
        array_push($errors, 'Deze gebruikersnaam bestaat al');
    }
    } 
    else
    {
        array_push($errors, 'Er is geen gebruikersnaam ingevuld');
    }
    if (empty($_POST['password']))
    {
        array_push($errors, 'Er is geen wachtwoord ingevuld');
    }
    if (count($errors) === 0)
    {
        $query = "INSERT INTO login VALUES('NULL', '" . $_POST['username'] . "', '" . $_POST['password'] . "')";

        $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
        mysqli_close($connect);
    }
}
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
                <div class='nav-content'>
                    <div class='nav-sub'>
                        <ul>
                            <!--<li><a href='plantdata.php'>Planten gegevens</a></li>!-->
                            <li><a href='plants.php'>kies je plant</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li>
        </ul>
        <div class='title'><p>Project Plant</p></div>
    </nav>	
    <div class='content'>
        <div class='login'>
            <form action='register.php' method='POST'>
        <p>Vul hier een gebruikersnaam en wachtwoord in om te registreren</p>
        </br>
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
                <td colspan='2'><input type='submit' name='submit' value='Registreer'></td>
            </tr>
        </table>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
        if (isset($_POST['submit']))
        {
        if (count($errors) > 0)
        {
            ?>
            <ul>
                <?php
                foreach ($errors as $error)
                {
                    ?>
                        <li>
                            <?php echo $error; ?>
                        </li>
                    <?php
                }
                ?>
            </ul>
            <?php
        } 
        else
        {
            echo "<ul></br><li>Je account is aangemaakt! Klik op<a href='http://plant.serverict.nl/homepagina.php'> deze link</a> om in te loggen</li></ul>";
        }
    }
}
?>
        </div>
        <div class="footer">
            <div id="footerlinks">&copy; 2015</div>
            <div id="footerrechts">
            <?php
            if($_SESSION)
            {
                echo "<a class='visited' href='http://plant.serverict.nl/logout.php' class='nav-item'>Log uit</a>"; 
            }
            ?>
            </div>
        </div>
    </div>
    <div class='p'><p></p></div>
    </br>
</body>
</html>