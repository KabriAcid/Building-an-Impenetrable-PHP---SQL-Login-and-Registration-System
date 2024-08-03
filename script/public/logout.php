<?php
// I wrote the logout logic
session_start();
session_destroy();
header("Location: index.php");
exit;
?>
