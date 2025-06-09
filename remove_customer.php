<?php
// Start the session to track admin login
session_start();
include('db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the admin is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Store the admin password (for demonstration purposes, in practice, hash and store securely)
$admin_password = "admin123";  // Hardcoded for this example (not recommended in real applications)

// Check if customer ID is provided
if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $entered_password = $_POST['admin_password'];

        // Validate the entered admin password
        if ($entered_password == $admin_password) {
            // Delete the customer from the database if the password is correct
            $sql_remove = "DELETE FROM customers WHERE id = '$customer_id'";

            if (mysqli_query($conn, $sql_remove)) {
                $success_message = "Customer removed successfully!";
            } else {
                $error_message = "Error removing customer: " . mysqli_error($conn);
            }
        } else {
            // If the password is incorrect
            $error_message = "Incorrect admin password!";
        }
    }
} else {
    // If no customer ID is provided, redirect to customer list
    header("Location: view_customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Remove Customer</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form to prompt admin for password -->
        <h5>To confirm deletion, please enter your admin password:</h5>
        <form method="POST" action="remove_customer.php?id=<?php echo $customer_id; ?>">
            <div class="form-group">
                <label for="admin_password">Admin Password</label>
                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
            </div>
            <button type="submit" class="btn btn-danger">Confirm Deletion</button>
        </form>

        <!-- Back to Customer List Button -->
        <br><br>
        <a href="view_customers.php" class="btn btn-secondary">Back to Customers</a>
    </div>
</body>
</html>
