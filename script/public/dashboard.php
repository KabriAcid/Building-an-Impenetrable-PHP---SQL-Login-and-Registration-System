<?php
// I wrote the dashboard logic
session_start();
require '../includes/session.php';

// I checked user role and redirected accordingly
if (!is_authenticated()) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$role = get_user_role($username);

if ($role === 'admin') {
    echo "Welcome to Admin Dashboard, $username.";
} else {
    echo "Welcome to User Dashboard, $username.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <a href="logout.php">Logout</a>
</body>
</html>
