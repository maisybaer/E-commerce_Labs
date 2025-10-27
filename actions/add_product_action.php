<?php
require_once '../controllers/product_controller.php';
require_once '../settings/core.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //recieve data

    $productCat = $_POST['productCat'] ?? '';
    $productBrand = $_POST['productBrand'] ?? '';
    $productTitle = $_POST['productTitle'] ?? '';
    $productPrice=$_POST['productPrice'] ?? '';
    $productDes=$_POST['productDes'] ?? '';
    $productKey=$_POST['productKey'] ?? '';
    $user_id = $_POST['user_id'] ?? '';

if (empty($productCat)||empty($productBrand)||empty($productTitle)||empty($productPrice)||empty($productDes)||empty($productKey)) {
    $response['status'] = 'error';
    $response['message'] = 'Please fill in all fields!';
    echo json_encode($response);
    exit();
}


    if (!is_numeric($productPrice)) {
        echo json_encode(["status" => "error", "message" => "Price must be a number!"]);
        exit();
    }

    if (!isset($user_id) || !is_numeric($user_id)) {
        echo json_encode(["status" => "error", "message" => "Invalid user ID!"]);
        exit();
    }

    // Handle image upload
    $productImage = '';
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../product_uploads/'; // fixed path to match JS display
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmp = $_FILES['productImage']['tmp_name'];
        $fileName = basename($_FILES['productImage']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowedExts)) {
            echo json_encode(["status" => "error", "message" => "Only JPG, JPEG, PNG, and GIF files are allowed!"]);
            exit();
        }

        $newFileName = uniqid("IMG_", true) . "." . $fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $destPath)) {
            $productImage = $newFileName; // store filename only
        } else {
            echo json_encode(["status" => "error", "message" => "Image upload failed."]);
            exit();
        }
    }

    // Add product using controller
    $result = add_product_ctr($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice, $user_id);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Product added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add product."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>