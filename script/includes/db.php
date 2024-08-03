<?php
// I connected to the database
$host = 'localhost';
$db = 'secure_login_system';
$user = 'root';
$pass = 'M48frzjS8M6GJ-B8';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
