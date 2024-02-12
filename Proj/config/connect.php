<?php
    $connect = mysqli_connect('localhost', 'root', '', 'будівельна_організація');
    if(!$connect) {
        die('Помилка підключення до бази даних');
    }
?>