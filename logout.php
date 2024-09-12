<?php
session_start();

// Clear session data
session_unset();
session_destroy();

// Clear cookies
setcookie('username', '', time() - 3600, "/");
setcookie('password', '', time() - 3600, "/");

// Redirect to login page
header('Location: login.php');
exit;
?>
