<?php
$host = '127.0.0.1'; // forces localhost through XAMPP
$port = '3307';      // default MySQL port in XAMPP
$db   = 'mytestdb';  // ✅ database name you created in phpMyAdmin
$user = 'root';      // default XAMPP user
$pass = '';          // default XAMPP password is blank

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to phpMyAdmin's MySQL database: <strong>$db</strong>";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
