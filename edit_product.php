<?php
include('db.php');

// Check if product ID is provided in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $product = mysqli_fetch_assoc($result);
    } else {
        die("Product not found.");
    }
} else {
    die("Product ID is missing.");
}

// Handle form submission to update product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $gst = $_POST['gst'];
    $stock = $_POST['stock'];
    
    // Update the product in the database
    $update_sql = "UPDATE products SET name = '$name', category = '$category', price = '$price', gst = '$gst', stock = '$stock' WHERE id = '$product_id'";

    if (mysqli_query($conn, $update_sql)) {
        $success_message = "Product updated successfully!";
        // Optionally redirect to inventory page
        // header("Location: inventory.php");
    } else {
        $error_message = "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        
        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Edit Product Form -->
        <form method="POST" action="edit_product.php?id=<?php echo $product_id; ?>">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo $product['category']; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="gst">GST (%)</label>
                <input type="number" class="form-control" id="gst" name="gst" value="<?php echo $product['gst']; ?>" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>

        <!-- Back to Dashboard Button -->
        <br><br>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
