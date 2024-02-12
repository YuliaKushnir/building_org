<?php 
    require_once '..\Lab5\config\connect.php';
    $title = $_POST['title'];
    $area = $_POST['area'];
    $population = $_POST['population'];
    $countryHiddenName = $_POST['countryHiddenName'];
    mysqli_query($connect, "UPDATE країни SET країна = '$title', площа_країни = '$area', населення_країни = '$population' 
        WHERE країна = '$countryHiddenName'");
    header('Location: /');
?>