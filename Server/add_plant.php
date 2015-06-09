<?php
session_start();
$user = $_SESSION['username'];
$id = $_SESSION['user_id'];
$plant_id = $_SESSION['plant_id'];
$type_id = $_SESSION['type_id'];

$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');

$query2 = "SELECT name FROM type";
$result2 = mysqli_query($connect, $query2);

if (mysqli_num_rows($result2) < strlen($result2))
{
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
                <td><select>
                <?php 
                while ($row2 = mysqli_fetch_assoc($result2))
                {
                echo '<option value="' . $row2['name'] . '">' . $row2['name'] . '</option>';
                }
                ?>
                    </select></td>
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
<?php
//if submit is pressed
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (isset($_POST['submit']))
{
$errors = [];

if (!(empty($_POST['plant_name'])))
{   
    $query = "SELECT * FROM plant WHERE name='" . mysqli_real_escape_string($connect, $_POST['plant_name']) . "'";
    
    $result = mysqli_query($connect, $query);
    
    if (mysqli_num_rows($result) > 0)
    {
        echo 'Deze plantnaam bestaat al :(' . '</br>
              Klik op <a href="http://casnetwork.tk/plant/registeredplants.php"> deze link</a> om alle geregistreerde plantnamen te bekijken';
    } 
    else
    {   
        $plant = $_POST['plant_name'];
        $plant_type = $_POST['plant_type'];
        
        $query = "INSERT INTO plant VALUES('$plant_id', '$plant', '$type_id', '$id')";
        
        $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
        
        mysqli_close($connect);
        
        if($query)
        {
            echo 'Je plant is toegevoegd aan de database!';
        }
    }
}
}
}