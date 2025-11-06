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
    $productImage = '';

    if (empty($productCat)||empty($productBrand)||empty($productTitle)||empty($productPrice)||empty($productDes)||empty($productKey)) {
        $response['status'] = 'error';
        $response['message'] = 'Please fill in all fields!';
        echo json_encode($response);
        exit();
    }


    // For image upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmp   = $_FILES['productImage']['tmp_name'];
        $fileName  = basename($_FILES['productImage']['name']);
        $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allowed extensions
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowedExts)) {
            $response['status'] = 'error';
            $response['message'] = 'Only JPG, JPEG, PNG, and GIF images are allowed.';
            echo json_encode($response);
            exit();
        }

        // Unique filename
        $newFileName = uniqid("IMG_", true) . "." . $fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $destPath)) {
            $productImage = $destPath;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Image upload failed';
            echo json_encode($response);
            exit();
        }
    }

    // Insert product
    $result = add_product_ctr($productCat, $productBrand, $productTitle, $productPrice, $productDes, $productImage, $productKey, $user_id);
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Product added successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
    }
}
?>