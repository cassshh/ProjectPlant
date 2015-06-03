<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();   
        $plant_name = $_GET['id'];
        $_SESSION['[plant_name'] = $plant_name;
        
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');

        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        else
        {
            $query = "
            SELECT temp,
            dateTime,
            light,
            moist,
            data.name
            FROM data
            INNER JOIN plant
            ON data.name = plant.name
            WHERE plant.name = '$plant_name'
            AND data.name = '$plant_name'
            ";
            
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
                <td>temperatuur</td>
                <td>plant licht niveau</td>
                <td>plant vochtigheid</td>
                <td>datum/tijd</td>
            </tr>
            <?php
            while (($row = mysqli_fetch_assoc($result)) > 1)
            {
                echo 
                '<tr>
                    <td>' . $row['temp'] . "°" . '</td>
                    <td>' . $row['light'] . '</td>
                    <td>' . $row['moist'] . '</td>
                    <td>' . $row['dateTime'] . '</td>
                </tr>'
                ;
            }
            }
        }
        ?>
    </body>
</html>