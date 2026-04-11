<?php
// Start session
session_start();

// Remove all session variables
$_SESSION = [];

// Destroy session completely
session_destroy();

// Redirect to home page
header("Location: index.php");
exit();
?>