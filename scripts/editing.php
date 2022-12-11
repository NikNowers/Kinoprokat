<?php
session_start();
$link = mysqli_connect("localhost", "root", "0307", "mydb");
mysqli_set_charset($link, "utf8");
// foreach ($_POST as $key => $val) {
//     echo $key . ' => ' . $val . '<br>';
// }

$name_del_id = '';
$del_id = $_POST['del_id'] ? $_POST['del_id'] : -1;

$table_film = $_POST['insert_film'] ? 'film' : '';
$table_del_film = $_POST['delete_film'] ? 'film' : '';

$table_actor = $_POST['insert_actor'] ? 'actor' : '';
$table_del_actor = $_POST['delete_actor'] ? 'actor' : '';

$table_director = $_POST['insert_director'] ? 'director' : '';
$table_del_director = $_POST['delete_director'] ? 'director' : '';

$table_disk = $_POST['insert_disk'] ? 'disk' : '';
$table_del_disk = $_POST['delete_disk'] ? 'disk' : '';

$table_disk_rental = $_POST['insert_disk_rental'] ? 'disk_rental' : '';
$table_del_disk_rental = $_POST['delete_disk_rental'] ? 'disk_rental' : '';

$table_film_disk = $_POST['insert_film_disk'] ? 'film_disk' : '';
$table_del_film_disk = $_POST['delete_film_disk'] ? 'film_disk' : '';

$table_films_actors = $_POST['insert_films_actors'] ? 'films_actors' : '';
$table_del_films_actors = $_POST['delete_films_actors'] ? 'films_actors' : '';

$table_films_directors = $_POST['insert_films_directors'] ? 'films_directors' : '';
$table_del_films_directors = $_POST['delete_films_directors'] ? 'films_directors' : '';

$table_genre = $_POST['insert_genre'] ? 'genre' : '';
$table_del_genre = $_POST['delete_genre'] ? 'genre' : '';

$table_tenant = $_POST['insert_tenant'] ? 'tenant' : '';
$table_del_tenant= $_POST['delete_tenant'] ? 'tenant' : '';


if ($table_film) {
    $table = $table_film;
} else if ($table_actor) {
    $table = $table_actor;
} else if ($table_director) {
    $table = $table_director;
} else if ($table_disk) {
    $table = $table_disk;
} else if ($table_disk_rental) {
    $table = $table_disk_rental;
} else if ($table_film_disk) {
    $table = $table_film_disk;
} else if ($table_films_actors) {
    $table = $table_films_actors;
} else if ($table_films_directors) {
    $table = $table_films_directors;
} else if ($table_genre) {
    $table = $table_genre;
} else if ($table_tenant) {
    $table = $table_tenant;
} else {
    $table = '';
}



if ($table_del_film) {
    $table = $table_del_film;
    $name_del_id = 'id_film';
} else if ($table_del_actor) {
    $table = $table_del_actor;
    $name_del_id = 'id_actor';
} else if ($table_del_director) {
    $table = $table_del_director;
    $name_del_id = 'id_director';
} else if ($table_del_disk) {
    $table = $table_del_disk;
    $name_del_id = 'id_disk';
} else if ($table_del_disk_rental) {
    $table = $table_del_disk_rental;
    $name_del_id = 'id_disk_rental';
} else if ($table_del_film_disk) {
    $table = $table_del_film_disk;
    $name_del_id = 'id_film_disk';
} else if ($table_del_films_actors) {
    $table = $table_del_films_actors;
    $name_del_id = 'id_product';
} else if ($table_del_films_directors) {
    $table = $table_del_films_directors;
    $name_del_id = 'id_films_directors';
} else if ($table_del_genre) {
    $table = $table_del_genre;
    $name_del_id = 'id_genre';
} else if ($table_del_tenant) {
    $table = $table_del_tenant;
    $name_del_id = 'id_tenant';
}


if ($table) {
    // Запрос к бд
    $sql =
        'select * from ' . $table;
    $result = mysqli_query($link, $sql);

    $finfo = $result->fetch_fields();

    $len = count($_POST) - 2;
    $all_params = true;

    $values = ' (';
    $counter = 0;
    foreach ($finfo as $val) {
        if ($counter > 0 && $table != 'order_list') {
            $values .= $val->name;
            if ($counter != $len) {
                $values .= ',';
            }
        }
        $counter++;
    }
    $values .= ') values';
    $values_post = ' (';
    $counter = 0;
    foreach ($_POST as $val) {

        if ($counter < $len) {
            if (!$val) {
                $all_params = false;
                break;
            }

            $values_post .= '"' . $val . '"';
            if ($counter < $len - 1) {
                $values_post .= ',';
            }
        }

        $counter++;
    }
    $values_post .= ')';

    if ($all_params && $del_id == -1) {
        $sql = 'insert into ' . $table . $values . $values_post . ';';

        // $result = mysqli_query($link, 'select * from '.$table);
        try {
            $result = mysqli_query($link, $sql);
        } catch (Exception $e) {
            $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $_SESSION['response'] = '<h2>Невозможно добавить данные</h2>';
            $_SESSION['Post'] = $_POST;
            header("Location: $redirect");
            exit();
        }
    } else if ($del_id != -1) {
        $sql = 'delete from ' . $table . ' where ' . $name_del_id . ' = ' . $del_id . ';';
        try {
            $result = mysqli_query($link, $sql);
        } catch (Exception $e) {
            $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            $_SESSION['response'] = '<h2>Невозможно удалить данные</h2>';
            $_SESSION['Post'] = $_POST;
            header("Location: $redirect");
            exit();
        }
    }


    // Составление ответа
    $sql =
        'select * from ' . $table;
    $result = mysqli_query($link, $sql);
    $response = '<table cellspacing="1">
<thead>
    <tr>
        <th colspan="' . count($finfo) . '">' . $finfo[0]->table . '</th>
    </tr>
</thead>
<tbody>
    <tr>';
    foreach ($finfo as $val) {
        $response .= '<td>' . $val->name . '</td>';
    }

    $response .= '</tr>';

    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
        $response .= '<tr>';
        for ($i = 0; $i < count($row); $i++) {
            $response .= '<td>' . $row[$i] . '</td>';
        }
        $response .= '</tr>';
    }
}




$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$_SESSION['response'] = $response;
$_SESSION['Post'] = $_POST;
header("Location: $redirect");
exit();