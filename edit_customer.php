<?php
include('db.php');

// Check if the customer ID is provided
if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Fetch customer details
    $sql_customer = "SELECT * FROM customers WHERE id = '$customer_id'";
    $result_customer = mysqli_query($conn, $sql_customer);
    $customer = mysqli_fetch_assoc($result_customer);

    // If customer not found, redirect
    if (!$customer) {
        header("Location: view_customers.php");
        exit();
    }

    // Process form submission to update customer data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        // Update customer details in the database
        $sql_update = "UPDATE customers 
                       SET name = '$name', phone = '$phone', email = '$email', address = '$address' 
                       WHERE id = '$customer_id'";

        if (mysqli_query($conn, $sql_update)) {
            $success_message = "Customer details updated successfully!";
        } else {
            $error_message = "Error updating customer: " . mysqli_error($conn);
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
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit Customer - <?php echo $customer['name']; ?></h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Edit Customer Form -->
        <form method="POST" action="edit_customer.php?id=<?php echo $customer['id']; ?>">
            <div class="form-group">
                <label for="name">Customer Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $customer['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $customer['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['email']; ?>">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" required><?php echo $customer['address']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>

        <!-- Back to Customer List -->
        <br><br>
        <a href="view_customers.php" class="btn btn-secondary">Back to Customers</a>
    </div>
</body>
</html>
