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

if ($_POST['name']){
    $conditions .= ' and name_tenant='.'"'.$_POST['name'].'"';
}

if ($_POST['phone_number']){
    $conditions .= ' and phone_number='.'"'.$_POST['phone_number'].'"';
}

$order_by = 'id_tenant';
if ($_POST['tenant_list'])
    $order_by = $_POST['tenant_list'];

$desc = '';
if ($_POST['tenant_list'])
    $desc = ' desc';

// Запрос к бд
$sql =
    'select id_tenant, Surname_tenant, Name_tenant, Phone_number
    from tenant '.
    $conditions.
    ' order by '.$order_by.$desc.';';
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