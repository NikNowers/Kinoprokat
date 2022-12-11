<?php
session_start();
$link = mysqli_connect("localhost", "root", "0307", "mydb");
mysqli_set_charset($link, "utf8");

// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$genre_condition = '';
if ($_POST['genre'])
    $genre_condition = ' and Name_genre = '.'"'.$_POST['genre'].'"';

$order_by = 'id_film';
if ($_POST['film_list'])
    $order_by = $_POST['film_list'];

$desc = '';
if ($_POST['film_list_desc'])
    $desc = ' desc';
// Запрос к бд
$sql =
    'select film.id_film, film_name,  film.id_genre, Name_genre, surname_actor, Surname_director, availability from actor join films_actors on actor.id_actor = films_actors.id_actor
                            join film on films_actors.id_film = film.id_film
                            join genre on genre.ID_genre=film.ID_genre
                            join films_directors on films_directors.id_film = film.id_film
                            join director on director.id_director = films_directors.ID_director
                            join film_disk on film_disk.id_film = film.ID_Film
                            join disk on disk.ID_disk = film_disk.ID_disk '.$genre_condition.' order by '.$order_by.$desc.';';
$result = mysqli_query($link, $sql);

// Составление ответа
$finfo = $result->fetch_fields();
$response = '<table cellspacing="1">
    <thead>
        <tr>
            <th colspan="'.count($finfo).'">' . $finfo[0]->table . '</th>
        </tr>
    </thead>
    <tbody>
        <tr>';
            foreach ($finfo as $val) {
                $response .= '<td>' . $val->name . '</td>';
            }
            
        $response .= '</tr>';

        while($row = mysqli_fetch_array($result, MYSQLI_NUM))
        {
            $response .= '<tr>';
            for($i = 0; $i < count($row); $i++){
                 $response .= '<td>'.$row[$i].'</td>';
            }
            $response .= '</tr>';
        }

        $response .= '</tbody></table>';


$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$_SESSION['response'] = $response;
$_SESSION['Post'] = $_POST;
header("Location: $redirect");
exit();

?>