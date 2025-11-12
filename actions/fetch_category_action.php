<?php
require_once '../controllers/category_controller.php';
require_once '../settings/core.php';

header('Content-Type: application/json');

try {
    $user_id = getUserID();

    if (!$user_id) {
        echo json_encode([]);
        exit;
    }

    $categories = get_cat_ctr($user_id);

    if (!is_array($categories)) {
        $categories = [];
    }

    echo json_encode($categories);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch categories.'
    ]);
}
