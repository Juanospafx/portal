<?php
// auth.php
require_once 'database.php';
require_once 'includes/jwt_config.php';
require_once 'libs/php-jwt/src/JWT.php';
use Firebase\JWT\JWT;


function register($username, $password, $email) {
    global $pdo;

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        return "Username or email already exists";
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password_hash, $email])) {
        return true;
    } else {
        return "Error: Could not register user";
    }
}

function login($username, $password) {
    global $pdo;

    // Find the user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Generate JWT
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // jwt valid for 1 hour
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => array(
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'app_access' => $user['app_access']
            )
        );
        $jwt = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
        return ['user' => $user, 'jwt' => $jwt];
    } else {
        return false;
    }
}
?>