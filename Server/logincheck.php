<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        $errors = [];
        if (empty($_POST['gebruikersnaam'])) {
            array_push($errors, 'Er is geen gebruikersnaam ingevuld');
        } else if (empty($_POST['wachtwoord'])) {
            array_push($errors, 'Er is geen wachtwoord ingevoerd');
        }
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo '</br><p>' . $error . '</p>';
            }
        } else {
            $connect = mysqli_connect('localhost', 'plant', '$_Tan1900', 'plant_login');
            $query = "SELECT * FROM plant_data WHERE username='" . mysqli_real_escape_string($_POST['username']) . "' AND password='" . $_POST['password'] . "'";
            $result = mysqli_query($connect, $query);

            $row = mysqli_fetch_assoc($result);
            if ($row == TRUE) {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $_POST['password'];
                $_SESSION['user_id'] = $row['user_id'];
            }

            $count = mysqli_num_rows($result);
            if ($count == 1) {
                header("location: homepagina.php?id={$row['user_id']}");
            } else {
                echo '<p></br>deze combinatie van gebruikersnaam en wachtwoord bestaat niet</p>';
            }
        }
    }
}
?>