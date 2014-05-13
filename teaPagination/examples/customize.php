<?php
require_once('debug/fb.php');
require_once('../teaPagination.php');
require_once('extendsPaginate/PaginateCustomize.php');

$sort = (isset($_POST['sort'])) ? $_POST['sort'] : null;
$order = (isset($_POST['order'])) ? $_POST['order'] : null;
$type = (isset($_POST['type'])) ? $_POST['type'] : null;
$search = (isset($_POST['search'])) ? $_POST['search'] : null;
$options = array(
    'sort' => $sort,
    'order' => $order,
    'typeList' => $type,
    'search' => $search,
    'fieldsSearch' => array('short_name','long_name')
);
$pagination = new PaginateCustomize('SELECT * FROM countries',$options,$type);
echo json_encode(array('list' => $pagination->ShowList(),'buttons' => $pagination->buttons()));
?>
