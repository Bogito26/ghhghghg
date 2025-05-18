<?php
$host = "ssql305.infinityfree.com"; // Your InfinityFree MySQL host
$db = "if0_38995923_multi_user_system";       // Your InfinityFree database name
$user = "if0_38995923";            // Your InfinityFree username
$pass = "MIpffijos81q";         // Your InfinityFree db password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>