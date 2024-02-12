<?php 
    require_once 'config\connect.php';
    $plot_name = $_GET['pl'];

    $plot = mysqli_query($connect, "SELECT * FROM ділянка WHERE назва='$plot_name'");
    $plot = mysqli_fetch_assoc($plot);
    $pl_id = $plot['id'];

    // $plots = mysqli_query($connect, "SELECT  d.id AS №, d.Назва AS Ділянка, i.ПІБ AS Керівник
    //     FROM Ділянка d 
    //     JOIN Інженерно_технічний_персонал i ON d.id_керівника = i.id
    //     WHERE d.id_управління='$gov_id' AND i.Посада LIKE '%керівник%';");
    // $plots = mysqli_fetch_all($plots);

    $itp = mysqli_query($connect, "SELECT id, ПІБ, Посада, id_ділянки_інженера, id_управління_інженера
    FROM Інженерно_технічний_персонал 
    WHERE id_ділянки_інженера='$pl_id';");
    $itp = mysqli_fetch_all($itp);


    $objs = mysqli_query($connect, "SELECT o.назва AS обєкт, d.назва AS ділянка, u.назва AS управління, g.початок_будування, g.планове_закінчення_будівництва, g.кошторис
        FROM обєкт o
        JOIN ділянка d ON o.id_ділянки = d.id
        JOIN Управління u ON o.id_управління = u.id
        JOIN графік_обєкта g ON o.id = g.id_обєкта
        WHERE o.id_ділянки = '$pl_id';");
    $objs = mysqli_fetch_all($objs);


    $terms = mysqli_query($connect, "SELECT w.вид AS робота, d.назва AS ділянка, u.назва AS управління, w.заплановане_закінчення, w.фактичне_закінчення
    FROM роботи w
    JOIN ділянка d ON w.id_ділянки = d.id
    JOIN управління u ON w.id_управління = u.id
    WHERE u.id = '$pl_id' AND w.фактичне_закінчення > w.заплановане_закінчення;");
    $terms = mysqli_fetch_all($terms);


    $materials = mysqli_query($connect, "SELECT m.назва AS матеріал, f.кількість AS кількість_матеріалу, f.запланована_сума, f.фактична_сума, d.назва AS ділянка, u.назва AS управління
    FROM будівельні_матеріали m
    JOIN фактичні_витрати_матеріалів f ON m.id = f.id_матеріалу
    JOIN роботи w ON f.id_роботи = w.id
    JOIN ділянка d ON w.id_ділянки = d.id
    JOIN управління u ON w.id_управління = u.id
    WHERE d.id = '$pl_id' AND f.фактична_сума > f.запланована_сума;");
    $materials = mysqli_fetch_all($materials);     

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <title>Ділянка</title>
</head>
<body>
    <h2>Ділянка: <?= $plot_name ?></h2>

    <h3>Об'єкти, які зводяться на ділянці та їх графіки:</h3>

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
            foreach($objs as $obj) {
             ?>
                <tr>
                    <td><a href="objects.php?obj=<?= $obj[0]?>"><?= $obj[0] ?></a></td>
                    <td><?= $obj[1] ?></td>
                    <td><?= $obj[2] ?></td>
                    <td><?= $obj[3] ?></td>
                    <td><?= $obj[4] ?></td>
                    <td><?= $obj[5] ?></td>                       
                </tr>
             <?php
            }
        ?> 
    </table>


    


    <h3>Переглянути детальніше:</h3>

    <button onclick="changingContent1()">список фахівців інженерно-технічного складу</button>
    <main id="button1">
        <h3>Список фахівців інженерно-технічного складу ділянки:</h3>
        <table>
        <tr> 
            <th>№</th>
            <th>ПІБ</th>
            <th>Посада</th>
            <th>Ділянка</th>
            <th>Управління</th>            
        </tr>
        <?php
            foreach($itp as $i) {
             ?>
                <tr>
                    <td><?= $i[0] ?></td>
                    <td><?= $i[1] ?></td>
                    <td><?= $i[2] ?></td>
                    <td><?= $i[3] ?></td>
                    <td><?= $i[4] ?></td>                    
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>

    <button onclick="changingContent2()">роботи, з перевищенням термінів виконання</button>
    <main id="button2">
        <h3>перелік видів будівельних робіт, за якими мало місце перевищення термінів виконання на ділянці</h3>
        <table>
        <tr> 
            <th>робота</th>
            <th>ділянка</th>
            <th>управління</th>
            <th>заплановане закінчення</th>  
            <th>фактичне закінчення</th>         
        </tr>
        <?php
            foreach($terms as $term) {
             ?>
                <tr>
                    <td><?= $term[0] ?></td>
                    <td><?= $term[1] ?></td>
                    <td><?= $term[2] ?></td>    
                    <td><?= $term[3] ?></td>  
                    <td><?= $term[4] ?></td>                     
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>


    <button onclick="changingContent3()">матеріали (перевищення кошторису)</button>
    <main id="button3">
        <h3>перелік матеріалів, з перевищенням суми за кошторисом на ділянці</h3>
        <table>
        <tr> 
            <th>матеріал</th>
            <th>кількість матеріалу</th>
            <th>запланована_сума</th>
            <th>фактична_сума</th>  
            <th>ділянка</th>   
            <th>управління</th>         
      
        </tr>
        <?php
            foreach($materials as $mat) {
             ?>
                <tr>
                    <td><?= $mat[0] ?></td>
                    <td><?= $mat[1] ?></td>
                    <td><?= $mat[2] ?></td>    
                    <td><?= $mat[3] ?></td>  
                    <td><?= $mat[4] ?></td>
                    <td><?= $mat[5] ?></td>                     
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