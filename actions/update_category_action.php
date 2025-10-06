<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'];
    $cat_name = $_POST['cat_name'];

    if ($cat_id && $cat_name) {
        $result = update_cat_ctr($cat_id, $cat_name);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Category updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update category."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing fields."]);
    }

}
