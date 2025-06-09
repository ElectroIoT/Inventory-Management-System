<?php
include('db.php');

// Fetch categories for the dropdown
$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);

// Process the form to add a product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $gst = $_POST['gst'];
    $stock = $_POST['stock'];

    // Insert product into database
    $sql = "INSERT INTO products (name, category, price, gst, stock) 
            VALUES ('$name', '$category_id', '$price', '$gst', '$stock')";
    
    if (mysqli_query($conn, $sql)) {
        $success_message = "Product added successfully!";
    } else {
        $error_message = "Error adding product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add New Product</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Add Product Form -->
        <form method="POST" action="add_product.php">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($row = mysqli_fetch_assoc($result_categories)): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="gst">GST (%)</label>
                <input type="number" class="form-control" id="gst" name="gst" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>

        <!-- Back to Dashboard Button -->
        <br><br>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
