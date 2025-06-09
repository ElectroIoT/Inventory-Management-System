<?php
include('db.php');
session_start();

// Check if the customer ID is provided
if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Fetch customer details
    $sql_customer = "SELECT * FROM customers WHERE id = '$customer_id'";
    $result_customer = mysqli_query($conn, $sql_customer);
    $customer = mysqli_fetch_assoc($result_customer);
    if (!$customer) {
        header("Location: view_customers.php"); // Redirect if customer not found
        exit();
    }

    // Fetch all sales for the customer
    $sql_sales = "SELECT sales.id, sales.quantity, sales.total, sales.gst, sales.sale_date, sales.payment_mode, products.name AS product_name
                  FROM sales
                  INNER JOIN products ON sales.product_id = products.id
                  WHERE sales.customer_id = '$customer_id'
                  ORDER BY sales.sale_date DESC";
    $result_sales = mysqli_query($conn, $sql_sales);
    if (!$result_sales) {
        die("Error fetching sales data: " . mysqli_error($conn));
    }

    // Fetch all transactions for the customer
    $sql_transactions = "SELECT * FROM transactions WHERE customer_id = '$customer_id' ORDER BY transaction_date DESC";
    $result_transactions = mysqli_query($conn, $sql_transactions);
    if (!$result_transactions) {
        die("Error fetching transactions data: " . mysqli_error($conn));
    }

    // Handle Export to CSV request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_csv'])) {
        // Output headers to prompt file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="customer_report.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV column headings
        fputcsv($output, ['Customer Name', 'Email', 'Phone', 'Address', 'Net Balance']);
        
        // Add customer details
        fputcsv($output, [$customer['name'], $customer['email'], $customer['phone'], $customer['address'], $customer['credit_balance'] + $customer['debit_balance'] + $customer['cash_balance'] + $customer['phonepe_balance']]);

        // Add CSV header for sales
        fputcsv($output, ['Transaction Date', 'Product', 'Quantity', 'Total (₹)', 'GST (₹)', 'Payment Mode']);

        // Add sales data to CSV
        while ($sale = mysqli_fetch_assoc($result_sales)) {
            fputcsv($output, [$sale['sale_date'], $sale['product_name'], $sale['quantity'], $sale['total'], $sale['gst'], $sale['payment_mode']]);
        }

        // Add CSV header for transactions
        fputcsv($output, ['Transaction Type', 'Amount (₹)', 'Transaction Date']);

        // Add transaction data to CSV
        while ($transaction = mysqli_fetch_assoc($result_transactions)) {
            fputcsv($output, [$transaction['transaction_type'], $transaction['amount'], $transaction['transaction_date']]);
        }

        // Close the output stream
        fclose($output);
        exit(); // Prevent further code execution
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Customer Report: <?php echo $customer['name']; ?></h2>

    <!-- Customer Details Section -->
    <h4>Customer Details</h4>
    <p><strong>Name:</strong> <?php echo $customer['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $customer['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $customer['phone']; ?></p>
    <p><strong>Credit Balance (₹):</strong> <?php echo number_format($customer['credit_balance'], 2); ?></p>
    <p><strong>Debit (₹):</strong> <?php echo number_format($customer['debit_balance'], 2); ?></p>
    <p><strong>Cash (₹):</strong> <?php echo number_format($customer['cash_balance'], 2); ?></p>
    <p><strong>PhonePe (₹):</strong> <?php echo number_format($customer['phonepe_balance'], 2); ?></p>

    <h4>Net Balance (₹)</h4>
    <p><?php echo number_format($customer['credit_balance'] + $customer['debit_balance'] + $customer['cash_balance'] + $customer['phonepe_balance'], 2); ?></p>

    <!-- Sales Table -->
    <h4>Sales</h4>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total (₹)</th>
                <th>GST (₹)</th>
                <th>Sale Date</th>
                <th>Payment Mode</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sale = mysqli_fetch_assoc($result_sales)): ?>
            <tr>
                <td><?php echo $sale['id']; ?></td>
                <td><?php echo $sale['product_name']; ?></td>
                <td><?php echo $sale['quantity']; ?></td>
                <td><?php echo number_format($sale['total'], 2); ?></td>
                <td><?php echo number_format($sale['gst'], 2); ?></td>
                <td><?php echo $sale['sale_date']; ?></td>
                <td><?php echo ucfirst($sale['payment_mode']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Transactions Table -->
    <h4>Transactions</h4>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Transaction Type</th>
                <th>Amount (₹)</th>
                <th>Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($transaction = mysqli_fetch_assoc($result_transactions)): ?>
            <tr>
                <td><?php echo $transaction['id']; ?></td>
                <td><?php echo ucfirst($transaction['transaction_type']); ?></td>
                <td><?php echo number_format($transaction['amount'], 2); ?></td>
                <td><?php echo $transaction['transaction_date']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Export and Print Buttons -->
    <div class="text-center">
        <form method="POST" action="customer_report.php?id=<?php echo $customer_id; ?>">
            <button class="btn btn-primary" type="submit" name="export_csv">Export to CSV</button>
        </form>
        <button class="btn btn-secondary" onclick="window.print()">Print</button>
    </div>

    <!-- Back to Customer List Button -->
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

</body>
</html>
