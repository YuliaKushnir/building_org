<?php 
    require_once 'config\connect.php';
    $gov_name = $_GET['gov'];
    $govern = mysqli_query($connect, "SELECT * FROM управління WHERE назва='$gov_name'");
    $govern = mysqli_fetch_assoc($govern);
    $gov_id = $govern['id'];

    $plots = mysqli_query($connect, "SELECT  d.id AS №, d.Назва AS Ділянка, i.ПІБ AS Керівник
        FROM Ділянка d 
        JOIN Інженерно_технічний_персонал i ON d.id_керівника = i.id
        WHERE d.id_управління='$gov_id' AND i.Посада LIKE '%керівник%';");
    $plots = mysqli_fetch_all($plots);

    $itp = mysqli_query($connect, "SELECT id, ПІБ, Посада, id_ділянки_інженера, id_управління_інженера
    FROM Інженерно_технічний_персонал 
    WHERE id_управління_інженера='$gov_id';");
    $itp = mysqli_fetch_all($itp);


    $objs = mysqli_query($connect, "SELECT o.назва AS обєкт, d.назва AS ділянка, u.назва AS управління, g.початок_будування, g.планове_закінчення_будівництва, g.кошторис
        FROM обєкт o
        JOIN ділянка d ON o.id_ділянки = d.id
        JOIN Управління u ON o.id_управління = u.id
        JOIN графік_обєкта g ON o.id = g.id_обєкта
        WHERE o.id_управління = '$gov_id';");
    $objs = mysqli_fetch_all($objs);


    $tech = mysqli_query($connect, "SELECT техніка.назва AS техніка, техніка.модель, обєкт.назва AS обєкт 
    FROM техніка 
    JOIN обєкт ON техніка.id_обєкта = обєкт.id
    WHERE техніка.id_управління = '$gov_id';");
    $tech = mysqli_fetch_all($tech);

    $works = mysqli_query($connect, "SELECT o.назва AS обєкт, u.назва AS управління, w.вид AS робота, w.початок AS початок_роботи, w.фактичне_закінчення AS закінчення_роботи
        FROM обєкт o
        JOIN управління u ON o.id_управління = u.id
        JOIN роботи w ON o.id = w.id_обєкта
        WHERE u.id = '$gov_id' AND w.Вид = 'цегляні роботи' 
            AND (w.Початок BETWEEN '2023-01-01' AND '2023-03-31' 
                OR w.фактичне_закінчення BETWEEN '2023-01-01' AND '2023-03-31');");
    $works = mysqli_fetch_all($works);


    $terms = mysqli_query($connect, "SELECT w.вид AS робота, d.назва AS ділянка, u.назва AS управління, w.заплановане_закінчення, w.фактичне_закінчення
    FROM роботи w
    JOIN ділянка d ON w.id_ділянки = d.id
    JOIN управління u ON w.id_управління = u.id
    WHERE u.id = '$gov_id' AND w.фактичне_закінчення > w.заплановане_закінчення;");
    $terms = mysqli_fetch_all($terms);

    $materials = mysqli_query($connect, "SELECT m.назва AS матеріал, f.кількість AS кількість_матеріалу, f.запланована_сума, f.фактична_сума, d.назва AS ділянка, u.назва AS управління
    FROM будівельні_матеріали m
    JOIN фактичні_витрати_матеріалів f ON m.id = f.id_матеріалу
    JOIN роботи w ON f.id_роботи = w.id
    JOIN ділянка d ON w.id_ділянки = d.id
    JOIN управління u ON w.id_управління = u.id
    WHERE u.id = '$gov_id' AND f.фактична_сума > f.запланована_сума;");
    $materials = mysqli_fetch_all($materials);     


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <title>Управління</title>
</head>
<body>
    <h2>Будівельне управління: <?= $gov_name ?></h2>
    <h3>Ділянки управління</h3>
    <p>Клікніть назву ділянки, щоб переглянути детальніше</p>


    <table>
        <tr>
            <th>№</th>
            <th>Ділянка</th>
            <th>Керівник</th>
        </tr>
        <?php
            foreach($plots as $plot) {
             ?>
                <tr>
                    <td><?= $plot[0] ?></td>
                    <td><a href="plot.php?pl=<?= $plot[1]?>"><?= $plot[1] ?></a></td>
                    <td><?= $plot[2] ?></td>
                    
                </tr>
             <?php
            }
        ?> 
    </table>


    <h3>Переглянути детальніше:</h3>

    <button onclick="changingContent1()">список фахівців інженерно-технічного складу</button>
    <main id="button1">
        <h3>список фахівців інженерно-технічного складу організації</h3>
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


    <button onclick="changingContent2()">перелік об'єктів та графіки</button>
    <main id="button2">
        <h3>Перелік об’єктів, що зводяться будівельним управлінням та графіки їх зведення.</h3>
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
                    <td><?= $obj[0] ?></td>
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
    </main>


    <button onclick="changingContent3()">будівельна техніка</button>
    <main id="button3">
        <h3>перелік будівельної техніки, доданої будівельному управлінню</h3>
        <table>
        <tr> 
            <th>техніка</th>
            <th>модель</th>
            <th>об'єкт</th>       
        </tr>
        <?php
            foreach($tech as $t) {
             ?>
                <tr>
                    <td><?= $t[0] ?></td>
                    <td><?= $t[1] ?></td>
                    <td><?= $t[2] ?></td>                       
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>
        

    <button onclick="changingContent4()">об'єкти (цегляні роботи, І квартал, 2022)</button>
    <main id="button4">
        <h3>перелік об’єктів, що зводяться в будівельному управлінні, на яких в перший квартал 2023 виконувались цегляні роботи</h3>
        <table>
        <tr> 
            <th>об'єкт</th>
            <th>управління</th>
            <th>робота</th>
            <th>початок роботи</th>  
            <th>закінчення роботи</th>         
        </tr>
        <?php
            foreach($works as $work) {
             ?>
                <tr>
                    <td><?= $work[0] ?></td>
                    <td><?= $work[1] ?></td>
                    <td><?= $work[2] ?></td>    
                    <td><?= $work[3] ?></td>  
                    <td><?= $work[4] ?></td>                     
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>

    <button onclick="changingContent5()">роботи, з перевищенням термінів виконання</button>
    <main id="button5">
        <h3>перелік видів будівельних робіт, за якими мало місце перевищення термінів виконання в управлінні</h3>
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

    <button onclick="changingContent6()">матеріали (перевищення кошторису)</button>
    <main id="button6">
        <h3>перелік матеріалів, з перевищенням суми за кошторисом в управлінні</h3>
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