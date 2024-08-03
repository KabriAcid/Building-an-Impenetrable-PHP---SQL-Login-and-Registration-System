<?php
// I wrote the OTP verification logic
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp_code = $_POST['otp_code'];
    if (verify_otp($_SESSION['username'], $otp_code)) {
        $_SESSION['authenticated'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid OTP.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <!-- I designed the HTML form -->
</head>
<body>
    <form action="otp_verification.php" method="post">
        <label for="otp_code">OTP Code:</label>
        <input type="text" id="otp_code" name="otp_code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
