<?php
include("graphs/phpgraphlib.php");
$graph=new PHPGraphLib(500,350); 

$connect = mysql_connect('localhost', 'plant', '$_Tan1900');
if(!$connect)
{
    echo 'connectie doet het niet';
}
else
{
    mysql_select_db('plant_data') or die(mysql_error());
    $query = "SELECT temp, moist FROM data";

    $array = array();
    $result = mysql_query($query) or die(mysql_error());

    if ($result) 
    {
    while ($row = mysql_fetch_assoc($result)) 
        {
            $temperature = $row["temp"];
            $moisture = $row["moist"];
            $array[$temperature]=$moisture;
        }
    }
$graph->addData($array);
$graph->setTitle("Plant temperatuur/vochtigheid");
$graph->setGradient("lime", "green");
$graph->setBarOutlineColor("black");
$graph->createGraph();
}
?>