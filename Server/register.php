<?php
//start een sessie
session_start();

//connect met de server/database
$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');

//als er een POST REQUEST wordt gedaan
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

//als er op de submit knop wordt geklikt
if (isset($_POST['submit']))
{
//maak een $errors array aan
$errors = [];

//als er een username is ingevuld
if (!(empty($_POST['username'])))
{
    //query om te controleren of de gebruiker al bestaat
    $query = "SELECT * FROM login WHERE username='" . mysqli_real_escape_string($connect, $_POST['username']) . "'";
    
    //run de query
    $result = mysqli_query($connect, $query);
    
    //als er records worden opgehaald, voeg dan een melding toe aan de $errors array
    if (mysqli_num_rows($result) > 0)
    {
        array_push($errors, 'Deze gebruikersnaam bestaat al');
    }
    }
    //als er geen gebruikersnaam is ingevuld, voeg een melding toe aan de $errors array
    else
    {
        array_push($errors, 'Er is geen gebruikersnaam ingevuld');
    }
    
    //als er geen wachtwoord is ingevuld, voeg een melding toe aan de $errors array
    if (empty($_POST['password']))
    {
        array_push($errors, 'Er is geen wachtwoord ingevuld');
    }
    //als er geen errors zijn
    if (count($errors) === 0)
    {
        //upload een plaatje
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $image_name = addslashes($_FILES['image']['name']);
        
        //query om de gebruikersnaam, het wachtwoord en het plaatje toe te voegen aan de database
        $query = "INSERT INTO login VALUES('NULL', '" . $_POST['username'] . "', '" . $_POST['password'] . "', '$image')";
        
        //run de query
        $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
        
        //sluit de connectie met de database
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
                        </ul>
                    </div>
                </div>
            </li>
            <li>	
                <a href='#' class='nav-item'>Mijn planten</a>
                <div class='nav-content'>
                    <div class='nav-sub'>
                        <ul>
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
            <form action='register.php' method='POST' enctype='multipart/form-data'>
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
                <td>foto</td> 
                <td><input type="file" name="image"/></td>
            </tr>
            <tr>
                <td colspan='2'><input type='submit' name='submit' value='Registreer'></td>
            </tr>
        </table>
        <?php
        //als er een POST REQUEST wordt gedaan
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
        //als er op de submit knop wordt geklikt
        if (isset($_POST['submit']))
        {
        //als er errors zijn, echo deze in een unordered list m.b.v. een foreach
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
        //als er geen errors zijn, echo de onderstaande link
        else
        {
            echo "<ul></br><li>Je account is aangemaakt! Klik op<a href='http://casnetwork.tk/plant/homepagina.php'> deze link</a> om in te loggen</li></ul>";
        }
    }
}
?>
        </div>
        <div class="footer">
            <div id="footerlinks">&copy; 2015</div>
            <div id="footerrechts">
            <?php
            //als er een sessie is gestart, toon de log uit knop
            if($_SESSION)
            {
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