<?php

// Start een sessie
session_start();

// Include de phpgrablib(voor het maken van een grafiek)
include("graphs/phpgraphlib.php");

// Zet sessie variabelen
$plant_id = $_SESSION['plant_id'];
$begindatum = $_SESSION['begindatum'];
$einddatum = $_SESSION['einddatum'];

// Connect met de server
$connect = mysql_connect('localhost', 'plant', '$_Tan1900');

// Als er niet kan worden connect, geef een melding
if (!$connect) {
    echo 'connectie doet het niet';
}
// Als de connectie het wel doet, ga verder
{
    // Connect met de database plant
    mysql_select_db('plant') or die(mysql_error());

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
        ON plant.plant_id = data.plant_id
        WHERE plant.plant_id = '$plant_id'
        ORDER BY dateTime DESC
        LIMIT 0, 24
        ";
    }

    // Als de variabelen begin/einddatum niet leeg zijn
    else if (!empty($begindatum && $einddatum)) {
        // $query wordt ingevuld met een begin/einddatum
        $query = "
        SELECT temp,
        dateTime,
        light,
        moist,
        data.plant_id
        FROM data
        INNER JOIN plant
        ON plant.plant_id = data.plant_id
        WHERE plant.plant_id = '$plant_id'
        AND dateTime between '$begindatum' and '$einddatum'
        ORDER BY dateTime DESC
        ";
    }

    // Maak een array aan
    $array = array();

    // Run een van de bovenstaande querys
    $result = mysql_query($query) or die(mysql_error());

    // Als er results zijn
    if ($result) {

        // Zolang er regels worden opgehaald, stop de gedefinieerde gegevens in de array
        while ($row = mysql_fetch_assoc($result)) {
            $date = $row["dateTime"];
            $moist = $row["moist"];
            $array[$date] = $moist;
        }
    }

// Maak een grafiek aan vanuit de aangemaakte array    
    $graph = new PHPGraphLib(750, 500);
    $graph->addData($array);
    $graph->setTitle("plant vochtigheid/tijd");
    $graph->setGradient("lime", "green");
    $graph->setBarOutlineColor("black");
    $graph->setLineColor("blue");
    $graph->setbars(false);
    $graph->setLine(true);
    $graph->createGraph();
}
?>