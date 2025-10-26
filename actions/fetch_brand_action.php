<?php
require_once '../controllers/brand_controller.php';
require_once '../settings/core.php';

$user_id = getUserID();

//fetch brand based on user id
$brand = get_brand_ctr($user_id);
echo json_encode($brand);

//fetch all brands
$brand = get_all_brands_ctr();
?>
