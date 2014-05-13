<?php

function create_list_data($data) {
    return '<p><a href="javascript:void(0)" onClick="alert(\'Country: ' . $data['short_name'] . '\')">' . $data['short_name'] . '</a></p><small>' . $data['long_name'] . '</small>';
}

require_once('../teaPagination.php');
require_once('setting/setting.php');
$sql = 'SELECT * FROM countries';
$options = array(
    'beginLoop' => '<blockquote>',
    'endLoop' => '</blockquote>',
    'maxButtons' => 5,
    'itemsPage' => 10,
    'ajax' => true,
    'buttons' => array('class' => 'pagination'),
    'connect_db' => array('user' => USER, 'database' => DATABASE, 'password' => PASSWORD)
);
$pagination = new teaPagination($sql, $options);
$pagination->loop('', 'create_list_data');
echo json_encode(array('list' => $pagination->render(),'buttons' => $pagination->buttons()));
?>
