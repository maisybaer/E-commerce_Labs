<?php
require_once '../settings/core.php';
$product_id = $_GET['product_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="productDetails"></div>

    <script>
    $(document).ready(function() {
        const productId = "<?php echo $product_id; ?>";
        if (productId) {
            fetch(`../actions/product_actions.php?action=view_single&product_id=${productId}`)
                .then(res => res.json())
                .then(p => {
                    $("#productDetails").html(`
                        <h2>${p.product_title}</h2>
                        <img src="../${p.product_image}" alt="${p.product_title}" width="300">
                        <p><strong>Price:</strong> $${p.product_price}</p>
                        <p><strong>Category:</strong> ${p.category}</p>
                        <p><strong>Brand:</strong> ${p.brand}</p>
                        <p><strong>Description:</strong> ${p.product_desc}</p>
                        <p><strong>Keywords:</strong> ${p.product_keywords}</p>
                        <button>Add to Cart</button>
                    `);
                });
        } else {
            $("#productDetails").html("<p>Invalid product.</p>");
        }
    });
    </script>
</body>
</html>
