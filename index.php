<?php
require_once 'settings/core.php';

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

// get user info
$user_id = getUserID();
$user_name = getUserName($user_id) ?? '';
$role = getUserRole();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
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
		<?php if (isset($_SESSION['user_id'])): ?>
			
			<a href="index.php" class="btn btn-sm btn-outline-secondary">Home</a>
			<a href="login/logout.php" class="btn btn-sm btn-outline-secondary">Logout</a>

		<?php else: ?>
			<a href="login/register.php" class="btn btn-sm btn-outline-primary">Register</a>
			<a href="login/login.php" class="btn btn-sm btn-outline-secondary">Login</a>
		<?php endif; ?>			
	</div>

	<div class="container" style="padding-top:120px;">
		<div class="text-center">

			<?php if ($role == 1) : ?>
				<h1>Welcome, <?php echo htmlspecialchars($user_name ?: 'Admin'); ?></h1>
                <a href="admin/category.php" class="btn btn-sm btn-outline-primary">Categories</a>
				<a href="admin/product.php" class="btn btn-sm btn-outline-primary">Products</a>
				<a href="admin/customers.php" class="btn btn-sm btn-outline-primary">Customers</a>
				<a href="admin/orders.php" class="btn btn-sm btn-outline-primary">Orders</a>
				<a href="admin/brand.php" class="btn btn-sm btn-outline-primary">Brands</a>

				<h3><br> To explore the customer view</h3>
				<a href="view/all_product.php" class="btn btn-sm btn-outline-primary">Shop Products</a>
			<?php elseif ($role == 2) : ?>
				<h1>Welcome, <?php echo htmlspecialchars($user_name ?: 'Guest'); ?>!</h1>
				<p class="lead">Find something delicious in the shop below.</p>
				<a href="view/all_product.php" class="btn btn-sm btn-outline-primary">Shop Products<br></a>

				    <div class="container header-container">
					
				</div>
	
			<?php endif; ?>

			<div class="row justify-content-center animate__animated animate__fadeInDown"></div>
					<input type="text" id="searchBox" placeholder="Search products...">
					<button id="searchBtn">Search</button>

					<select id="categoryFilter">
						<option value="">Filter by Category</option>
					</select>

					<select id="brandFilter">
						<option value="">Filter by Brand</option>
					</select>
			</div>

			<?php if (isset($_SESSION['user_id'])): ?>
				<p class="text-muted">Use the menu in the top-right to Logout.</p>			
			<?php else: ?>
				<p class="text-muted">Use the menu in the top-right to Register or Login.</p>
			<?php endif; ?>

			

		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>