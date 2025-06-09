<?php
session_start();
include('db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is already logged in
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

// Process the login if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging - Check if the form data is correct
    var_dump($_POST);  // This will display the form data

    if (empty($username) || empty($password)) {
        $error_message = "Username and Password cannot be empty!";
    } else {
        // Prepare SQL query to find the user by username
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        // Debugging - Check if query returns the expected results
        if (mysqli_num_rows($result) == 1) {
            echo "Query successful";  // This message will appear if the query is successful
            $user = mysqli_fetch_assoc($result);
            var_dump($user);  // Display user details

            // Check the password (no password_verify for plain text)
            if ($password === $user['password']) {
                $_SESSION['user'] = $username;  // Store user session
                header("Location: dashboard.php");
                exit();  // Redirect to the dashboard
            } else {
                $error_message = "Invalid username or password!";
            }
        } else {
            $error_message = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventory Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .login-container {
            width: 30%;
            margin-top: 100px;
        }
        .login-btn {
            width: 100%;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center">Login</h2>
            
            <!-- Show error message if any -->
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary login-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
