<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../settings/core.php';
require_once '../controllers/product_controller.php';
header('Content-Type: application/json');

// Handle incoming requests
$action = $_GET['action'] ?? '';

switch ($action) {

//view all products
    case 'view_all':
        $products = view_all_product_ctr();
        echo json_encode($data);
        break;

    case 'search':
        if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
            echo json_encode(["status" => "error", "message" => "Search query missing."]);
            exit;
        }

        $query = trim($_GET['query']);
        $product = new Product();
        $results = $product->search($query);

        echo json_encode($results);
        break;

//filter by category
    case 'filter_by_category':
        if (!isset($_GET['cat_id']) || !is_numeric($_GET['cat_id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid category ID"]);
            exit;
        }

        $cat_id = intval($_GET['cat_id']);
        $products = filter_by_cat_ctr($cat_id);

        echo json_encode($products);
        break;


//filter by brand
    case 'filter_by_brand':
        if (!isset($_GET['brand_id']) || !is_numeric($_GET['brand_id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid brand ID"]);
            exit;
        }

        $brand_id = intval($_GET['brand_id']);
        $products = filter_by_brand_ctr($brand_id);

        echo json_encode($products);
        break;


//view single product
    case 'view_single':
        if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid product ID"]);
            exit;
        }

        $product_id = intval($_GET['product_id']);
        $product = view_single_product_ctr($product_id);

        echo json_encode($product);
        break;



//default
    default:
        echo json_encode(["status" => "error", "message" => "Invalid action."]);
        break;
}
?>

