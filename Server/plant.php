<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();   
        $plant_name = $_GET['id'];
        $plant_id = $_GET['id'];
        
        $plant_id = $_SESSION['plant_id'];
        $begindatum = $_SESSION['begindatum'];
        $einddatum = $_SESSION['einddatum']; 
        
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');

        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        else
        {
        if(empty($begindatum && $einddatum))
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
            WHERE plant.plant_id = '$plant_id'
            AND data.plant_id = '$plant_id'
            ";
        }
        else if(!empty($begindatum && $einddatum))
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
            WHERE plant.plant_id = '$plant_id'
            AND dateTime between '$begindatum' and '$einddatum'
            ";
        }    
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