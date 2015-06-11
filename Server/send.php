<?php

session_start();
$plantNaam = 'Jaapie';
$_SESSION['plantNaam'] = $plantNaam;

$plantType = 'Cactus';
$_SESSION['plantType'] = $plantType;

$temperatuur = '200';
$_SESSION['temperatuur'] = $temperatuur;

$plantVochtigheid = '3';
$_SESSION['plantVochtigheid'] = $plantVochtigheid;

$plantLichtNiveau = '90';
$_SESSION['plantLichtNiveau'] = $plantLichtNiveau;

$meetTijd = '2015-05-20 11:04:23';
$_SESSION['meetTijd'] = $meetTijd;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html>
    <head>
        <style>
            table, th, td 
            {
                border: 1px solid grey;
                border-collapse: collapse;
                padding: 10px;
                width: auto;       
            }
        </style>
        <title>Overzicht van de opgehaalde plantgegevens</title>
    </head>
    <body>
        <form id='signup' action='receive.php' method='POST'>
            <table>
                <tr>
                    <td>Plantnaam:</td><td><input type='text' name='plantNaam' value=<?php echo $plantNaam ?>></td>
                </tr>
                <tr>
                    <td>Planttype:</td><td><input type='text' name ='plantType' value=<?php echo $plantType ?>></td>
                </tr>
                <tr>
                    <td>Temperatuur:</td><td><input type='text' name='temperatuur' value=<?php echo $temperatuur ?>></td>
                </tr>
                <tr>
                    <td>Plantvochtigheid:</td><td><input type='text' name='plantVochtigheid' value=<?php echo $plantVochtigheid ?>></td>
                </tr> 
                <tr>
                    <td>Plantlichtniveau:</td><td><input type='text' name='plantLichtNiveau' value=<?php echo $plantLichtNiveau ?>></td>
                </tr> 
                <tr>
                    <td>Meet/tijd datum:</td><td><input type='text' name='meetTijd' value=<?php echo $meetTijd ?>></td>
                </tr> 
                <tr>
                    <td colspan='2'><input type='submit' value='Verstuur'></td> 
                </tr>
            </table>      
        </form>
    </body>
</html>



