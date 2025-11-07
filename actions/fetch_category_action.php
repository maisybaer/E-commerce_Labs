<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';

$user_id = getUserID();

//fetch category based on user id
$cat = get_cat_ctr($user_id);

//fetch all categories
$cat = get_all_cat_ctr();

?>
