<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    $total_price = $product['price'] * $quantity;
    $gst_amount = $total_price * ($product['gst'] / 100);
    $total_with_gst = $total_price + $gst_amount;

    // Insert sale into database
    $sale_sql = "INSERT INTO sales (product_id, quantity, total, gst) VALUES ('$product_id', '$quantity', '$total_with_gst', '$gst_amount')";
    if (mysqli_query($conn, $sale_sql)) {
        echo "Invoice generated successfully!";
    } else {
        echo "Error: " . $sale_sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Generate Invoice</h2>
        <form method="POST" action="invoice.php">
            <div class="form-group">
                <label for="product_id">Product</label>
                <select class="form-control" id="product_id" name="product_id">
                    <?php
                    $sql = "SELECT * FROM products";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id']}'>{$row['name']} - ₹{$row['price']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Invoice</button>
        </form>
    </div>
</body>
</html>
