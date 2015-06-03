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
            ";
    
    $array = array();
    $result = mysql_query($query) or die(mysql_error());
    if ($result) 
    {
    while ($row = mysql_fetch_assoc($result)) 
        {
            $date = $row["dateTime"];
            $moist = $row["moist"];
            $array[$date]=$moist;
        }
    }
$graph = new PHPGraphLib(750,500);
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