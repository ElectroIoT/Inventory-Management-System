<?php
include('db.php');

// Process the form to add a category
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];

    // Insert the new category into the database
    $sql = "INSERT INTO categories (name) VALUES ('$category_name')";
    
    if (mysqli_query($conn, $sql)) {
        $success_message = "Category added successfully!";
    } else {
        $error_message = "Error adding category: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add New Category</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Category Form -->
        <form method="POST" action="add_category.php">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>

        <!-- Back to Dashboard Button -->
        <br><br>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
