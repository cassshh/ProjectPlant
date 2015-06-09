<?php
session_start();
$username = $_SESSION['username'];
$connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant');
if (!$connect)
{
    DIE('could not connect: ' . mysqli_error());
} 
else
{   
    $query = "
    SELECT login.user_id,
    login.username,
    plant.name,
    plant.plant_id
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
                <td>Temperatuur/tijd</td>
                <td>Vochtigheid/tijd</td>
                <td>licht/tijd</td>
            </tr>';
        while (($row = mysqli_fetch_assoc($result)) > 1)
        {
            $id = $row['plant_id'];
            $name = $row['name'];
            echo 
            "<tr>
                <td>" . $name . "</td>
                <td><a href=\"plantdata.php?id={$row['plant_id']}\" class='redButton'>Temperatuur</a></td>  
                <td><a href=\"plantdata2.php?id={$row['plant_id']}\" class='blueButton'>Vochtigheid</a></td>  
                <td><a href=\"plantdata3.php?id={$row['plant_id']}\" class='orangeButton'>Licht</a></td>  
            </tr>";
        }
    }
}