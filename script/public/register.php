<?php
// I wrote the registration logic
require '../includes/db.php';
require '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Enhanced email validation to ensure it contains a period in the domain part
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } elseif (!strpos(substr($email, strpos($email, '@')), '.')) {
        echo "Email must contain a period (.) in the domain part.";
    } else {
        // I validated user input
        if (register_user($username, $email, $password)) {
            echo "Registration successful.";
        } else {
            echo "Registration failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- I designed the HTML form -->
</head>
<body>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
