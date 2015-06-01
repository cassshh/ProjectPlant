<?php
session_start();
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
    login.username,
    plant.name
    FROM plant
    INNER JOIN login
    ON login.user_id = plant.user_id
    WHERE username = '" . $username . "'
    ";
    
    $result = mysqli_query($connect, $query);

    $rows = mysqli_num_rows($result);
    if ($rows < 1)
    {
        echo '<p>Je hebt nog geen planten geregistreerd</p>';
    } 
    else
    {
    echo '<table> 
            <tr>
                <td>plantnaam</td>
                <td>Plantdetails</td>
            </tr>';
        while (($row = mysqli_fetch_assoc($result)) > 1)
        {
            echo 
            '<tr>
                <td>' . $row['name'] . '</td>
                <td><a href="plantdata.php" class="link">Bekijk hier de gegevens</a></td>
            </tr>';
        }
    }
}