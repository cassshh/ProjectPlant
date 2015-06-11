<?php

// Start een sessie
session_start();

// Zet sessie variabele
$username = $_SESSION['username'];

// Connect met de server/database
$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');

// Als er niet kan worden geconnect met de server/database, geef een melding
if (!$connect) {
    DIE('could not connect: ' . mysqli_error());
}

// Als er is geconnect, ga verder met de onderstaande code
else {
    // Query om plantgegevens op te halen
    $query = "
    SELECT login.user_id,
    login.username,
    plant.name,
    plant.plant_id
    FROM plant
    INNER JOIN login
    ON login.user_id = plant.user_id
    WHERE username = '" . $username . "'
    ";

    // Run de query
    $result = mysqli_query($connect, $query);

    // Haal de gegevens op en zet deze in $rows
    $rows = mysqli_num_rows($result);

    // Als er minder dan 1 rij wordt opgehaald, geef een melding
    if ($rows < 1) {
        echo '<p>Je hebt nog geen planten geregistreerd</p>';
    }

    // Word er meer dan 1 rij opgehaald, ga verder met de onderstaande code
    else {

        // Maak een tabel aan
        echo '<table> 
            <tr>
                <td>plantnaam</td>
                <td>Temperatuur/tijd</td>
                <td>Vochtigheid/tijd</td>
                <td>licht/tijd</td>
            </tr>';

        // Zolang er gegevens worden opgehaald, maak twee variabelen aan en vul deze in in de tabel 
        while (($row = mysqli_fetch_assoc($result)) > 1) {
            $id = $row['plant_id'];
            $name = $row['name'];
            echo
            "<tr>
                <td>" . $name . "</td>
                <td><a href=\"plantdata.php?id={$row['plant_id']}\" class='redButton'>Temperatuur</a></td>  
                <td><a href=\"plantdata2.php?id={$row['plant_id']}\" class='blueButton'>Vochtigheid</a></td>  
                <td><a href=\"plantdata3.php?id={$row['plant_id']}\" class='orangeButton'>Licht</a></td>  
            </tr>";
        }
    }
}