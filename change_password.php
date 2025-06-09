<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$admin_email = $_SESSION['user'];  // Assuming the email (or username) is stored in the session

// Handle password change request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate current password
    $sql = "SELECT password FROM users WHERE email = '$admin_email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($current_password, $user['password'])) {
        if ($new_password == $confirm_password) {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$admin_email'";

            if (mysqli_query($conn, $update_sql)) {
                $success_message = "Password updated successfully!";
            } else {
                $error_message = "Error updating password: " . mysqli_error($conn);
            }
        } else {
            $error_message = "New password and confirmation don't match!";
        }
    } else {
        $error_message = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Admin Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Change Admin Password</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Change Password Form -->
        <form method="POST" action="change_password.php">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>

        <!-- Back to Dashboard -->
        <br><br>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
