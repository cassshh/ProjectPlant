<?php
session_start();
include("graphs/phpgraphlib.php");

$plant_id = $_SESSION['plant_id'];
$begindatum = $_SESSION['begindatum'];
$einddatum = $_SESSION['einddatum'];

$connect = mysql_connect('localhost', 'plant', '$_Tan1900');
if(!$connect)
{
    echo 'connectie doet het niet';
}
else
{
    mysql_select_db('plant') or die(mysql_error());

if(empty($begindatum && $einddatum))
{
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
            LIMIT 0, 24
            ";
}
else if(!empty($begindatum && $einddatum))
{
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
            ";
}   
    $array = array();
    $result = mysql_query($query) or die(mysql_error());
    if ($result) 
    {
    while ($row = mysql_fetch_assoc($result)) 
        {
            $date = $row["dateTime"];
            $temperature = $row["temp"];
            $array[$date]=$temperature;
        }
    }
    
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