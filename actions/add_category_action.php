<?php
require_once '../controllers/category_controller.php';
header('Content-Type: application/json');

// Get POST data safely
$cat_name = isset($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

// Validate
if (empty($cat_name) || $user_id == 0) {
    echo json_encode(["success" => false, "message" => "Missing category name or user ID"]);
    exit;
}

// Try inserting
$result = add_cat_ctr($cat_name, $user_id);

if ($result) {
    echo json_encode(["success" => true, "message" => "Category added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add category"]);
}
?>
