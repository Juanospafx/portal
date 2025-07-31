<?php
require_once 'database.php'; // Portal DB

// Default XAMPP credentials
define('XAMPP_DB_USER', 'root');
define('XAMPP_DB_PASS', '');

// Function to connect to a database
function connect_to_db($db_name) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=" . $db_name, XAMPP_DB_USER, XAMPP_DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e){
        die("ERROR: Could not connect to $db_name. " . $e->getMessage());
    }
}

// Migrate users from clockin_app
$clockin_pdo = connect_to_db('brightro_qrapp_inv');
$stmt = $clockin_pdo->query('SELECT username, password FROM users');
$clockin_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clockin_users as $user) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user['username']]);
    if ($stmt->fetch()) {
        echo "User {$user['username']} already exists in portal_db. Skipping.\n";
        continue;
    }

    $email = $user['username'] . '@example.com';
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, app_access) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user['username'], $user['password'], $email, 'clockin_app'])) {
        echo "User {$user['username']} from clockin_app migrated successfully.\n";
    } else {
        echo "Error: Could not migrate user {$user['username']}.\n";
    }
}

// Migrate users from katalog-master
$katalog_pdo = connect_to_db('brightro_katalog');
$stmt = $katalog_pdo->query('SELECT username, email FROM user');
$katalog_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$temp_password = password_hash('password123', PASSWORD_BCRYPT);

foreach ($katalog_users as $user) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user['username']]);
    if ($stmt->fetch()) {
        echo "User {$user['username']} already exists in portal_db. Skipping.\n";
        continue;
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, app_access) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user['username'], $temp_password, $user['email'], 'katalog-master'])) {
        echo "User {$user['username']} from katalog-master migrated successfully with a temporary password.\n";
    } else {
        echo "Error: Could not migrate user {$user['username']}.\n";
    }
}

// Migrate users from Sistema de Inventario
$inventario_pdo = connect_to_db('brightro_brightronix_inv');
$stmt = $inventario_pdo->query('SELECT username, name FROM users'); // Assuming 'name' can be used for email
$inventario_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($inventario_users as $user) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user['username']]);
    if ($stmt->fetch()) {
        echo "User {$user['username']} already exists in portal_db. Skipping.\n";
        continue;
    }
    $email = $user['name'] . '@example.com';
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, app_access) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user['username'], $temp_password, $email, 'Sistema de Inventario'])) {
        echo "User {$user['username']} from Sistema de Inventario migrated successfully with a temporary password.\n";
    } else {
        echo "Error: Could not migrate user {$user['username']}.\n";
    }
}

echo "Migration complete.\n";

?>