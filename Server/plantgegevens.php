<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $image = $_SESSION['image'];
        $user = $_SESSION['username'];
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_data');
        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        else
        {
            $query = "
                    SELECT login.username,
                    login.image,
                    plant.name,
                    type.name
                    FROM plant
                    INNER JOIN login
                    ON plant.user_id = login.user_id
                    INNER JOIN type
                    ON plant.type_id = type.type_id
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
                <td>Gebruiker</td>
                <td>Plantnaam</td>
                <td>plantsoort</td>
            </tr>
            <?php
            while (($row = mysqli_fetch_array($result)) > 1)
            {
                $_SESSION['data_name'] = $row['name'];
                echo 
                '<tr>
                    <td>' . $row['username'] . '</br><img id="image" src="data:image/jpeg;base64,' . base64_encode($row['image']) . '"/></td>
                    <td>' . $row[2] . '</td>
                    <td>' . $row['name'] . '</td>
                </tr>'
                ;
            }
            }
        }
        ?>
    </body>
</html>