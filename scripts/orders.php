<?php
session_start();
$link = mysqli_connect("localhost", "root", "0307", "mydb");
mysqli_set_charset($link, "utf8");
// foreach ($_POST as $key => $val){
//     echo $key.' => '.$val.'<br>';
// }

// Составление условий для where
$conditions = 'where True ';

if ($_POST['Surname']){
    $conditions .= ' and Surname_tenant='.'"'.$_POST['Surname'].'"';
}

if ($_POST['date_rent']){
    $conditions .= ' and Date_rent='.'"'.$_POST['date_rent'].'"';
}

if ($_POST['date_refund']){
    $conditions .= ' and Date_refund='.'"'.$_POST['date_refund'].'"';
}


$order_by = 'id_disk';
if ($_POST['orders_list'])
    $order_by = $_POST['orders_list'];

$desc = '';
if ($_POST['orders_list_desc'])
    $desc = ' desc';

// Запрос к бд
$sql =
    'select tenant.Surname_tenant, id_disk, date_rent, date_refund 
    from disk_rental join tenant on tenant.ID_tenant=disk_rental.ID_tenant '. $conditions. ' order by '.$order_by.$desc.';';
echo $sql;
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


$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$_SESSION['response'] = $response;
$_SESSION['Post'] = $_POST;
header("Location: $redirect");
exit();

?>