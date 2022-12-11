<?php
session_start();?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>База данных - Кинопрокат- Тут ничего нет</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <?php
    $link = mysqli_connect("localhost", "root", "0307", "mydb");
    mysqli_set_charset($link, "utf8");
    if (!empty($_SESSION['Post']))
        $_POST = $_SESSION['Post'];

$checked_tenant = '';
$checked_orders = '';


if (!empty($_POST['filter_tenant']))
    $checked_tenant = ' checked';

if (!empty($_POST['filter_orders']))
    $checked_orders = ' checked';
    ?>
    <a href="main.php">Фильтр и поиск</a>
    <a href="edit.php">Редактирования</a>
    <p>Выберите категорию: </p>
    <input type="radio" name="filters" checked value="radio_filter_film"><label>Фильмы</label>
    <input type="radio" name="filters" <?php echo $checked_tenant ?> value="radio_filter_tenant"><label>Клиенты</label>
    <input type="radio" name="filters" <?php echo $checked_orders ?> value="radio_filter_orders"><label>Заказы</label>
    <br><br>

<div class="editing">
    <p>Редактирование таблиц: </p>
    <?php
    $sql = 'show tables;';
    $result = mysqli_query($link, $sql);

    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
        for ($i = 0; $i < count($row); $i++) {
            echo '<input type="radio" name="edit" value="radio_edit_' . $row[$i] . '"><label>' . $row[$i] . '</label>';
        }
    }
    ?>


    <div class="table_car">
        <form action="scripts/editing.php" method="post">
            <?php
            $sql = 'select * from film;';
            $result = mysqli_query($link, $sql);

            $finfo = $result->fetch_fields();
            $counter = 0;
            foreach ($finfo as $val) {
                if ($counter > 0)
                    echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                $counter++;
            }
            ?>
            <input type="submit" name="insert_car" value="Добавить запись">
            <input type="text" name="del_id" placeholder="id">
            <input type="submit" name="delete_car" value="Удалить запись">
        </form>
    </div>

    <!--        <div class="table_client">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from client;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_client" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_client" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_delivery">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from delivery;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_delivery" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_delivery" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_employee">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from employee;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_employee" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_employee" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_order_list">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from order_list;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //
    //                foreach ($finfo as $val) {
    //                    echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_order_list" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_order_list" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_orders">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from orders;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_orders" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_orders" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_product">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from product;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_product" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_product" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_product_type">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from product_type;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_product_type" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_product_type" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_status">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from status;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_status" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_status" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->
    <!---->
    <!--        <div class="table_storehouse">-->
    <!--            <form action="scripts/editing.php" method="post">-->
    <!--                --><?php
    //                $sql = 'select * from storehouse;';
    //                $result = mysqli_query($link, $sql);
    //
    //                $finfo = $result->fetch_fields();
    //                $counter = 0;
    //                foreach ($finfo as $val) {
    //                    if ($counter > 0)
    //                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
    //                    $counter++;
    //                }
    //                ?>
    <!--                <input type="submit" name="insert_storehouse" value="Добавить запись">-->
    <!--                <input type="text" name="del_id" placeholder="id">-->
    <!--                <input type="submit" name="delete_storehouse" value="Удалить запись">-->
    <!--            </form>-->
    <!--        </div>-->

</div>



    <?php echo $_SESSION['response']; ?>


<?php mysqli_close($link) ?>
</body>

</html>