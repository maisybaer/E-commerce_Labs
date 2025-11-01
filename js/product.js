document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#productTable tbody");

    // Add Product
    $('#addProductForm').submit(function(e) {
        e.preventDefault();

        let productCat = $('#productCat').val();
        let productBrand = $('#productBrand').val();
        let productTitle = $('#productTitle').val();
        let productPrice = $('#productPrice').val();
        let productDes = $('#productDes').val();
        let productImage = $('#productImage')[0].files[0];
        let productKey = $('#productKey').val();
        let user_id = $('#user_id').val();

        if (productCat == '' || productBrand == '' || productTitle == '' ||productPrice == ''|| productDes == ''|| productKey == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });

            return;

        } else if (!/^[0-9]+(\.[0-9]+)?$/.test(productPrice)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'The price must be a number!',
            });
            return;
        }

       //prepare data to accept image
        let formData = new FormData();
        formData.append('productCat', productCat);
        formData.append('productBrand', productBrand);
        formData.append('productTitle', productTitle);
        formData.append('productPrice', productPrice);
        formData.append('productDes', productDes);
        formData.append('productKey', productKey);
        formData.append('user_id', user_id);

        if (productImage) {
            formData.append('productImage', productImage);
        }

        $.ajax({
            url: '../actions/add_product_action.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData:false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'product.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText); // For debugging
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred while adding the product! Please try again later. Troubleshoot: See product.js_ajax ',
                });
            }
        });
    });

    // Update Product (Pop up form)
    let currentUpdateProductId = null;

