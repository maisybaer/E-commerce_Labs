document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("#catTable tbody");

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

    // Pop-up edit logic
    let currentEditCatId = null;

    function createEditPopup() {
        if (document.getElementById("updateCatForm")) return;
        let popup = document.createElement("div");
        popup.className = "form-popup";
        popup.id = "updateCatForm";
        popup.style.display = "none";
        popup.innerHTML = `
            <div class="card" style="max-width:400px;margin:auto;">
                <div class="card-body">
                    <form id="editCatForm">
                        <div class="mb-3">
                            <label for="editCatName" class="form-label">Edit Category Name</label>
                            <input type="text" class="form-control" id="editCatName" name="editCatName" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Update</button>
                        <button type="button" class="btn btn-secondary w-100 mt-2" id="closeEditPopup">Cancel</button>
                    </form>
                </div>
            </div>
        `;
        document.body.appendChild(popup);

        document.getElementById("closeEditPopup").onclick = closeForm;

        document.getElementById("editCatForm").onsubmit = function(e) {
            e.preventDefault();
            let cat_name = document.getElementById("editCatName").value;
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
