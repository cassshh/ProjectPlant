<?php
// connection to the database
/*echo "<h1>Y U NO CONNECT</h1>";
if (!defined('PDO::ATTR_DRIVER_NAME')) { 
echo 'PDO unavailable'; 
} 
elseif (defined('PDO::ATTR_DRIVER_NAME')) { 
echo 'PDO available'; 
}
echo "<br/>";
ini_set('error_reporting', E_ALL|E_STRICT);
ini_set('display_errors', 1);*/


$host = "127.0.0.1";
$username = "plant";
$password = (String)'$_Tan1900';
$db_name = "plant";

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}catch(PDOException $exception){
    echo "Connection error: " . $exception->getMessage();
}


if(isset($_GET['id']) && !empty($_GET['id'])){
	$id = $_GET['id'];
	$stmt = $con->prepare("SELECT * FROM plant WHERE plant_id = '{$id}'");
	$stmt->execute();
	// get the results in array
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$stmt = $con->prepare("SELECT * FROM type WHERE type_id = '{$results[0]['type_id']}'");
	$stmt->execute();
	// get the results in array
	$type = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//$stmt = $con->prepare("SELECT * FROM data WHERE (DATEDIFF(NOW(), dateTime) <= 1) AND plant_id = '{$id}' ORDER BY dateTime");
	$stmt = $con->prepare("SELECT * FROM data WHERE plant_id = '{$results[0]['plant_id']}' ORDER BY dateTime");
	$stmt->execute();
	// get the results in array
	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if(empty($results)){
		//No succes
	}
	// you can remove this line if you want
	$results = array('Data' => $results);
	$results['Type'] = $type;
	$results['Graph'] = $data;

	// now show the json tring
	echo "<pre>";
	echo json_encode($results, JSON_PRETTY_PRINT);
	echo "</pre>";

} else {
	// SQL query and prepared statement
	$stmt = $con->prepare("SELECT * FROM plant");
	$stmt->execute();
	// get the results in array
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; $i < count($results); $i++){
		$stmt = $con->prepare("SELECT * FROM type WHERE type_id = '{$results[$i]['type_id']}'");
		$stmt->execute();
		// get the results in array
		$resultsType = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$results[$i]['type'] = $resultsType;

		//Latest data
		$stmt = $con->prepare("SELECT * FROM data WHERE plant_id = '{$results[$i]['plant_id']}' ORDER BY dateTime DESC LIMIT 1");
		$stmt->execute();
		// get the results in array
		$resultsType = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$results[$i]['lastData'] = $resultsType;
	}

	// you can remove this line if you want
	$results = array('Data' => $results);

	// now show the json tring
	echo "<pre>";
	echo json_encode($results, JSON_PRETTY_PRINT);
	echo "</pre>";
}
?>