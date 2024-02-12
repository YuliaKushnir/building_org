<?php 
    require_once 'config\connect.php';
    $obj_name = $_GET['obj'];

    $ob = mysqli_query($connect, "SELECT * FROM обєкт WHERE назва='$obj_name';");
    $ob = mysqli_fetch_row($ob);
    $obj_id = $ob[0];
    

    $brigade = mysqli_query($connect, "SELECT o.назва AS обєкт, b.назва AS бригада, r.піб AS робітник, r.посада AS посада
    FROM обєкт o
    JOIN роботи w ON o.id = w.id_обєкта
    JOIN бригади b ON w.id_бригади = b.id
    JOIN робітники r ON b.id = r.бригада
    WHERE o.назва = '$obj_name';");
    $brigade = mysqli_fetch_all($brigade);


    $tech = mysqli_query($connect, "SELECT t.назва AS техніка, t.модель, o.назва AS обєкт, t.початок_використання, t.кінець_використання 
    FROM техніка t
    JOIN обєкт o ON t.id_обєкта = o.id
    WHERE t.id_обєкта = '$obj_id';");
    $tech = mysqli_fetch_all($tech);

    
    $schedule = mysqli_query($connect, "SELECT o.назва AS обєкт, g.початок_будування, g.планове_закінчення_будівництва, g.кошторис
    FROM обєкт o
    JOIN графік_обєкта g ON o.id = g.id_обєкта
    WHERE o.id = '$obj_id';");
    $schedule = mysqli_fetch_all($schedule);
    // WHERE id_обєкта = 2 OR (початок_використання < '2023-06-01' AND кінець_використання > '2023-08-31');


    $report = mysqli_query($connect, "SELECT o.назва AS обєкт, o.тип AS тип_обєкта, g.початок_будування, g.планове_закінчення_будівництва, z.фактичне_завершення_будівництва, g.кошторис, z.фактична_сума
    FROM обєкт o
    JOIN звіт z ON o.id = z.id_обєкта
    JOIN графік_обєкта g ON o.id = g.id_обєкта
    WHERE o.id = '$obj_id';");
    $report = mysqli_fetch_all($report);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <title>Об'єкт</title>
</head>
<body>
    <h2>Об'єкт: <?= $obj_name ?></h2>

    <h3>Звіт про спорудження::</h3>

    <table>
        <tr> 
            <th>об'єкт</th>
            <th>ділянка</th>
            <th>управління</th>
            <th>початок_будування</th>
            <th>планове_закінчення_будівництва</th>  
            <th>кошторис</th>          
        </tr>
        <?php
            foreach($report as $rep) {
             ?>
                <tr>
                    <td><?= $rep[0] ?></td>
                    <td><?= $rep[1] ?></td>
                    <td><?= $rep[2] ?></td>
                    <td><?= $rep[3] ?></td>
                    <td><?= $rep[4] ?></td>
                    <td><?= $rep[5] ?></td>                       
                </tr>
             <?php
            }
        ?> 
    </table>



    <h3>Переглянути детальніше:</h3>

    <button onclick="changingContent1()">склад бригад, які працювали (працюють) на будівництві</button>
    <main id="button1">
        <h3>Склад бригад, які працювали (працюють) на будівництві:</h3>
        <table>
        <tr> 
            <th>бригада</th>
            <th>робітник</th>
            <th>посада</th>
        </tr>
        <?php
            foreach($brigade as $br) {
             ?>
                <tr>
                    <td><?= $br[1] ?></td>
                    <td><?= $br[2] ?></td>
                    <td><?= $br[3] ?></td>
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>


    <button onclick="changingContent2()">будівельна техніка</button>
    <main id="button2">
        <h3>перелік будівельної техніки, яка працювала на об'єкті</h3>
        <table>
        <tr> 
            <th>техніка</th>
            <th>модель</th>
            <th>об'єкт</th>
            <th>початок використання</th>
            <th>кінець використання</th>       
        </tr>
        <?php
            foreach($tech as $t) {
             ?>
                <tr>
                    <td><?= $t[0] ?></td>
                    <td><?= $t[1] ?></td>
                    <td><?= $t[2] ?></td> 
                    <td><?= $t[3] ?></td>   
                    <td><?= $t[4] ?></td>                         
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>


    <button onclick="changingContent3()">Графік і кошторис</button>
    <main id="button3">
        <h3>графік і кошторис на будівництво </h3>
        <table>
        <tr> 
            <th>об'єкт</th>
            <th>початок будівництва</th>
            <th>планове закінчення будівництва</th>
            <th>кошторис, $</th>
        </tr>
        <?php
            foreach($schedule as $shed) {
             ?>
                <tr>
                    <td><?= $shed[0] ?></td>
                    <td><?= $shed[1] ?></td>
                    <td><?= $shed[2] ?></td> 
                    <td><?= $shed[3] ?></td>   
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>


    <button onclick="changingContent4()">звіт про спорудження</button>
    <main id="button4">
        <h3>звіт про спорудження</h3>
        <table>
        <tr>   
            <th>об'єкт</th>
            <th>тип обєкта</th>
            <th>початок будівництва</th>
            <th>планове закінчення будівництва</th>
            <th>фактичне завершення будівництва</th>
            <th>планова сума</th>
            <th>фактична сума</th>
        </tr>
        <?php
            foreach($report as $rep) {
             ?>
                <tr>
                    <td><?= $rep[0] ?></td>
                    <td><?= $rep[1] ?></td>
                    <td><?= $rep[2] ?></td> 
                    <td><?= $rep[3] ?></td> 
                    <td><?= $rep[4] ?></td>
                    <td><?= $rep[5] ?></td>
                    <td><?= $rep[6] ?></td>    
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>
    


    <div id="content">
        <p>Оберіть пункт для відображення інформації...</p>
    </div>

    
</body>
</html>