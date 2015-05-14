<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_monitor');
        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        else
        {
            $query = "SELECT * FROM PLANT_GEGEVENS";
            $result = mysqli_query($connect, $query);
            
            $rows = mysqli_num_rows($result);
            if ($rows < 1)
            {
                    echo "<p>Er staan nog geen plantgegevens in de database</p>";
            } 
            else
            {
            ?>
            <table border="1">
            <tr>
                <td>PlantType</td>
                <td>PlantMinTemp</td>
                <td>PlantMaxTemp</td>
                <td>plantMinLicht</td>
                <td>PlantMaxLicht</td>
                <td>PlantMinVochtigheid</td>
                <td>PlantMaxVochtigheid</td>
            </tr>
            <?php
            while (($row = mysqli_fetch_assoc($result)) > 1)
            {
                echo 
                '<tr>
                    <td>' . $row['plantType'] . '</td>
                    <td>' . $row['plantMinTemp'] . '</td>
                    <td>' . $row['plantMaxTemp'] . '</td>
                    <td>' . $row['plantMinLicht'] . '</td>
                    <td>' . $row['plantMaxLicht'] . '</td>
                    <td>' . $row['plantMinVochtigheid'] . '</td>
                    <td>' . $row['plantMaxVochtigheid'] . '</td>
                </tr>'
                ;
            }
            }
        }
        ?>
    </body>
</html>