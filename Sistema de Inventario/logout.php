<?php
session_start();
unset($_SESSION['user_data']);
unset($_SESSION['jwt']);
session_destroy();
header('Location: ../../portal/logout.php');
exit;
?>