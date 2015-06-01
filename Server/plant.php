<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $username = $_SESSION['username'];
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');

        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        else
        {
            $query = "
            SELECT login.user_id,
            data.temp,
            data.light,
            data.moist,
            data.dateTime
            FROM data
            INNER JOIN login
            ON login.user_id = data.plant_id
            WHERE username = '" . $username . "'
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
                    <td>' . $row['temp'] . '</td>
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