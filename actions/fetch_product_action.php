<?php
require_once '../controllers/product_controller.php';
require_once '../settings/core.php';

$user_id = getUserID();
$product = get_product_ctr($user_id);

echo json_encode($product);
