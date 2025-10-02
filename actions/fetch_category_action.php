<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';

$user_id = getUserID();
$cat = get_cat_ctr($user_id);

echo json_encode($cat);
