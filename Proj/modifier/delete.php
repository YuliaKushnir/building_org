<?php 
    require_once 'config\connect.php';
    $country_name = $_GET['country'];
    mysqli_query($connect, "DELETE FROM країни WHERE країна='$country_name'");
    header('Location: /');