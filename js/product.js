document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#productTable tbody");

//-------------------------------------
//ALL JS FOR ADMIN PRODUCT PAGE
//-------------------------------------

    // ADD PRODUCT
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


    // UPDATE PRODUCT (Pop up form)
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

                        <label for="updateProductCat" class="form-label">New Product Category</label>
                        <select class="form-control" id="updateProductCat" name="updateProductCat" required>
                            <option value="">Select Category</option>
                        </select>

                        <label for="updateProductBrand" class="form-label">New Product Brand</label>
                        <select class="form-control" id="updateProductBrand" name="updateProductBrand" required>
                            <option value="">Select Brand</option>
                        </select>

                        <label for="updateProductTitle" class="form-label">New Product Title</label>
                        <input type="text" class="form-control" id="updateProductTitle" name="updateProductTitle" required>

                        <label for="updateProductPrice" class="form-label">New Product Price</label>
                        <input type="number" step="0.01" class="form-control" id="updateProductPrice" name="updateProductPrice" required>

                        <label for="updateProductDes" class="form-label">New Product Description</label>
                        <textarea class="form-control" id="updateProductDes" name="updateProductDesc" required></textarea>

                        <label for="updateProductImage" class="form-label">New Product Image</label>
                        <input type="file" class="form-control" id="updateProductImage" name="updateProductImage">

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

        let productCat = document.getElementById("updateProductCat").value;
        let productBrand = document.getElementById("updateProductBrand").value;
        let productTitle = document.getElementById("updateProductTitle").value;
        let productPrice = document.getElementById("updateProductPrice").value;
        let productDes = document.getElementById("updateProductDes").value;
        let productKey = document.getElementById("updateProductKey").value;
        let imageFile = document.getElementById("updateProductImage").files[0];

        let formData = new FormData();
        formData.append("product_id", currentUpdateProductId);
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
                    icon: resp.status === 'success' ? 'success' : 'error',
                    title: resp.status === 'success' ? 'Updated!' : 'Error',
                    text: resp.message,
                }).then(() => {
                    closeForm();
                    loadProducts();
                });
            })
            .catch(err => {
                console.error("Update Error:", err);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Update failed' });
            });
    };
}

function openForm(product) {
    // Accept either an object or a JSON string
    if (typeof product === 'string') {
        try { product = JSON.parse(product); } catch (e) { console.error('Invalid product JSON', e); return; }
    }

    currentUpdateProductId = product.product_id;

    createUpdatePopup();

    // Populate hidden id
    const idEl = document.getElementById('updateProductID');
    if (idEl) idEl.value = product.product_id || '';

    // Populate other fields immediately
    const titleField = document.getElementById('updateProductTitle'); if (titleField) titleField.value = product.product_title || product.productTitle || '';
    const priceField = document.getElementById('updateProductPrice'); if (priceField) priceField.value = product.product_price || product.productPrice || '';
    const descField = document.getElementById('updateProductDes'); if (descField) descField.value = product.product_desc || product.productDes || '';
    const keyField = document.getElementById('updateProductKey'); if (keyField) keyField.value = product.product_keywords || product.productKey || '';

    // Populate dropdowns (pass ids) - wait for them to load before showing popup so selection is visible
    populateDropdowns(product)
        .then(() => {
            const popup = document.getElementById('updatePopupContainer');
            if (popup) popup.style.display = 'block';
        })
        .catch(err => {
            console.error('populateDropdowns error:', err);
            const popup = document.getElementById('updatePopupContainer');
            if (popup) popup.style.display = 'block';
        });
}