function createUpdatePopup() {
    if (document.getElementById("updatePopupContainer")) return;

    const popup = document.createElement("div");
    popup.className = "form-popup";
    popup.id = "updatePopupContainer";
    popup.style.display = "none";

        popup.innerHTML = `
            <div class="card" style="max-width:auto;margin:auto;">
                <div class="card-body">
                    <form id="updateProductForm">
                        <div class="mb-3">

                            <input type="hidden" id="updateProductID">

                            <!-- Update Product Category -->

                            <label for="updateProductCat" class="form-label">New Product Category</label>
                            <select class="form-control" id="updateProductCat" name="updateProductCat" required>
                                <option value="">Select Category</option>
                                <?php foreach ($allCat as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['cat_id']); ?>">
                                    <?php echo htmlspecialchars($cat['cat_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <!-- Update Product Brand -->
                            <label for="updateProductBrand" class="form-label" >New Product Brand</label>
                            <select class="form-control" id="updateProductBrand" name="updateProductBrand" required>
                                <option value="">Select Brand</option>
                                <?php foreach ($allBrand as $brand): ?>
                                    <option value="<?php echo htmlspecialchars($brand['brand_id']); ?>">
                                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                    
                            <!-- Update Product Title -->
                            <label for="updateProductTitle" class="form-label">New Product Title</label>
                            <input type="text" class="form-control" id="updateProductTitle" name="updateProductTitle" required>

                            <!-- Update Product Price -->
                            <label for="updateProductPrice" class="form-label">New Product Price</label>
                            <input type="number" class="form-control" id="updateProductPrice" name="updateProductPrice" required>

                            <!-- Update Product Description -->
                            <label for="updateProductDes" class="form-label">New Product Description</label>
                            <input type="text" class="form-control" id="updateProductDes" name="updateProductDesc" required>

                            <!-- Update Product Image -->
                            <label for="updateProductImage" class="form-label">New Product Image</label>
                            <input type="file" class="form-control" id="updateProductImage" name="updateProductImage">

                            <!-- Update Product Keywords -->
                            <label for="updateProductKey" class="form-label">New Product Keywords</label>
                            <input type="text" class="form-control" id="updateProductKey" name="updateProductKey" required>
                       
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Update</button>
                        <button type="button" class="btn btn-secondary w-100 mt-2" id="closeEditPopup">Cancel</button>
                    </form>
                </div>
            </div>
        `;
        document.body.appendChild(popup);

        document.getElementById("closeEditPopup").onclick = closeForm;

        document.getElementById("updateProductForm").onsubmit = function(e) {
            e.preventDefault();
            let product_id = document.getElementById("updateProductID").value;
            let productCat = document.getElementById("updateProductCat").value;
            let productBrand = document.getElementById("updateProductBrand").value;
            let productTitle = document.getElementById("updateProductTitle").value;
            let productPrice = document.getElementById("updateProductPrice").value;
            let productDes = document.getElementById("updateProductDes").value;
            let productKey = document.getElementById("updateProductKey").value;
            let imageFile = document.getElementById("updateProductImage").files[0];
           
            let formData = new FormData();
            formData.append("product_id", currentEditProductID);
            formData.append("productCat", productCat);
            formData.append("productBrand", productBrand);
            formData.append("productTitle", productTitle);
            formData.append("productPrice", productPrice);
            formData.append("productDes", productDes);
            formData.append("productKey", productKey);
            if (imageFile) formData.append("productImage", imageFile);

            fetch("../actions/update_product_action.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(resp => {
                    Swal.fire({
                        icon: resp.status,
                        title: resp.status === 'success' ? 'Updated!' : 'Error',
                        text: resp.message,
                    }).then(() => {
                        closeForm();
                        loadProducts();
                    });
                })
                .catch(err => console.error("Update Error:", err));
        };
    }

    function openForm(product_id) {
        currentEditProductId = product_id;
        populateDropdowns(productCat, productBrand);

        document.getElementById("updateProductTitle").value = productTitle;
        document.getElementById("updateProductprice").value = productPrice;
        document.getElementById("updateProductDesc").value = productDes;
        document.getElementById("updateProductKey").value = productKey;
        popup.style.display = "block";
    }

    function closeForm() {
        let popup = document.getElementById("updatePopupContainer");
        popup.style.display = "none";
    }


    // Fetch products
    function loadProducts() {
        fetch("../actions/fetch_product_action.php")
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = "";
                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="3">No product available</td></tr>`;
                } else {
                    data.forEach(product => {
                        let row = `
                        <tr>
                            <td>${product.product_id}</td>
                            <td>${product.product_cat}</td>
                            <td>${product.product_brand}</td>
                            <td>${product.product_title}</td>
                            <td>${product.product_price}</td>
                            <td>${product.product_desc}</td>
                            <td><img src="../images/product/${product.product_image}" width="50"></td>
                            <td>${product.product_keywords}</td>
                            <td>
                                <button class="btn btn-sm btn-custom" onclick='openForm(${JSON.stringify(product)})'>Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.product_id})">Delete</button>
                            </td>
                        </tr>`;
                    
                        tableBody.innerHTML += row;
                    });
                }
            });
    }

function populateDropdowns(selectedCat, selectedBrand) {
        // Fetch category and brand lists from the PHP side
        fetch("../actions/fetch_category_action.php")
            .then(res => res.json())
            .then(cats => {
                let catSelect = document.getElementById("updateProductCat");
                catSelect.innerHTML = `<option value="">Select Category</option>`;
                cats.forEach(c => {
                    catSelect.innerHTML += `<option value="${c.cat_id}" ${c.cat_name === selectedCat ? 'selected' : ''}>${c.cat_name}</option>`;
                });
            });

        fetch("../actions/fetch_brand_action.php")
            .then(res => res.json())
            .then(brands => {
                let brandSelect = document.getElementById("updateProductBrand");
                brandSelect.innerHTML = `<option value="">Select Brand</option>`;
                brands.forEach(b => {
                    brandSelect.innerHTML += `<option value="${b.brand_id}" ${b.brand_name === selectedBrand ? 'selected' : ''}>${b.brand_name}</option>`;
                });
            });
    }

//Delete Product
    window.deleteProduct = function (product_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the product.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then(result => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append("product_id", product_id);

                fetch("../actions/delete_product_action.php", {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.json())
                    .then(resp => {
                        Swal.fire(resp.status === 'success' ? 'Deleted!' : 'Error', resp.message, resp.status);
                        loadProducts();
                    });
            }
        });
    };

    // Expose openForm globally for inline button
    window.openForm = openForm;

    createUpdatePopup();
    closeForm();
    loadProducts();
});
