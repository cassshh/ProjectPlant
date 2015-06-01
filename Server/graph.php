<?php
session_start();
include("graphs/phpgraphlib.php");
$username = $_SESSION['username'];

$connect = mysql_connect('localhost', 'plant', '$_Tan1900');
if(!$connect)
{
    echo 'connectie doet het niet';
}
else
{
    mysql_select_db('plant_data') or die(mysql_error());
    
    $query = "
            SELECT data.temp,
            data.dateTime
            FROM data
            INNER JOIN login
            ON login.user_id = data.plant_id
            WHERE username = '" . $username . "' 
            order by dateTime 
            desc LIMIT 0, 15
            ";
    
    $array = array();
    $result = mysql_query($query) or die(mysql_error());
    if ($result) 
    {
    while ($row = mysql_fetch_assoc($result)) 
        {
            $date = $row["dateTime"];
            //$min = $date[1];
            $temperature = $row["temp"];
            $array[$date]=$temperature;
        }
    }
$graph = new PHPGraphLib(750,500);
$graph->addData($array);
$graph->setTitle("Plant temperatuur/min");
$graph->setGradient("lime", "green");
$graph->setBarOutlineColor("black");
$graph->setLineColor("red");
$graph->setbars(false);
$graph->setLine(true);
$graph->createGraph();
}
?>