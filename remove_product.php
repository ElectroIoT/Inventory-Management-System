<?php
include('db.php');

// Handle removal of product
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // SQL to delete the product
    $sql = "DELETE FROM products WHERE id = '$product_id'";

    if (mysqli_query($conn, $sql)) {
        $success_message = "Product removed successfully!";
    } else {
        $error_message = "Error removing product: " . mysqli_error($conn);
    }
}

// Display all products for removal
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Remove Product</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <!-- Display Product List -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Price (₹)</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td>
                            <a href="remove_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Remove</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Optional: Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
