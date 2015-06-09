<?php
session_start();
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
        <div class='login'>
            <?php
            if(!$_POST['submit'])
            {
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
            $query = "
                    SELECT * 
                    FROM login 
                    WHERE username='" . mysqli_real_escape_string($connect, $_POST['username']) . "'
                    AND password='" . mysqli_real_escape_string($connect, $_POST['password']) . "'";
            
            $result = mysqli_query($connect, $query);
            
            $row = mysqli_fetch_assoc($result);
            if(!$row)
            {
                echo '<p></br>deze combinatie van gebruikersnaam en wachtwoord bestaat niet</p>';
            }
            else if ($row == TRUE)
            {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $_POST['password'];
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['image'] = $row['image'];
                $_SESSION['image_name'] = $row['image_name'];
            
                $query2 = "
                        SELECT plant_id,
                        name,
                        type_id,
                        user_id,
                        plant_type
                        FROM plant
                        ";
            
                $result2 = mysqli_query($connect, $query2);
                $row2 = mysqli_fetch_assoc($result2);
                if($row2 == TRUE)
                {
                    $_SESSION['plant_id'] = $row2['plant_id'];
                    $_SESSION['name'] = $row2['name'];
                    $_SESSION['plant_type'] = $row2['plant_type'];
                    $_SESSION['type_id'] = $row2['type_id'];
                }


                $count = mysqli_num_rows($result);

                if ($count == 1)
                {
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
            if($_SESSION)
            {
                echo "<a class='test' href='http://casnetwork.tk/plant/logout.php' class='nav-item'>Log uit</a>"; 
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>