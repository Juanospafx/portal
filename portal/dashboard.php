<?php
// dashboard.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';
require_once 'database.php';

// Get user's app access rights
$stmt = $pdo->prepare("SELECT app_access FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user_app_access = $stmt->fetchColumn();
$user_apps = explode(',', $user_app_access);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>
        <p>Welcome! You are logged in.</p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <hr>
        <h3>Your Applications</h3>
        <div class="list-group">
            <?php foreach ($apps as $app): ?>
                <?php if (in_array($app['name'], array_map('trim', $user_apps))): ?>
                    <a href="<?php echo $app['url']; ?>?jwt=<?php echo $_SESSION['jwt']; ?>" class="list-group-item list-group-item-action">
                        <i class="<?php echo $app['icon_class']; ?>"></i>
                        <?php echo $app['name']; ?>
                        <small class="text-muted"><?php echo $app['desc']; ?></small>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>