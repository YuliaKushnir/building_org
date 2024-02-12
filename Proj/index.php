<?php
    require_once 'config/connect.php';
    $governances = mysqli_query($connect, "SELECT u.id AS №, u.Назва AS Управління, i.ПІБ AS Керівник
        FROM Управління u
        JOIN Інженерно_технічний_персонал i ON u.id_керівника = i.id 
        WHERE i.Посада LIKE '%керівник%';");
    $governances = mysqli_fetch_all($governances);

    $itp = mysqli_query($connect, "SELECT id, ПІБ, Посада, id_ділянки_інженера, id_управління_інженера
    FROM Інженерно_технічний_персонал");
    $itp = mysqli_fetch_all($itp);


    $works = mysqli_query($connect, "SELECT o.назва AS обєкт, u.назва AS управління, w.вид AS робота, w.початок AS початок_роботи, w.фактичне_закінчення AS закінчення_роботи
        FROM обєкт o
        JOIN управління u ON o.id_управління = u.id
        JOIN роботи w ON o.id = w.id_обєкта
        WHERE (w.Вид = 'цегляні роботи' OR w.Вид = 'зведення фундаменту') AND (w.Початок BETWEEN '2023-01-01' AND '2023-03-31' 
                OR w.фактичне_закінчення BETWEEN '2023-01-01' AND '2023-03-31');");
    $works = mysqli_fetch_all($works);
    

    $terms = mysqli_query($connect, "SELECT w.вид AS робота, d.назва AS ділянка, u.назва AS управління, w.заплановане_закінчення, w.фактичне_закінчення
    FROM роботи w
    JOIN ділянка d ON w.id_ділянки = d.id
    JOIN управління u ON w.id_управління = u.id
    WHERE w.фактичне_закінчення > w.заплановане_закінчення;");
    $terms = mysqli_fetch_all($terms);

    $materials = mysqli_query($connect, "SELECT m.назва AS матеріал, f.кількість AS кількість_матеріалу, f.запланована_сума, f.фактична_сума, d.назва AS ділянка, u.назва AS управління
    FROM будівельні_матеріали m
    JOIN фактичні_витрати_матеріалів f ON m.id = f.id_матеріалу
    JOIN роботи w ON f.id_роботи = w.id
    JOIN ділянка d ON w.id_ділянки = d.id
    JOIN управління u ON w.id_управління = u.id
    WHERE f.фактична_сума > f.запланована_сума;");
    $materials = mysqli_fetch_all($materials);

    // ========================     ЗАПИТ 12  ===============================================
    $object_works_br1 = mysqli_query($connect, "SELECT w.вид AS робота, b.назва AS бригада, o.назва AS обєкт, w.початок AS початок_роботи, w.фактичне_закінчення AS закінчення_роботи
    FROM роботи w
    JOIN бригади b ON w.id_бригади = b.id
    JOIN обєкт o ON w.id_обєкта = o.id
    WHERE b.id = 1 AND (w.початок BETWEEN '2023-01-01' AND '2023-03-31' OR w.фактичне_закінчення BETWEEN '2023-01-01' AND '2023-03-31');");
    $object_works_br1 = mysqli_fetch_all($object_works_br1);

    $object_works_br2 = mysqli_query($connect, "SELECT w.вид AS робота, b.назва AS бригада, o.назва AS обєкт, w.початок AS початок_роботи, w.фактичне_закінчення AS закінчення_роботи
    FROM роботи w
    JOIN бригади b ON w.id_бригади = b.id
    JOIN обєкт o ON w.id_обєкта = o.id
    WHERE b.id = 2 AND (w.початок BETWEEN '2023-01-01' AND '2023-03-31' OR w.фактичне_закінчення BETWEEN '2023-01-01' AND '2023-03-31');");
    $object_works_br2 = mysqli_fetch_all($object_works_br2);

    $object_works_br3 = mysqli_query($connect, "SELECT w.вид AS робота, b.назва AS бригада, o.назва AS обєкт, w.початок AS початок_роботи, w.фактичне_закінчення AS закінчення_роботи
    FROM роботи w
    JOIN бригади b ON w.id_бригади = b.id
    JOIN обєкт o ON w.id_обєкта = o.id
    WHERE b.id = 3 AND (w.початок BETWEEN '2023-01-01' AND '2023-03-31' OR w.фактичне_закінчення BETWEEN '2023-01-01' AND '2023-03-31');");
    $object_works_br3 = mysqli_fetch_all($object_works_br3);
    // =======================================================================



    // ================= ЗАПИТ 13 ======================================
    $brigade1 = mysqli_query($connect, "SELECT b.назва AS бригада, w.вид AS робота, o.назва AS обєкт, w.початок AS початок_роботи, w.фактичне_закінчення AS закінчення_роботи
    FROM бригади b
    JOIN роботи w ON b.id = w.id_бригади
    JOIN обєкт o ON w.id_обєкта = o.id
    WHERE w.вид = 'цегляні роботи' AND (w.початок BETWEEN '2023-01-01' AND '2023-03-31' OR w.фактичне_закінчення BETWEEN '2023-01-01' AND '2023-03-31');");
    $brigade1 = mysqli_fetch_all($brigade1);

    $brigade2 = mysqli_query($connect, "SELECT b.назва AS бригада, w.вид AS робота, o.назва AS обєкт, w.початок AS початок_роботи, w.фактичне_закінчення AS закінчення_роботи
    FROM бригади b
    JOIN роботи w ON b.id = w.id_бригади
    JOIN обєкт o ON w.id_обєкта = o.id
    WHERE w.вид = 'зведення фундаменту' AND (w.початок BETWEEN '2023-01-01' AND '2023-03-31' OR w.фактичне_закінчення BETWEEN '2023-01-01' AND '2023-03-31');");
    $brigade2 = mysqli_fetch_all($brigade2);

    // =======================================================


    
?> 
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <metaname="Author" content="Юлія">
    <META NAME="ROBOTS" CONTENT="ALL">
    <METANAME="Keywords" CONTENT="Курсовий проєкт, MySQL, виконання селектів">
    <METANAME="Description" CONTENT="Курсовий проєкт. АІС будівельної організації">

    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>

    <title> Курсовий проєкт. АІС будівельної організації </title>
</head>
<body>     
    <h1>АІС будівельної організації</h1>
    <p>Будівельна організація займається будівництвом різного роду об'єктів: житлових будинків, лікарень, шкіл, мостів, доріг тощо за договорами із замовниками (міська адміністрація, відомства, приватні фірми тощо).  </p>
    <p>Структурно будівельна організація складається з будівельних управлінь, кожне будівельне управління веде роботи на одному або декількох ділянках, очолюваних начальниками ділянок, яким підпорядковується група виконробів, майстрів і техніків. </p>
    
    <div>
    <h3>Клікніть назву управління, щоб переглянути детальну інформацію </h3>
    <table>
        <tr>
            <th>№</th>
            <th>Управління</th>
            <th>Керівник</th>
            
        </tr>
        <?php
            foreach($governances as $governance) {
             ?>
                <tr>
                    <td><?= $governance[0] ?></td>

                    <td><a href="gov.php?gov=<?= $governance[1]?>"><?= $governance[1] ?></a></td>
                    <td><?= $governance[2] ?></td>
                    <!-- <td><a href="update.php?country=<?= $country[1] ?>">Редагувати</a></td>
                    <td><a href="modifier/delete.php?country=<?= $country[1] ?>">Видалити</a></td>     -->
                    
                </tr>
             <?php
            }
        ?> 
    </table>
    <br>

    
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


    <button onclick="changingContent2()">об'єкти (цегляні роботи, І квартал, 2022)</button>
    <main id="button2">
        <h3>перелік об’єктів, що зводяться в організації, на яких в перший квартал 2023 виконувались цегляні роботи</h3>
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

    

    <button onclick="changingContent3()">роботи, з перевищенням термінів виконання</button>
    <main id="button3">
        <h3>перелік видів будівельних робіт, за якими мало місце перевищення термінів виконання (в цілому по організації)</h3>
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

    <button onclick="changingContent4()">матеріали (перевищення кошторису)</button>
    <main id="button4">
        <h3>перелік матеріалів, з перевищенням суми за кошторисом (в цілому по організації)</h3>
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

    

    <h4>Виконання робіт на об'єктах</h4>
    <button onclick="changingContent5()"> бригадою №1</button>
    <main id="button5">
        <h3>перелік видів будівельних робіт, виконаних бригадою #1 протягом I кварталу 2023р., із зазначенням об’єктів, де ці роботи виконуються</h3>
        <table>
        <tr> 
            <th>робота</th>
            <th>бригада</th>
            <th>об'єкт</th>
            <th>початок роботи</th>  
            <th>закінчення роботи</th>   
      
        </tr>
        <?php
            foreach($object_works_br1 as $owb1) {
             ?>
                <tr>
                    <td><?= $owb1[0] ?></td>
                    <td><?= $owb1[1] ?></td>
                    <td><?= $owb1[2] ?></td>    
                    <td><?= $owb1[3] ?></td>  
                    <td><?= $owb1[4] ?></td>
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>

    <button onclick="changingContent6()"> бригадою №2</button>
    <main id="button6">
    <h3>перелік видів будівельних робіт, виконаних бригадою #2 протягом I кварталу 2023р., із зазначенням об’єктів, де ці роботи виконуються</h3>
        <table>
        <tr> 
            <th>робота</th>
            <th>бригада</th>
            <th>об'єкт</th>
            <th>початок роботи</th>  
            <th>закінчення роботи</th>   
      
        </tr>
        <?php
            foreach($object_works_br2 as $owb2) {
             ?>
                <tr>
                    <td><?= $owb2[0] ?></td>
                    <td><?= $owb2[1] ?></td>
                    <td><?= $owb2[2] ?></td>    
                    <td><?= $owb2[3] ?></td>  
                    <td><?= $owb2[4] ?></td>
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>

    <button onclick="changingContent7()"> бригадою №3</button>
    <main id="button7">
    <h3>перелік видів будівельних робіт, виконаних бригадою #3 протягом I кварталу 2023р., із зазначенням об’єктів, де ці роботи виконуються</h3>
        <table>
        <tr> 
            <th>робота</th>
            <th>бригада</th>
            <th>об'єкт</th>
            <th>початок роботи</th>  
            <th>закінчення роботи</th>   
      
        </tr>
        <?php
            foreach($object_works_br3 as $owb3) {
             ?>
                <tr>
                    <td><?= $owb3[0] ?></td>
                    <td><?= $owb3[1] ?></td>
                    <td><?= $owb3[2] ?></td>    
                    <td><?= $owb3[3] ?></td>  
                    <td><?= $owb3[4] ?></td>
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>


    <h4>Бригади, що виконують</h4>
    <button onclick="changingContent8()"> цегляні роботи</button>
    <main id="button8">
    <h3>бригади, що виконують цегляні роботи протягом І кварталу 2023р., із зазначенням об’єктів, де ці роботи виконуються</h3>
        <table>
        <tr> 
            <th>бригада</th>
            <th>робота</th>
            <th>об'єкт</th>
            <th>початок роботи</th>  
            <th>закінчення роботи</th>   
      
        </tr>
        <?php
            foreach($brigade1 as $br1) {
             ?>
                <tr>
                    <td><?= $br1[0] ?></td>
                    <td><?= $br1[1] ?></td>
                    <td><?= $br1[2] ?></td>    
                    <td><?= $br1[3] ?></td>  
                    <td><?= $br1[4] ?></td>
                </tr>
             <?php
            }
        ?> 
    </table>
    </main>

    <button onclick="changingContent9()"> зведення фундаменту</button>
    <main id="button9">
    <h3>бригади, що виконують зведення фундаменту протягом І кварталу 2023р., із зазначенням об’єктів, де ці роботи виконуються</h3>
        <table>
        <tr> 
            <th>бригада</th>
            <th>робота</th>
            <th>об'єкт</th>
            <th>початок роботи</th>  
            <th>закінчення роботи</th>   
      
        </tr>
        <?php
            foreach($brigade2 as $br2) {
             ?>
                <tr>
                    <td><?= $br2[0] ?></td>
                    <td><?= $br2[1] ?></td>
                    <td><?= $br2[2] ?></td>    
                    <td><?= $br2[3] ?></td>  
                    <td><?= $br2[4] ?></td>
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