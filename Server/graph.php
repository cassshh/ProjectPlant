<?php
//start een sessie
session_start();

//include de phpgrablib(voor het maken van een grafiek)
include("graphs/phpgraphlib.php");

//zet sessie variabelen
$plant_id = $_SESSION['plant_id'];
$begindatum = $_SESSION['begindatum'];
$einddatum = $_SESSION['einddatum'];

//connect met de server
$connect = mysql_connect('localhost', 'plant', '$_Tan1900');

//als er niet kan worden connect, geef een melding
if(!$connect)
{
    echo 'connectie doet het niet';
}
//als de connectie het wel doet, ga verder
else
{
    //connect met de database plant
    mysql_select_db('plant') or die(mysql_error());

    //als de variabelen begin/einddatum leeg zijn
    if(empty($begindatum && $einddatum))
    {
        //$query wordt ingevuld met de onderstaande query
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
    else if(!empty($begindatum && $einddatum))
    {
        //$query wordt ingevuld met de onderstaande query
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
    
    //maak een array aan
    $array = array();
    
    //run een van de bovenstaande querys
    $result = mysql_query($query) or die(mysql_error());

    //als er results zijn
    if ($result) 
    {
    //zolang er regels worden opgehaald, stop de gedefinieerde gegevens in de array
    while ($row = mysql_fetch_assoc($result)) 
        {
            $date = $row["dateTime"];
            $temperature = $row["temp"];
            $array[$date]=$temperature;
        }
    }
//maak een grafiek aan vanuit de aangemaakte array    
$graph = new PHPGraphLib(750,500);
$graph->addData($array);
$graph->setTitle("temperatuur/tijd");
$graph->setGradient("lime", "red");
$graph->setBarOutlineColor("black");
$graph->setLineColor("red");
$graph->setbars(false);
$graph->setLine(true);
$graph->createGraph();
}
?>