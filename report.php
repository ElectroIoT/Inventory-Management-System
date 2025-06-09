<?php
include('db.php');

// Query sales data
$sql = "SELECT p.name, SUM(s.quantity) AS total_quantity, SUM(s.total) AS total_sales 
        FROM sales s 
        JOIN products p ON s.product_id = p.id
        GROUP BY p.name";
$result = mysqli_query($conn, $sql);
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

        <!-- Display Sales Report -->
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity Sold</th>
                    <th>Total Sales (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['total_quantity']; ?></td>
                        <td><?php echo $row['total_sales']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
