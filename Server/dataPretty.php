<?php

$host = "127.0.0.1";
$username = "plant";
$password = (String) '$_Tan1900';
$db_name = "plant";

try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
} catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $con->prepare("SELECT * FROM plant WHERE plant_id = '{$id}'");
    $stmt->execute();
    // Get the results in array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $con->prepare("SELECT * FROM type WHERE type_id = '{$results[0]['type_id']}'");
    $stmt->execute();
    // Get the results in array
    $type = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $con->prepare("SELECT * FROM data WHERE plant_id = '{$results[0]['plant_id']}' ORDER BY dateTime");
    $stmt->execute();
    // Get the results in array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($results)) {
        // No succes
    }
    // You can remove this line if you want
    $results = array('Data' => $results);
    $results['Type'] = $type;
    $results['Graph'] = $data;

    // Now show the json tring
    echo "<pre>";
    echo json_encode($results, JSON_PRETTY_PRINT);
    echo "</pre>";
} else {
    // SQL query and prepared statement
    $stmt = $con->prepare("SELECT * FROM plant");
    $stmt->execute();
    // Get the results in array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($results); $i++) {
        $stmt = $con->prepare("SELECT * FROM type WHERE type_id = '{$results[$i]['type_id']}'");
        $stmt->execute();
        // Get the results in array
        $resultsType = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results[$i]['type'] = $resultsType;

        // Latest data
        $stmt = $con->prepare("SELECT * FROM data WHERE plant_id = '{$results[$i]['plant_id']}' ORDER BY dateTime DESC LIMIT 1");
        $stmt->execute();
        // Get the results in array
        $resultsType = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results[$i]['lastData'] = $resultsType;
    }

    // You can remove this line if you want
    $results = array('Data' => $results);

    // Now show the json tring
    echo "<pre>";
    echo json_encode($results, JSON_PRETTY_PRINT);
    echo "</pre>";
}
?>