<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');
        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        else
        {
            $query = "SELECT * FROM type";
            $result = mysqli_query($connect, $query);
            
            $rows = mysqli_num_rows($result);
            if ($rows < 1)
            {
                    echo "<p>Er staan nog geen plantgegevens in de database</p>";
            } 
            else
            {
            ?>
            <table>
            <tr>
                <td>Plant ID</td>
                <td>Plantnaam</td>
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
                    <td>' . $row['type_id'] . '</td>
                    <td>' . $row['name'] . '</td>
                    <td>' . $row['minTemp'] . '</td>
                    <td>' . $row['maxTemp'] . '</td>
                    <td>' . $row['minLight'] . '</td>
                    <td>' . $row['maxLight'] . '</td>
                    <td>' . $row['minMoist'] . '</td>
                    <td>' . $row['maxMoist'] . '</td>
                </tr>'
                ;
            }
            }
        }
        ?>
    </body>
</html>