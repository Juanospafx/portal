<?php
// Default XAMPP credentials
define('XAMPP_DB_USER', 'brightro_portal');
define('XAMPP_DB_PASS', 'rootadmin01');

function setup_database($db_name, $sql_file) {
    try {
        $pdo = new PDO("mysql:host=localhost", XAMPP_DB_USER, XAMPP_DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name`");
        $pdo->exec("USE `$db_name`");
        $sql = file_get_contents($sql_file);
        $pdo->exec($sql);
        echo "Database `$db_name` created and populated successfully.\n";
    } catch(PDOException $e){
        die("ERROR: Could not setup database $db_name. " . $e->getMessage());
    }
}

setup_database('portal_db', 'C:/xampp/htdocs/Brightronix/portal/portal_db.sql');
setup_database('brightro_qrapp_inv', 'C:/xampp/htdocs/Brightronix/clockin_app/public_html/brightro_qrapp_inv.sql');
setup_database('brightro_katalog', 'C:/xampp/htdocs/Brightronix/katalog-master/katalog-master/brightro_katalog.sql');
setup_database('brightro_brightronix_inv', 'C:/xampp/htdocs/Brightronix/Sistema de Inventario/Sistema de Inventario/brightro_brightronix_inv (1).sql');

echo "All databases set up successfully.\n";

?>