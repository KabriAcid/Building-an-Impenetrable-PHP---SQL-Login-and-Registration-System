<?php
// I wrote the login logic
session_start();
require '../includes/db.php';
require '../includes/functions.php';
require '../includes/session.php';

// I handled the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // I validated user input
    if (validate_user($username, $password)) {
        if (is_account_locked($username)) {
            // I blocked the user after failed attempts
            $admin_email = get_admin_email();
            notify_admin($admin_email, $username);
            echo "Your account is locked. Please contact the administrator.";
        } else {
            if (authenticate_user($username, $password)) {
                // I redirected user based on role
                $user_role = get_user_role($username);
                if ($user_role === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit;
            } else {
                record_failed_attempt($username);
                echo "Invalid credentials.";
            }
        }
    } else {
        echo "Invalid input.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- I designed the HTML form -->
</head>
<body>
    <form action="index.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
