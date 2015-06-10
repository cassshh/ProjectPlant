<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        //zet sessie variabelen
        $image = $_SESSION['image'];
        $user = $_SESSION['username'];
        
        //connect met de server/database
        $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');
        
        //als er niet kan worden geconnect, geef een melding
        if (!$connect)
        {
            DIE('could not connect: ' . mysqli_error());
        } 
        
        //wordt er wel geconnect, ga verder met de onderstaande code
        else
        {
            //query om plantgegevens op te halen
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
            
            //run de query
            $result = mysqli_query($connect, $query);
            
            //tel het aantal records dat wordt opgehaald
            $rows = mysqli_num_rows($result);
            
            //als er geen records worden opgehaald, geef een melding
            if ($rows < 1)
            {
                    echo "<p>Er staan nog geen plantgegevens in de database</p>";
            } 
            
            //zijn er wel records, maak dan een tabel aan
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
            
            //zolang er records worden opgehaald
            while (($row = mysqli_fetch_array($result)) > 1)
            {
                //maak een sessie variable aan en vul deze in in de tabel
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