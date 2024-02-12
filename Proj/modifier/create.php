<?php 
    require_once '..\Lab5\config\connect.php';
    $title = $_POST['title'];
    $area = $_POST['area'];
    $population = $_POST['population'];

    mysqli_query($connect, "INSERT INTO країни(країна, площа_країни, населення_країни) VALUES ('$title', '$area', '$population')");

    header('Location: /');
?>