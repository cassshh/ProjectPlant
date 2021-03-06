<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // Start een sessie
        session_start();

        // Haal het plant_id op van de vorige pagina
        $plant_id = $_GET['id'];

        // Zet sessie variabelen
        $plant_id = $_SESSION['plant_id'];
        $begindatum = $_SESSION['begindatum'];
        $einddatum = $_SESSION['einddatum'];

        // Connect met de server/database
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');

        // Als er niet kan worden geconnect, geef een melding
        if (!$connect) {
            DIE('could not connect: ' . mysqli_error());
        }

        // Wordt er wel geconnect, ga verder met de onderstaande code
        else {

            // Als de variabelen begin/einddatum leeg zijn
            if (empty($begindatum && $einddatum)) {
                // $query wordt ingevuld met de onderstaande query
                $query = "
            SELECT temp,
            dateTime,
            light,
            moist,
            data.plant_id
            FROM data
            INNER JOIN plant
            ON data.plant_id = plant.plant_id
            WHERE plant.plant_id = '$plant_id'
            AND data.plant_id = '$plant_id'
            ORDER BY dateTime DESC;
            ";
            }
            // Als begin/einddatum niet leeg zijn
            else if (!empty($begindatum && $einddatum)) {
                // $query wordt ingevuld met de onderstaande query
                $query = "
            SELECT temp,
            dateTime,
            light,
            moist,
            data.plant_id
            FROM data
            INNER JOIN plant
            ON data.plant_id = plant.plant_id
            WHERE plant.plant_id = '$plant_id'
            AND dateTime between '$begindatum' and '$einddatum'
            ";
            }

            // Run de query
            $result = mysqli_query($connect, $query);

            // Tel de opgehaalde records
            $rows = mysqli_num_rows($result);

            // Als er geen records worden opgehaald, geef een melding
            if ($rows < 1) {
                echo "<p>Er staan nog geen plantgegevens in de database</p>";
            }
            // Worden er wel records opgehaald, maak een tabel aan
            else {
                ?>
                <table>
                    <tr>
                        <td>temperatuur</td>
                        <td>plant licht niveau</td>
                        <td>plant vochtigheid</td>
                        <td>datum/tijd</td>
                    </tr>
        <?php
        // Zolang er records worden opgehaald, vul deze in in de tabel
        while (($row = mysqli_fetch_assoc($result)) > 1) {
            echo
            '<tr>
                    <td>' . $row['temp'] . "°" . '</td>
                    <td>' . $row['light'] . '</td>
                    <td>' . $row['moist'] . '</td>
                    <td>' . $row['dateTime'] . '</td>
                </tr>'
            ;
        }
    }
}
?>
    </body>
</html>