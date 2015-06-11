<?php

include("graphs/phpgraphlib.php");
$graph = new PHPGraphLib(500, 350);

$connect = mysql_connect('localhost', '', '');
if (!$connect) {
    echo 'connectie doet het niet';
} else {
    mysql_select_db('test') or die('Could not select database');
    $query = "SELECT temp, moist FROM data order by temp asc LIMIT 0 , 4";

    $array = array();
    $result = mysql_query($query) or die(mysql_error());

    if ($result) {
        while ($row = mysql_fetch_assoc($result)) {
            $temperature = $row["temp"];
            $moisture = $row["moist"];
            $array[$temperature] = $moisture;
        }
    }

    $graph->addData($array);
    $graph->setTitle("Plant temperatuur/vochtigheid");

    // Kleur van de bars
    $graph->setGradient("lime", "green");
    $graph->setBarOutlineColor("black");

    // Bars uit/aan zetten
    //$graph->setBars(false);
    // Lijn tonen ipv bars
    //$graph->setLine(true);
    $graph->createGraph();
}
?>