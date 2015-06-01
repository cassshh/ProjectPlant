<?php
//session_start();

$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (isset($_POST['submit']))
{
$errors = [];

if (!(empty($_POST['plant_name'])))
{
    $query = "SELECT * FROM login WHERE username='" . mysqli_real_escape_string($connect, $_POST['plant_name']) . "'";
    $query2 = "SELECT type.name FROM type";
    
    $result = mysqli_query($connect, $query);
    $result2 = mysqli_query($connect, $query2);
    
    if (mysqli_num_rows($result) > 0)
    {
        array_push($errors, 'Deze plantnaam bestaat al :(');
    }
    } 
    else
    {
        array_push($errors, 'Je bent vergeten een naam in te vullen..');
    }
    
    if (mysqli_num_rows($result2) < strlen($result2))
    {
        array_push($errors, 'Er ging iets mis met het ophalen van de plantgegevens');
    }
    else
    {
        $row = mysqli_fetch_assoc($result2);
        if($row)
        {
            echo $name = $row['name'];
        }
        ?>
        <tr>
            <td>Plant type</td>
            <td><select name='planttype' style='width: 100%'>
                <option value='' name='Cactus'>Cactus</option>
                <option value='Boom' name='Boom'>Boom</option>
                <option value='Aloe Vera' name='Aloe Vera'>Aloe Vera</option>
                <option value='Palm' name='Palm'>Palm</option>
                <option value='Yolo' name='Yolo'>Yolo</option>
                <option value='Swag' name='Swag'>Swag</option>
                </select>
            </td>
        </tr>
        <?php
    }
    
    if (count($errors) === 0)
    {
        $query = "INSERT INTO plant VALUES('NULL', '" . $_POST['plant_name'] . "', 'NULL', 'NULL')";

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
                        </ul>
                    </div>
                </div>
            </li>
            <li>	
                <a href='#' class='nav-item'>Mijn planten</a>
                <div class='nav-content'>
                    <div class='nav-sub'>
                        <ul>
                            <li><a href='plantdata.php'>Planten gegevens</a></li>
                            <li><a href='add_plant.php'>Registreer een plant</a></li>
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
        <div class='title'><p>Project Plant</p></div>
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
            echo "<ul></br><li>Je plant is toegevoegd aan de database!</li></ul>";
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