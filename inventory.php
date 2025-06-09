<?php
include('db.php');

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Manage Inventory</h2>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <!-- Display Product List -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Category</th>
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
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
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
