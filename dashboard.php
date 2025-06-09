<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include('db.php');

// Fetch basic statistics (total products and total sales)
$sql_products = "SELECT COUNT(*) AS total_products FROM products";
$result_products = mysqli_query($conn, $sql_products);
if ($result_products) {
    $row_products = mysqli_fetch_assoc($result_products);
    $total_products = $row_products['total_products'];
} else {
    die("Error fetching total products: " . mysqli_error($conn));
}

$sql_sales = "SELECT SUM(total) AS total_sales FROM sales";
$result_sales = mysqli_query($conn, $sql_sales);
if ($result_sales) {
    $row_sales = mysqli_fetch_assoc($result_sales);
    $total_sales = $row_sales['total_sales'];
} else {
    die("Error fetching total sales: " . mysqli_error($conn));
}

// Fetch recent products (last 5)
$recent_products_sql = "SELECT name, created_at FROM products ORDER BY created_at DESC LIMIT 5";
$recent_products_result = mysqli_query($conn, $recent_products_sql);
if (!$recent_products_result) {
    die("Error fetching recent products: " . mysqli_error($conn));
}

// Fetch products with low stock (less than 5)
$low_stock_sql = "SELECT name, stock FROM products WHERE stock < 5";
$low_stock_result = mysqli_query($conn, $low_stock_sql);
if ($low_stock_result) {
    $low_stock_count = mysqli_num_rows($low_stock_result);
} else {
    die("Error fetching low stock products: " . mysqli_error($conn));
}

// Fetch the total number of customers (for management and reporting purposes)
$sql_customers = "SELECT COUNT(*) AS total_customers FROM customers";
$result_customers = mysqli_query($conn, $sql_customers);
if ($result_customers) {
    $row_customers = mysqli_fetch_assoc($result_customers);
    $total_customers = $row_customers['total_customers'];
} else {
    die("Error fetching total customers: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-lg {
            margin: 5px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <h2 class="text-center">Admin Dashboard</h2>

        <!-- Dashboard Summary Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text"><?php echo $total_products; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales (₹)</h5>
                        <p class="card-text"><?php echo number_format($total_sales, 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Low Stock Alerts</h5>
                        <?php
                        if ($low_stock_count > 0) {
                            echo "<p class='card-text'>You have $low_stock_count products with low stock!</p>";
                        } else {
                            echo "<p class='card-text'>All products are well-stocked.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Total Customers</h5>
                        <p class="card-text"><?php echo $total_customers; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center">
            <a href="inventory.php" class="btn btn-primary btn-lg">Manage Inventory</a>
            <a href="add_category.php" class="btn btn-info btn-lg">Manage Categories</a>
            <a href="add_product.php" class="btn btn-success btn-lg">Add New Product</a>
            <a href="record_sale.php" class="btn btn-warning btn-lg">Record Sale</a>
            <a href="sales.php" class="btn btn-secondary btn-lg">View Sales</a>
            <a href="view_customers.php" class="btn btn-custom btn-lg">View Customers</a>
            <a href="sales_performance.php" class="btn btn-info btn-lg">View Sales Performance</a> <!-- New Button -->
            <a href="logout.php" class="btn btn-danger btn-lg">Logout</a>
        </div>

        <!-- Recent Activity Section -->
        <div class="mt-5">
            <h4>Recent Activity</h4>
            <ul class="list-group">
                <?php
                // Fetch recent products added (last 5 products)
                while ($product = mysqli_fetch_assoc($recent_products_result)) {
                    echo "<li class='list-group-item'>" . $product['name'] . " (Added on: " . $product['created_at'] . ")</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025. All rights reserved. | Developed by <strong>Manoranjan</strong> | Email: <a href="mailto:MANORANJAN2050@LIVE.COM">MANORANJAN2050@LIVE.COM</a></p>
    </footer>

    <!-- Optional: Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
