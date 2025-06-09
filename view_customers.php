<?php
include('db.php');

// Fetch all customers from the database
$sql_customers = "SELECT * FROM customers";
$result_customers = mysqli_query($conn, $sql_customers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Customer List</h2>

        <!-- Add New Customer Button -->
        <a href="add_customer.php" class="btn btn-success mb-3">Add New Customer</a>

        <!-- Display Customer Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Credit Balance (₹)</th>
                    <th>Debit (₹)</th>
                    <th>Cash (₹)</th>
                    <th>PhonePe (₹)</th>
                    <th>Net Balance (₹)</th> <!-- Added Net Balance column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_customers)): ?>
                    <?php
                    // Calculate the net balance for the customer
                    $net_balance = $row['credit_balance'] + $row['debit_balance'] + $row['cash_balance'] + $row['phonepe_balance'];
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo number_format($row['credit_balance'], 2); ?></td>
                        <td><?php echo number_format($row['debit_balance'], 2); ?></td>
                        <td><?php echo number_format($row['cash_balance'], 2); ?></td>
                        <td><?php echo number_format($row['phonepe_balance'], 2); ?></td>
                        <td><?php echo number_format($net_balance, 2); ?></td> <!-- Display Net Balance -->
                        <td>
                            <a href="edit_customer.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="remove_customer.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Remove</a>
                            <a href="update_balance.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Update Balance</a>
                            <a href="customer_report.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Report</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
