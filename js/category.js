document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#catTable tbody");
    const form = document.getElementById("addCatForm");

    // Fetch categories
    function loadCategories() {
        fetch("../actions/fetch_category_action.php")
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = "";
                data.forEach(cat => {
                    let row = `
                        <tr>
                            <td>${cat.cat_id}</td>
                            <td>${cat.cat_name}</td>
                            <td>
                                <button onclick="updateCategory(${cat.cat_id})">Update</button>
                                <button onclick="deleteCategory(${cat.cat_id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            });
    }

    loadCategories();

    // Add category
    $('#addCatForm').submit(function(e) {
        e.preventDefault();

        let cat_name = $('#cat_name').val();
        let user_id = $('#user_id').val();

        if (cat_name == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill the name of your new category!',
            });

                return;
        }

        let formData = new FormData();
        formData.append('cat_name', cat_name);
        formData.append('user_id', user_id);

        fetch("../actions/add_category_action.php", {
            method: "POST",
            body: formData
        })

        .then(res => res.json())
        .then(resp => {
            Swal.fire({
                icon: 'success',
                title: 'Added!',
                text: resp.message,
            });
        
            loadCategories();
            $('#addCatForm')[0].reset();
        });
    });

    // Inline Edit Category
    window.editCategory = function (cat_id, cat_name) {
        const nameCell = document.getElementById(`catName-${cat_id}`);
        const row = document.getElementById(`row-${cat_id}`);

        // Replace text with input box
        nameCell.innerHTML = `<input type="text" id="editInput-${cat_id}" value="${cat_name}" class="form-control" />`;

        // Replace buttons with save and cancel
        row.children[2].innerHTML = `
            <button class="btn btn-success btn-sm" onclick="saveCategory(${cat_id})">Save</button>
            <button class="btn btn-secondary btn-sm" onclick="cancelEdit(${cat_id}, '${cat_name}')">Cancel</button>
        `;
    };

    // Save Edited Category
    window.saveCategory = function (cat_id) {
        const newName = document.getElementById(`editInput-${cat_id}`).value.trim();

        if (newName === "") {
            Swal.fire({
                icon: "error",
                title: "Empty Field",
                text: "Category name cannot be empty!",
            });
            return;
        }

        let formData = new FormData();
        formData.append("cat_id", cat_id);
        formData.append("cat_name", newName);

        fetch("../actions/update_category_action.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            Swal.fire({
                icon: resp.status === "success" ? "success" : "error",
                title: resp.status === "success" ? "Updated!" : "Error",
                text: resp.message,
            });
            loadCategories();
        });
    };

    // Cancel Edit
    window.cancelEdit = function (cat_id, oldName) {
        const nameCell = document.getElementById(`catName-${cat_id}`);
        const row = document.getElementById(`row-${cat_id}`);

        nameCell.textContent = oldName;
        row.children[2].innerHTML = `
            <button class="btn btn-sm btn-primary" onclick="editCategory(${cat_id}, '${oldName}')">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="deleteCategory(${cat_id})">Delete</button>
        `;
    };

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

});    
