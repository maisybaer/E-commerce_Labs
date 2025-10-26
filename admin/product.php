<?php
//session_start();
require_once '../settings/core.php';
require_once '../settings/db_class.php';
require_once '../actions/fetch_category_action.php';
require_once '../actions/fetch_brand_action.php';

$user_id = getUserID();
$role = getUserRole();


$db = new db_connection();

//get categories and brands for dropdowns
$allBrand=get_all_brands_ctr();
$allCat=get_all_cat_ctr();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products-Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../settings/styles.css">
</head>

<body>
	<div class="menu-tray">
		<span class="me-2">Menu:</span>
		<?php if (isset($_SESSION['user_id'])): ?>
            <a href="../index.php" class="btn btn-sm btn-outline-primary">Home</a>
			<a href="../login/logout.php" class="btn btn-sm btn-outline-secondary">Logout</a>
		<?php else: ?>
            <a href="../index.php" class="btn btn-sm btn-outline-primary">Home</a>
			<a href="../login/login.php" class="btn btn-sm btn-outline-secondary">Login</a>
            <p><br>Login to see your Products</p>
		<?php endif; ?>			
	</div>


    <div class="container header-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
           
            <h1>Products</h1>
            <h4>All the products you have created are listed below</h4>
        
            <!-- Create brand form-->
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Create a new category</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="" class="mt-4" id="addProductForm">
                            <div class="mb-3">
                                <label for="productCategory" class="form-label">Category</label>
                                <select class="form-control" id="product_cat" name="product_cat" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($allCat as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['cat_id']); ?>">
                                        <?php echo htmlspecialchars($cat['cat_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label for="productBrand" class="form-label">Brand</label>
                                <select class="form-control" id="product_brand" name="product_brand" required>
                                    <option value="">Select Brand</option>
                                    <?php foreach ($allBrand as $brand): ?>
                                        <option value="<?php echo htmlspecialchars($brand['brand_id']); ?>">
                                        <?php echo htmlspecialchars($brand['brand_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label for="productTitle" class="form-label">Product Title</label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="product_title" name="product_title" required>

                                <label for="productPrice" class="form-label">Product Price</label>
                                <input type="number" step="0.01" class="form-control animate__animated animate__fadeInUp" id="product_price" name="product_price" required>

                                <label for="productDescription" class="form-label">Product Description</label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="product_description" name="product_description" required>

                                <label for="productImage" class="form-label">Product Image</label>
                                <input type="file" class="form-control animate__animated animate__fadeInUp" id="product_image" name="product_image">

                                <label for="productKeyword" class="form-label">Product Keyword</label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="product_keyword" name="product_keyword" required>
                            </div>

                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Add New Product</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View Product-->
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Your current Categories</h4>
                    </div>


                    <div class="card-body">
                        <table id="productTable">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Keyword</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="text-center">No product available</td>
                                <tr>
                            
                                    <button onclick="openForm()" class="small btn btn-custom w-100 animate-pulse-custom">Edit</button>
                                        <!-- Update Product Popup -->
                                            <div id="updatePopup" class="form-popup">
                                                <div class="card mx-auto p-4" style="max-width: 400px; background-color: #fff; border-radius: 10px;">
                                                    <h5 class="text-center mb-3 highlight">Update Product</h5>
                                                    <form id="updateForm">
                                                        <div class="mb-3">
                                                            
                                                            <!-- Update Product Category -->
                                                            <label for="update_product_cat" class="form-label">New Product Category</label>
                                                            <select class="form-control" id="update_product_cat" name="update_product_cat" type="hidden" id="update_product_id" required>
                                                                <option value="">Select Category</option>
                                                                <?php foreach ($allCat as $cat): ?>
                                                                    <option value="<?php echo htmlspecialchars($cat['cat_id']); ?>">
                                                                    <?php echo htmlspecialchars($cat['cat_name']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                            <!-- Update Product Brand -->
                                                            <label for="update_product_brand" class="form-label">New Product Brand</label>
                                                            <input type="dropdown" class="form-control" id="update_product_brand" value=brand_id, display=brand_name required>
                                                            <input type="hidden" id="update_product_id">

                                                            <!-- Update Product Title -->
                                                            <label for="update_product_title" class="form-label">New Product Title</label>
                                                            <input type="text" class="form-control" id="update_product_title" required>
                                                            <input type="hidden" id="update_product_id">

                                                            <!-- Update Product Price -->
                                                            <label for="update_product_price" class="form-label">New Product Price</label>
                                                            <input type="number" step="0.01" class="form-control" id="update_product_price" required>
                                                            <input type="hidden" id="update_product_id">

                                                            <!-- Update Product Description -->
                                                            <label for="update_product_desc" class="form-label">New Product Description</label>
                                                            <input type="text" class="form-control" id="update_product_desc" required>
                                                            <input type="hidden" id="update_product_id"></div>

                                                            <!-- Update Product Image -->
                                                            <label for="update_product_image" class="form-label">New Product Image</label>
                                                            <input type="file" class="form-control" id="update_product_image">
                                                            <input type="hidden" id="update_product_id">

                                                            <!-- Update Product Keywords -->
                                                            <label for="update_product_keywords" class="form-label">New Product Keywords</label>
                                                            <input type="text" class="form-control" id="update_product_keywords" required>
                                                            <input type="hidden" id="update_product_id"></div>

                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button type="submit" id="saveUpdate" class="btn btn-custom">Save</button>
                                                            <button type="button" id="cancelUpdate" class="btn btn-secondary">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                    <button class="small btn btn-custom w-100 animate-pulse-custom">Delete</button>
                                </td>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/product.js"></script>

    
</body>

</html>

