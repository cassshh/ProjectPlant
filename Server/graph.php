<?php
session_start();
include("graphs/phpgraphlib.php");

$plant_name = $_SESSION['plant_name'];

$connect = mysql_connect('localhost', 'plant', '$_Tan1900');
if(!$connect)
{
    echo 'connectie doet het niet';
}
else
{
    mysql_select_db('plant_data') or die(mysql_error());
    
    $query = "
            SELECT temp,
            dateTime,
            light,
            moist,
            data.name
            FROM data
            INNER JOIN plant
            ON plant.name = data.name
            WHERE plant.name = '$plant_name'
            LIMIT 0, 24
            ";
    
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