function closeForm() {
    let popup = document.getElementById("updatePopupContainer");
    if (popup) popup.style.display = "none";
}


    // FETCH PRODUCTS (admin table)
    function loadProducts() {
        fetch("../actions/fetch_product_action.php")
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = "";
                if (!Array.isArray(data) || data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="9" class="text-center">No product available</td></tr>`;
                    return;
                }

                data.forEach(product => {
                    const tr = document.createElement('tr');

                    const tdId = document.createElement('td'); tdId.textContent = product.product_id;
                    const tdCat = document.createElement('td'); tdCat.textContent = product.category || product.cat_name || product.product_cat || 'N/A';
                    const tdBrand = document.createElement('td'); tdBrand.textContent = product.brand || product.brand_name || product.product_brand || 'N/A';
                    const tdTitle = document.createElement('td'); tdTitle.textContent = product.product_title;
                    const tdPrice = document.createElement('td'); tdPrice.textContent = product.product_price;
                    const tdDesc = document.createElement('td'); tdDesc.textContent = product.product_desc;

                    const tdImg = document.createElement('td');
                    const img = document.createElement('img');

                    // Choose src: prefer server-provided image_url, else construct from product_image
                    let srcCandidate = product.image_url || (product.product_image ? ('uploads/' + product.product_image) : 'uploads/no-image.svg');
                    if (/^https?:\/\//i.test(srcCandidate)) {
                        img.src = srcCandidate;
                    } else if (srcCandidate.startsWith('/')) {
                        img.src = srcCandidate; // root-relative
                    } else if (srcCandidate.startsWith('uploads/') || srcCandidate.startsWith('../uploads/')) {
                        img.src = '../' + srcCandidate.replace(/^\.\.?\//, '');
                    } else {
                        img.src = '../uploads/' + srcCandidate.replace(/^\.\/+/, '');
                    }

                    img.width = 50;
                    img.alt = product.product_title || '';
                    img.onerror = function () { this.onerror = null; this.src = '../uploads/no-image.svg'; };
                    tdImg.appendChild(img);

                    const tdKey = document.createElement('td'); tdKey.textContent = product.product_keywords || '';
                    const tdActions = document.createElement('td');

                    const editBtn = document.createElement('button'); editBtn.className = 'btn btn-sm btn-custom'; editBtn.textContent = 'Edit';
                    // Pass the product object to the product edit opener. Prefer product-specific opener if present.
                    editBtn.addEventListener('click', () => {
                        const opener = window.openProductEditForm || window.openForm;
                        if (opener) opener(product);
                    });
                    const delBtn = document.createElement('button'); delBtn.className = 'btn btn-sm btn-danger'; delBtn.textContent = 'Delete';
                    delBtn.addEventListener('click', () => { if (window.deleteProduct) window.deleteProduct(product.product_id); });

                    tdActions.appendChild(editBtn); tdActions.appendChild(document.createTextNode(' ')); tdActions.appendChild(delBtn);

                    tr.appendChild(tdId);
                    tr.appendChild(tdCat);
                    tr.appendChild(tdBrand);
                    tr.appendChild(tdTitle);
                    tr.appendChild(tdPrice);
                    tr.appendChild(tdDesc);
                    tr.appendChild(tdImg);
                    tr.appendChild(tdKey);
                    tr.appendChild(tdActions);

                    tableBody.appendChild(tr);
                });
            })
            .catch(err => {
                console.error('Failed to load admin products', err);
                tableBody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Error loading products.</td></tr>`;
            });
    }

function populateDropdowns(productMeta = {}) {
        const {
            product_cat: catId,
            productCat,
            category: catName,
            product_brand: brandId,
            productBrand,
            brand: brandName
        } = productMeta || {};

        const desiredCatId = catId ?? productCat ?? '';
        const desiredCatName = (catName ?? '').toString().trim().toLowerCase();
        const desiredBrandId = brandId ?? productBrand ?? '';
        const desiredBrandName = (brandName ?? '').toString().trim().toLowerCase();

        const catPromise = fetch("../actions/fetch_category_action.php")
            .then(res => res.json())
            .then(cats => {
                const catSelect = document.getElementById("updateProductCat");
                if (!catSelect) return;

                catSelect.innerHTML = `<option value="">Select Category</option>`;
                cats.forEach(c => {
                    const option = document.createElement('option');
                    option.value = c.cat_id;
                    option.textContent = c.cat_name;
                    catSelect.appendChild(option);
                });

                if (desiredCatId !== undefined && desiredCatId !== null && desiredCatId !== '') {
                    catSelect.value = String(desiredCatId);
                }

                // Fallback: match by name if id selection failed
                if (!catSelect.value && desiredCatName) {
                    const match = Array.from(catSelect.options).find(opt => opt.textContent.trim().toLowerCase() === desiredCatName);
                    if (match) catSelect.value = match.value;
                }
            });

        const brandPromise = fetch("../actions/fetch_brand_action.php")
            .then(res => res.json())
            .then(brands => {
                const brandSelect = document.getElementById("updateProductBrand");
                if (!brandSelect) return;

                brandSelect.innerHTML = `<option value="">Select Brand</option>`;
                brands.forEach(b => {
                    const option = document.createElement('option');
                    option.value = b.brand_id;
                    option.textContent = b.brand_name;
                    brandSelect.appendChild(option);
                });

                if (desiredBrandId !== undefined && desiredBrandId !== null && desiredBrandId !== '') {
                    brandSelect.value = String(desiredBrandId);
                }

                if (!brandSelect.value && desiredBrandName) {
                    const match = Array.from(brandSelect.options).find(opt => opt.textContent.trim().toLowerCase() === desiredBrandName);
                    if (match) brandSelect.value = match.value;
                }
            });

        return Promise.all([catPromise, brandPromise]);
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

    // Expose product-specific global and also assign to openForm as a fallback
    window.openProductEditForm = openForm;
    if (!window.openForm) window.openForm = openForm;

    createUpdatePopup();
    closeForm();
    // Only try to load admin products if the table is present on the page
    if (tableBody) loadProducts();


    
});
