<?php
// Include the database connection
include('db.php');

// SQL queries to fetch the data

// Monthly sales totals for the last 12 months
$sql_monthly_sales = "
    SELECT 
        DATE_FORMAT(sale_date, '%Y-%m') AS month, 
        SUM(total) AS total_sales
    FROM sales
    WHERE sale_date >= CURDATE() - INTERVAL 12 MONTH
    GROUP BY month
    ORDER BY month DESC
";
$result_monthly_sales = mysqli_query($conn, $sql_monthly_sales);

// Top-selling products (top 5)
$sql_top_products = "
    SELECT 
        products.name AS product_name,
        SUM(sales.quantity) AS total_quantity
    FROM sales
    INNER JOIN products ON sales.product_id = products.id
    GROUP BY product_name
    ORDER BY total_quantity DESC
    LIMIT 5
";
$result_top_products = mysqli_query($conn, $sql_top_products);

// Sales by payment mode (credit, cash, online)
$sql_sales_payment_mode = "
    SELECT 
        payment_mode,
        SUM(total) AS total_sales
    FROM sales
    GROUP BY payment_mode
";
$result_sales_payment_mode = mysqli_query($conn, $sql_sales_payment_mode);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Performance Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .dashboard-container {
            margin-top: 50px;
        }
        footer {
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <h2 class="text-center">Sales Performance Report</h2>

        <!-- Text Report -->
        <div class="mt-5">
            <h3>Sales Performance Report</h3>
            <p><strong>Total Sales for the Last 12 Months:</strong> ₹<?php
                $total_sales = 0;
                while ($row = mysqli_fetch_assoc($result_monthly_sales)) {
                    $total_sales += $row['total_sales'];
                }
                echo number_format($total_sales, 2);
            ?></p>

            <h4>Top-Selling Products</h4>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result_top_products)): ?>
                    <li><?php echo $row['product_name'] . ": " . $row['total_quantity'] . " units"; ?></li>
                <?php endwhile; ?>
            </ul>

            <h4>Sales by Payment Mode</h4>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result_sales_payment_mode)): ?>
                    <li><?php echo ucfirst($row['payment_mode']) . ": ₹" . number_format($row['total_sales'], 2); ?></li>
                <?php endwhile; ?>
            </ul>
        </div>

        <!-- Print Button -->
        <div class="text-center mt-3">
            <button class="btn btn-primary" onclick="window.print()">Print Report</button>
        </div>

        <!-- Back to Dashboard Button -->
        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-secondary btn-lg">Back to Dashboard</a>
        </div>
    </div>

    <!-- Optional: Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
