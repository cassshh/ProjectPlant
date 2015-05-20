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
$db_name = "plant_monitor";

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
}catch(PDOException $exception){
    echo "Connection error: " . $exception->getMessage();
}

// parameter
//$company_id = isset($_GET['company_id']) ? $_GET['company_id'] : die();

// SQL query and prepared statement
$stmt = $con->prepare("SELECT * FROM PLANT");
//$stmt->bindParam(':company_id', $company_id);
$stmt->execute();

// get the results in array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// you can remove this line if you want
$results = array('Data' => $results);

// now show the json tring
echo "<pre>";
echo json_encode($results, JSON_PRETTY_PRINT);
echo "</pre>";
?>