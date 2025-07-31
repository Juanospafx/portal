<?php
// database.php
define('DB_HOST', 'localhost');
define('DB_USER', 'brightro_portal_db');
define('DB_PASS', 'rootadmin01');
define('DB_NAME', 'brightro_portal_db');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>