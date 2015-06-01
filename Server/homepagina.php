<?php
session_start();
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
                            <?php
                            if($_SESSION)
                            {
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
                            <!--<li><a href='plantdata.php'>Gegevens</a></li>!-->
                            <li><a href='plants.php'>kies je plant</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <?php
            if(!$_SESSION)
            {
            ?>
            <li>	
                <a href='register.php' class='nav-item'>Registreer</a>
            </li>
            <?php
            }
            ?>
            <li>
        </ul>
        <div class='title'><p>Project Plant</p>
        </div>
    </nav>
    <div class='content'>
        <div class='login'>
            <?php
            if(!$_SESSION)
            {
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
            ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['submit']))
    {
        $errors = [];
        if (empty($_POST['username']))
        {
            array_push($errors, 'Er is geen gebruikersnaam ingevuld');
        } 
        else if (empty($_POST['password']))
        {
            array_push($errors, 'Er is geen wachtwoord ingevoerd');
        }
        if (count($errors) > 0)
        {
            foreach ($errors as $error)
            {
                    echo '</br><p>' . $error . '</p>';
            }
        } 
        else
        {
            $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');
            //$connect = mysqli_connect('localhost', '', '', 'test');
            $query = "SELECT * FROM login WHERE username='" . $_POST['username'] . "' AND password='" . $_POST['password'] . "'";
            $result = mysqli_query($connect, $query);
            
            $row = mysqli_fetch_assoc($result);
            if ($row == TRUE)
            {
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['password'] = $_POST['password'];
                    $_SESSION['user_id'] = $row['user_id'];
            }
            
            $count = mysqli_num_rows($result);
            
            if ($count == 1)
            {
                echo '</br>You have successfully logged in';    
                //header("location: homepagina.php?id={$row['user_id']}");
                header ("location: http://plant.serverict.nl/homepagina.php");
            } 
            else
            {
                    echo '<p></br>deze combinatie van gebruikersnaam en wachtwoord bestaat niet</p>';
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
            if($_SESSION)
            {
                echo "<a class='visited' href='http://plant.serverict.nl/logout.php' class='nav-item'>Log uit</a>"; 
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>