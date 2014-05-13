<?php

function create_row_data($data) {
    $style = ($data['calling_code'] > 100)? ' style="background-color:#F2F2F2"' : '';
    return '<tr'.$style.'><td>'.$data['iso2'].'</td><td>'.$data['short_name'].'</td><td>'.$data['calling_code'].'</td><td><a href="#" onClick="alert(\'Delete '.$data['country_id'].'\')">delete</a></td></tr>';
}

require_once('../teaPagination.php');
require_once('debug/fb.php');
require_once('setting/setting.php');

if (session_id() == '') {
    session_start();
}

if(isset($_POST['init'])){
    unset($_SESSION['paginate']);
}
$order = (isset($_SESSION['paginate']['order']))? $_SESSION['paginate']['order'] : 'ORDER BY short_name ASC';
if(isset($_POST['order'])){
    $order = 'ORDER BY '.$_POST['order'].' ASC';
    $_SESSION['paginate']['order'] = $order;
}
$search = (isset($_SESSION['paginate']['search']))? $_SESSION['paginate']['search'] : '';
if(isset($_POST['search'])){
    $search = 'WHERE short_name LIKE "%'.$_POST['search'].'%"';
    $_SESSION['paginate']['search'] = $search;
}
$display = (isset($_SESSION['paginate']['display']))? $_SESSION['paginate']['display'] : 10;
if(isset($_POST['display'])){
    $display = $_POST['display'];
    $_SESSION['paginate']['display'] = $display;
}
$sql = 'SELECT * FROM countries '.$search.$order;
ob_start();
fb::log($sql);
$options = array(
    'beginLoop' => '<tr>',
    'endLoop' => '</tr>',
    'maxButtons' => 5, 
    'itemsPage' => (integer)$display, 
    'ajax' => true,
    'textNotFound' => '<tr><td colspan="4">Empty Record</td></tr>',
    'buttons' => array('class' => 'pagination pull-right','btLast' => false,'btFirst' => false),
    'connect_db' => array('user' => USER, 'database' => DATABASE, 'password' => PASSWORD)
);
$pagination = new teaPagination($sql, $options);
$pagination->loop('', 'create_row_data');
echo json_encode(array(
    'list' => $pagination->render(),
    'buttons' => '<div class="row"><div class="col-md-6">Página '.$pagination->currentPage.' de '.$pagination->TotalPage.' de '.$pagination->TotalRecord.' Registros</div><div class="col-md-6">'.$pagination->buttons().'</div></div>'
));
?>
