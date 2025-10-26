document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#productTable tbody");

    // Add Product
    $('#addProductForm').submit(function(e) {
        e.preventDefault();

        let category = $('#category').val();
        let brand = $('#brand').val();
        let title = $('#product_title').val();
        let price = $('#product_price').val();
        let description = $('#product_description').val();
        let product_image = $('#product_image')[0].files[0];
        let keyword = $('#productkeyword').val();
        let user_id = $('#user_id').val();

        if (category == '' || brand == '' || title == '' ||price == ''|| description == ''|| keyword == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });

            return;

        } elseif(!price.match(/[0-9]/)){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'The price must be a number!',
            });

            return;

        }}

       //prepare data to accept image
        let formData = new FormData();
        formData.append('category', category);
        formData.append('brand', brand);
        formData.append('title', title);
        formData.append('price', price);
        formData.append('description', description);
        formData.append('keyword', keyword);

        if (product_image) {
            formData.append('product_image', product_image);
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
                    text: 'An error occurred! Please try again later. Troubleshoot: See product.js_ajax ',
                });
            }
        });
    });
});

    // Update Product (Pop up form)
    let currentProductId = null;

    function createEditPopup() {
        if (document.getElementById("updateProductForm")) return;
        let popup = document.createElement("div");
        popup.className = "form-popup";
        popup.id = "updateProductForm";
        popup.style.display = "none";
        popup.innerHTML = `
            <div class="card" style="max-width:400px;margin:auto;">
                <div class="card-body">
                    <form id="editProductForm">
                        <div class="mb-3">

                            <label for="editProductTitle" class="form-label">Edit Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="editProductName" required>
                       
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Update</button>
                        <button type="button" class="btn btn-secondary w-100 mt-2" id="closeEditPopup">Cancel</button>
                    </form>
                </div>
            </div>
        `;
        document.body.appendChild(popup);

        document.getElementById("closeEditPopup").onclick = closeForm;

        document.getElementById("editProductForm").onsubmit = function(e) {
            e.preventDefault();
            let product_title = document.getElementById("editProductName").value;
            let formData = new FormData();
            formData.append("cat_id", currentEditCatId);
            formData.append("cat_name", cat_name);

            fetch("../actions/update_category_action.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(resp => {
                alert(resp.message);
                closeForm();
                loadCategories();
            });
        };
    }

    function openForm(cat_id, cat_name) {
        currentEditCatId = cat_id;
        let popup = document.getElementById("updateCatForm");
        document.getElementById("editCatName").value = cat_name;
        popup.style.display = "block";
    }

    function closeForm() {
        let popup = document.getElementById("updateCatForm");
        popup.style.display = "none";
    }

    // Fetch categories
    function loadCategories() {
        fetch("../actions/fetch_category_action.php")
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = "";
                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="3">No categories available</td></tr>`;
                } else {
                    data.forEach(cat => {
                        let row = `
                            <tr>
                                <td>${cat.cat_id}</td>
                                <td>${cat.cat_name}</td>
                                <td>
                                    <button onclick="openForm(${cat.cat_id}, '${cat.cat_name.replace(/'/g, "\\'")}')" class="btn btn-custom btn-sm">Edit</button>
                                    <button onclick="deleteCategory(${cat.cat_id})" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                }
            });
    }

    // Delete category
    window.deleteCategory = function (cat_id) {
        let formData = new FormData();
        formData.append("cat_id", cat_id);

        fetch("../actions/delete_category_action.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            alert(resp.message);
            loadCategories();
        });
    };

    // Expose openForm globally for inline button
    window.openForm = openForm;

    // Initial setup 
    createEditPopup();
    closeForm();
    loadCategories();
});
