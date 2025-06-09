<?php
include('db.php');

// Fetch all sales with customer name and product details
$sql_sales = "
    SELECT sales.id, sales.customer_id, sales.product_id, sales.quantity, sales.total, sales.gst, sales.sale_date, sales.payment_mode, 
           customers.name AS customer_name, products.name AS product_name
    FROM sales
    INNER JOIN customers ON sales.customer_id = customers.id
    INNER JOIN products ON sales.product_id = products.id
    ORDER BY sales.sale_date DESC
";
$result_sales = mysqli_query($conn, $sql_sales);

// Calculate total sales
$sql_total_sales = "SELECT SUM(total) AS total_sales FROM sales";
$result_total_sales = mysqli_query($conn, $sql_total_sales);
$total_sales_row = mysqli_fetch_assoc($result_total_sales);
$total_sales = $total_sales_row['total_sales'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Sales Report</h2>

        <!-- Display Total Sales -->
        <div class="alert alert-info">
            <strong>Total Sales: ₹<?php echo number_format($total_sales, 2); ?></strong>
        </div>

        <!-- Sales Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Amount (₹)</th>
                    <th>GST (₹)</th>
                    <th>Sale Date</th>
                    <th>Payment Mode</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_sales)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo number_format($row['total'], 2); ?></td>
                        <td><?php echo number_format($row['gst'], 2); ?></td>
                        <td><?php echo $row['sale_date']; ?></td>
                        <td><?php echo ucfirst($row['payment_mode']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
