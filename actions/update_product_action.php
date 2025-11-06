<?php
require_once '../controllers/product_controller.php';
require_once '../settings/core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST fields
    $product_id = $_POST['product_id'] ?? '';
    $productCat = $_POST['productCat'] ?? '';
    $productBrand = $_POST['productBrand'] ?? '';
    $productTitle = $_POST['productTitle'] ?? '';
    $productPrice = $_POST['productPrice'] ?? '';
    $productDes = $_POST['productDes'] ?? '';
    $productKey = $_POST['productKey'] ?? '';
    $productImage = '';

    // Handle file upload if present
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        // store uploads in the uploads/ folder (project root)
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileTmp  = $_FILES['productImage']['tmp_name'];
        $fileName = basename($_FILES['productImage']['name']);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['jpg','jpeg','png','gif'];
        if (in_array($fileExt, $allowedExts)) {
            $newFileName = uniqid('IMG_', true) . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmp, $destPath)) {
                // store only the filename in DB; frontend resolves to /uploads/<filename>
                $productImage = $newFileName;
            }
        }
    }

    if (!empty($product_id)) {
        $result = update_product_ctr($productCat, $productBrand, $productTitle, $productDes, $productKey, $productImage, $productPrice, $product_id);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Product updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update product."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing fields."]);
    }

}
