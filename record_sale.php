<?php
include('db.php');

// Fetch list of products (only those with stock)
$sql_products = "SELECT * FROM products WHERE stock > 0";
$result_products = mysqli_query($conn, $sql_products);

// Fetch list of customers
$sql_customers = "SELECT * FROM customers";
$result_customers = mysqli_query($conn, $sql_customers);

// Process the form submission for sale
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $payment_mode = $_POST['payment_mode'];

    // Fetch product details
    $sql_product = "SELECT * FROM products WHERE id = '$product_id'";
    $result_product = mysqli_query($conn, $sql_product);
    $product = mysqli_fetch_assoc($result_product);

    if ($quantity > $product['stock']) {
        $error_message = "Not enough stock!";
    } else {
        // Calculate total price and GST
        $total = $product['price'] * $quantity;
        $gst = ($total * $product['gst']) / 100;
        $total_with_gst = $total + $gst;

        // Check if payment is from net balance (credit)
        if ($payment_mode == 'credit') {
            // Fetch customer's current net balance
            $sql_customer = "SELECT * FROM customers WHERE id = '$customer_id'";
            $result_customer = mysqli_query($conn, $sql_customer);
            $customer = mysqli_fetch_assoc($result_customer);

            // Calculate the remaining balance after deduction
            $new_net_balance = $customer['credit_balance'] + $customer['debit_balance'] + $customer['cash_balance'] + $customer['phonepe_balance'] - $total_with_gst;

            // Check if the customer has enough balance
            if ($new_net_balance < 0) {
                $error_message = "Not enough net balance!";
            } else {
                // Proceed with the sale and update the balances
                $sql_sale = "INSERT INTO sales (customer_id, product_id, quantity, total, gst, payment_mode) 
                             VALUES ('$customer_id', '$product_id', '$quantity', '$total_with_gst', '$gst', '$payment_mode')";
                if (mysqli_query($conn, $sql_sale)) {
                    // Update stock for the product
                    $new_stock = $product['stock'] - $quantity;
                    $sql_update_stock = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
                    mysqli_query($conn, $sql_update_stock);

                    // Update customer's net balance (debit balance)
                    $sql_update_customer = "UPDATE customers SET 
                                            credit_balance = credit_balance - '$total_with_gst', 
                                            debit_balance = debit_balance - '$total_with_gst'
                                            WHERE id = '$customer_id'";
                    mysqli_query($conn, $sql_update_customer);

                    $success_message = "Sale recorded successfully and net balance updated!";
                } else {
                    $error_message = "Error recording sale: " . mysqli_error($conn);
                }
            }
        } else {
            // Proceed with sale for cash/online payment
            $sql_sale = "INSERT INTO sales (customer_id, product_id, quantity, total, gst, payment_mode) 
                         VALUES ('$customer_id', '$product_id', '$quantity', '$total_with_gst', '$gst', '$payment_mode')";
            if (mysqli_query($conn, $sql_sale)) {
                // Update stock for the product
                $new_stock = $product['stock'] - $quantity;
                $sql_update_stock = "UPDATE products SET stock = '$new_stock' WHERE id = '$product_id'";
                mysqli_query($conn, $sql_update_stock);

                $success_message = "Sale recorded successfully!";
            } else {
                $error_message = "Error recording sale: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Sale</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Record Sale</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Sale Form -->
        <form method="POST" action="record_sale.php">
            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select class="form-control" id="customer_id" name="customer_id" required>
                    <option value="">Select Customer</option>
                    <?php while ($customer = mysqli_fetch_assoc($result_customers)): ?>
                        <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="product_id">Product</label>
                <select class="form-control" id="product_id" name="product_id" required>
                    <option value="">Select Product</option>
                    <?php while ($product = mysqli_fetch_assoc($result_products)): ?>
                        <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?> (₹<?php echo $product['price']; ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="payment_mode">Payment Mode</label>
                <select class="form-control" id="payment_mode" name="payment_mode" required>
                    <option value="credit">Credit (Net Balance)</option>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Record Sale</button>
        </form>

        <!-- Back to Dashboard Button -->
        <br><br>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
