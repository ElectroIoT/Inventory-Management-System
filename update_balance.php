<?php
include('db.php');

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

    // Process form submission to update customer balance
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $transaction_type = $_POST['transaction_type'];  // Debit, Credit, PhonePe
        $amount = $_POST['amount'];

        // Update the relevant balance based on transaction type
        if ($transaction_type == 'credit') {
            $new_balance = $customer['credit_balance'] + $amount;
            $update_sql = "UPDATE customers SET credit_balance = '$new_balance' WHERE id = '$customer_id'";
        } elseif ($transaction_type == 'phonepe') {
            $new_balance = $customer['phonepe_balance'] + $amount;
            $update_sql = "UPDATE customers SET phonepe_balance = '$new_balance' WHERE id = '$customer_id'";
        }

        // Log the transaction in the transactions table
        $transaction_sql = "INSERT INTO transactions (customer_id, transaction_type, amount) 
                            VALUES ('$customer_id', '$transaction_type', '$amount')";
        mysqli_query($conn, $transaction_sql);

        if (mysqli_query($conn, $update_sql)) {
            $success_message = "Balance updated successfully and transaction recorded!";
        } else {
            $error_message = "Error updating balance: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: view_customers.php"); // Redirect if no ID provided
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Customer Balance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Update Balance for <?php echo $customer['name']; ?></h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Transaction Form -->
        <form method="POST" action="update_balance.php?id=<?php echo $customer['id']; ?>">
            <div class="form-group">
                <label for="transaction_type">Transaction Type</label>
                <select class="form-control" id="transaction_type" name="transaction_type" required>
                    <option value="">Select Transaction Type</option>
                    <option value="credit">Credit</option>
                    <option value="phonepe">PhonePe</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Balance</button>
        </form>

        <!-- Back to Customers List -->
        <br><br>
        <a href="view_customers.php" class="btn btn-secondary">Back to Customers</a>
    </div>
</body>
</html>
