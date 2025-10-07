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

    // Update category
    window.updateCategory = function (cat_id) {
        let cat_name = document.getElementById(`cat-${cat_id}`).value;
        let formData = new FormData();
        formData.append("cat_name", cat_name);

        fetch("../actions/update_category_action.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            alert(resp.message);
            loadCategories();
        });
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

    //form interactivity
    

    window.openForm = function () {
        document.getElementById("updateCatForm").style.display = "block";
    };

    window.closeForm = function () {
        document.getElementById("updateCatForm").style.display = "none";
    };
});    
