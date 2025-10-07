<?php
//session_start();
require_once '../settings/core.php';
require_once '../settings/db_class.php';

$user_id = getUserID();
$role = getUserRole();


$db = new db_connection();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Categories - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        .small{
            width: 30px;
        }

        .btn-custom {
            background-color: #D19C97;
            border-color: #D19C97;
            color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #b77a7a;
            border-color: #b77a7a;
        }

        .highlight {
            color: #D19C97;
            transition: color 0.3s;
        }

        .highlight:hover {
            color: #b77a7a;
        }

        body {
            /* Base background color */
            background-color: #f8f9fa;

            /* Gradient-like grid using repeating-linear-gradients */
            background-image:
                repeating-linear-gradient(0deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                repeating-linear-gradient(90deg,
                    #b77a7a,
                    #b77a7a 1px,
                    transparent 1px,
                    transparent 20px),
                linear-gradient(rgba(183, 122, 122, 0.1),
                    rgba(183, 122, 122, 0.1));

            /* Blend the gradients for a subtle overlay effect */
            background-blend-mode: overlay;

            /* Define the size of the grid */
            background-size: 20px 20px;

            /* Ensure the background covers the entire viewport */
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .header-container {
            margin-top: 100px;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #D19C97;
            color: #fff;
        }

        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Additional Styling for Enhanced Appearance */
        .form-label i {
            margin-left: 5px;
            color: #b77a7a;
        }

        .alert-info {
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .form-popup {
            display: none; 
            position: fixed;
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4); 
            padding-top: 50px; 
            justify-content: center;
            align-items: center;
        }

        .menu-tray {
			position: fixed;
			top: 16px;
			right: 16px;
			background: rgba(255,255,255,0.95);
			border: 1px solid #e6e6e6;
			border-radius: 8px;
			padding: 6px 10px;
			box-shadow: 0 4px 10px rgba(0,0,0,0.06);
			z-index: 1000;
		}
		.menu-tray a { margin-left: 8px; }
    </style>
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
		<?php endif; ?>			
	</div>


    <div class="container header-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
           
            <h1>Categories</h1>
            <h4>All the categories you have created are listed below</h4>
        
            <!-- Create category form-->
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Create a new category</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="" class="mt-4" id="addCatForm">
                            <div class="mb-3">
                                <label for="catName" class="form-label">Category Name</label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="cat_name" name="cat_name" required>
                                <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                            </div>

                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Add New Category</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- View Categories-->
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Your current Categories</h4>
                    </div>


                    <div class="card-body">
                        <table id="catTable">
                            <thead>
                                <tr>
                                    <th>Category ID</th>
                                    <th>Category Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>No categories available</td>
                                <td></td>
                                <td>
                                    <button onclick="openForm()" class="small btn btn-custom w-100 animate-pulse-custom">Edit</button>
                                        <!-- Update Category Popup -->
                                            <div id="updatePopup" class="form-popup">
                                                <div class="card mx-auto p-4" style="max-width: 400px; background-color: #fff; border-radius: 10px;">
                                                    <h5 class="text-center mb-3 highlight">Update Category</h5>
                                                    <form id="updateForm">
                                                        <div class="mb-3">
                                                            <label for="update_cat_name" class="form-label">New Category Name</label>
                                                            <input type="text" class="form-control" id="update_cat_name" required>
                                                            <input type="hidden" id="update_cat_id">
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button type="button" id="saveUpdate" class="btn btn-custom">Save</button>
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
    <script src="../js/category.js"></script>

    
</body>

</html>

