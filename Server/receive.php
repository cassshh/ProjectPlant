<?php
session_start();
$plantNaam = $_SESSION['plantNaam'];

$plantType = $_SESSION['plantType'];

$temperatuur = $_SESSION['temperatuur'];

$plantVochtigheid = $_SESSION['plantVochtigheid'];

$plantLichtNiveau = $_SESSION['plantLichtNiveau'];

$meetTijd = $_SESSION['meetTijd'];

$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_monitor');
        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        else
        {
            $query = "INSERT INTO PLANT VALUES ('plantID' , '$plantNaam', '$plantType', '$temperatuur', '$plantVochtigheid', '$plantLichtNiveau', '$meetTijd')";        
            $result = mysqli_query($connect, $query);
            if($result)
            {
                echo 'heuuhh!';
            }
            else
            {
                echo 'werkt niet';
            }      
        }
?>

