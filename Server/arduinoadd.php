<?php

$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');
        if (!$connect)
        {
            DIE('Niet goed nie' . mysqli_error());
        } 
        else
        {
            $query = "INSERT INTO data VALUES ('' , '".$_GET["test"]."', '', '', '')";        
            $result = mysqli_query($connect, $query);
		 if($result)
            {
                echo 'Dut';
            }
			else
            {
                echo 'Dut niet';
            }      
        }


mysqli_close($connect);

?>