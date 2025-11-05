<?php
require_once '../controllers/product_controller.php';
require_once '../settings/core.php';

// Get the logged-in user’s ID (if required)
$user_id = getUserID();

// Fetch all products
$products = view_all_product_ctr();

if ($products === false || empty($products)) {
    echo json_encode([]);
    exit;
}

// Return clean JSON
header('Content-Type: application/json');
echo json_encode($products);
exit;
?>

