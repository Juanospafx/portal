<?php
// logout.php
session_start();
unset($_SESSION['jwt']); // Remove JWT from session
session_destroy();
header('Location: login.php');
exit;
?>