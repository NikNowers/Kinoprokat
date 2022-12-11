<?php
    session_start();?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>База данных - Кинопрокат</title>
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

    <header>
            <img src="image/lenta-header_new.png" width="1600" height="125" style="margin-left: -25px; margin-top: -10px">
    </header>

    <p>Выберите категорию: </p>
    <input type="radio" name="filters" checked value="radio_filter_film"><label>Фильмы</label>
    <input type="radio" name="filters" <?php echo $checked_tenant ?> value="radio_filter_tenant"><label>Клиенты</label>
    <input type="radio" name="filters" <?php echo $checked_orders ?> value="radio_filter_orders"><label>Заказы</label>
    <br><br>

    <div class="film">
        <form action="scripts/film.php" method="post">
            <label for="film">Жанр: </label>
            <select name="genre" id="films">
                <option value="">--Выберите жанр фильма--</option>
                <?php
                $sql = 'select distinct Name_genre from genre';
                $result = mysqli_query($link, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    $selected = '';
                    if ($_POST['genre'] == $row['Name_genre'])
                        $selected = 'selected';
                    echo '<option ' . $selected . ' value=' . '"' . $row['Name_genre'] . '">' . $row['Name_genre'] . '</option>';
                }
                ?>
            </select>

            <div class="order_by">
                <label for="film_list">Сортировать по: </label>
                <select name="film_list" id="film_list">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select film.id_film, film_name,  Name_genre, surname_actor, Surname_director, availability from actor join films_actors on actor.id_actor = films_actors.id_actor
                            join film on films_actors.id_film = film.id_film
                            join genre on genre.ID_genre=film.ID_genre
                            join films_directors on films_directors.id_film = film.id_film
                            join director on director.id_director = films_directors.ID_director
                            join film_disk on film_disk.id_film = film.ID_Film
                            join disk on disk.ID_disk = film_disk.ID_disk';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['film_list'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['film_list_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="film_list_desc" name="film_list_desc" <?php echo $checked ?>>
                <label for="film_list_desc">Обратный порядок</label>
                <input type="submit" name="filter_film" value="Поиск">
            </div>
        </form>
    </div>

    <div class="tenant">
        <form action="scripts/tenant.php" method="post">
            <label>Фамилия: </label>
            <input type="text" name="Surname_tenant">

            <label>Имя: </label>
            <input type="text" name="name_tenant">

            <label>Телефон: </label>
            <input type="text" name="phone_number">

            <div class="order_by">
                <label for="tenant_list">Сортировать по: </label>
                <select name="tenant_list" id="tenant_list">
                    <option value="">--Выберите поле--</option>
                    <?php
                    $sql = 'select id_tenant, Surname_tenant, Name_tenant, Phone_number
                    from tenant ';
                    $result = mysqli_query($link, $sql);
                    $finfo = $result->fetch_fields();

                    foreach ($finfo as $val) {
                        $selected = '';
                        if ($_POST['tenant_list'] == $val->name)
                            $selected = 'selected';
                        echo '<option ' . $selected . ' value=' . '"' . $val->name . '">' . $val->name . '</option>';
                    }
                    ?>
                </select>

                <?php
                $checked = '';
                if (!empty($_POST['tenant_list_desc']))
                    $checked = ' checked';
                ?>
                <input type="checkbox" id="tenant_list_desc" name="tenant_list_desc" <?php echo $checked ?>>
                <label for="tenant_list_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_tenant" value="Поиск">
        </form>
    </div>

    <div class="orders">
        <form action="scripts/orders.php" method="post">
            <label>Фамилия клиента: </label>
            <input type="text" name="Surname_tenant">
            <label>Id диска: </label>
            <input type="text" name="id_orders">
            <label>Дата аренды: </label>
            <input type="text" name="date_rent">
            <label>Дата возврата: </label>
            <input type="text" name="date_refund">

            <div class="order_by">
                <input type="checkbox" id="orders_list_desc" name="orders_list_desc" <?php echo $checked ?>>
                <label for="orders_list_desc">Обратный порядок</label>
            </div>

            <input type="submit" name="filter_orders" value="Поиск">
        </form>
    </div>

    <div class="editing">
        <p>Изменение таблиц: </p>
        <?php
        $sql = 'show tables;';
        $result = mysqli_query($link, $sql);

        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
            for ($i = 0; $i < count($row); $i++) {
                echo '<input type="radio" name="edit" value="radio_edit_' . $row[$i] . '"><label>' . $row[$i] . '</label>';
            }
        }
        ?>


        <div class="table_film">
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
                <input type="submit" name="insert_film" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_film" value="Удалить запись">
            </form>
        </div>

        <div class="table_actor">
            <form action="scripts/editing.php" method="post">
                <?php
                $sql = 'select * from actor;';
                $result = mysqli_query($link, $sql);

                $finfo = $result->fetch_fields();
                $counter = 0;
                foreach ($finfo as $val) {
                    if ($counter > 0)
                        echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                    $counter++;
                }
                ?>
                <input type="submit" name="insert_actor" value="Добавить запись">
                <input type="text" name="del_id" placeholder="id">
                <input type="submit" name="delete_actor" value="Удалить запись">
            </form>
        </div>

                <div class="table_director">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from director;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();
                        $counter = 0;
                        foreach ($finfo as $val) {
                            if ($counter > 0)
                                echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                            $counter++;
                        }
                        ?>
                        <input type="submit" name="insert_director" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_director" value="Удалить запись">
                    </form>
                </div>

                <div class="table_disk">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from disk;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();
                        $counter = 0;
                        foreach ($finfo as $val) {
                            if ($counter > 0)
                                echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                            $counter++;
                        }
                        ?>
                        <input type="submit" name="insert_disk" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_disk" value="Удалить запись">
                    </form>
                </div>

                <div class="table_disk_rental">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from disk_rental;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();
                        $counter = 0;
                        foreach ($finfo as $val) {
                            if ($counter > 0)
                                echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                            $counter++;
                        }
                        ?>
                        <input type="submit" name="insert_disk_rental" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_disk_rental" value="Удалить запись">
                    </form>
                </div>

                <div class="table_film_disk">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from film_disk;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();

                        foreach ($finfo as $val) {
                            echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';

                        }
                        ?>
                        <input type="submit" name="insert_film_disk" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_film_disk" value="Удалить запись">
                    </form>
                </div>

                <div class="table_films_actors">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from films_actors;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();
                        $counter = 0;
                        foreach ($finfo as $val) {
                            if ($counter > 0)
                                echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                            $counter++;
                        }
                        ?>
                        <input type="submit" name="insert_films_actors" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_films_actors" value="Удалить запись">
                    </form>
                </div>

                <div class="table_films_directors">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from films_directors;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();
                        $counter = 0;
                        foreach ($finfo as $val) {
                            if ($counter > 0)
                                echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                            $counter++;
                        }
                        ?>
                        <input type="submit" name="insert_films_directors" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_films_directors" value="Удалить запись">
                    </form>
                </div>

                <div class="table_genre">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from genre;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();
                        $counter = 0;
                        foreach ($finfo as $val) {
                            if ($counter > 0)
                                echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                            $counter++;
                        }
                        ?>
                        <input type="submit" name="insert_genre" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_genre" value="Удалить запись">
                    </form>
                </div>

                <div class="table_tenant">
                    <form action="scripts/editing.php" method="post">
                        <?php
                        $sql = 'select * from tenant;';
                        $result = mysqli_query($link, $sql);

                        $finfo = $result->fetch_fields();
                        $counter = 0;
                        foreach ($finfo as $val) {
                            if ($counter > 0)
                                echo '<label>'.$val->name.': </label><input type="text" name="'.$val->name.'"> ';
                            $counter++;
                        }
                        ?>
                        <input type="submit" name="insert_tenant" value="Добавить запись">
                        <input type="text" name="del_id" placeholder="id">
                        <input type="submit" name="delete_tenant" value="Удалить запись">
                    </form>
                </div>

                </div>

    </div>

    <?php echo $_SESSION['response']; ?>


    <?php mysqli_close($link) ?>

    <div id="footer">
        &copy; Веб-приложение "Кинопрокат" разработано студентом Евлановым Никитой ИКБО-13-20
    </div>
</body>

</html>