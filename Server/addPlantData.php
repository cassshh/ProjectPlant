<?php

for ($i = 0; $i < 10; $i++) {

$plantID = 1;
$temp = rand(0,40);
$light = rand(10,100);
$moist = rand(0,99);
$dateTime = date("Y-m-d H:i:s", time());

//2015-05-21 02:08:12
echo($dateTime);

$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');
        if (!$connect)
        {
            DIE('Kan geen verbinding maken: ' . mysqli_error());
        } 
        else
        {
            $query = "INSERT INTO data VALUES ('$plantID' , '$temp', '$light', '$moist', '$dateTime')";        
            $result = mysqli_query($connect, $query);
            if($result)
            {
                echo ' De random plantgegevens zijn succesvol toegevoegd aan de database';
            }
            else
            {
                echo ' Er ging iets mis bij het aanmaken van de gegevens';
            }      
        }
		
		echo "\r\n";
mysqli_close($connect);

sleep(60);

}

?>