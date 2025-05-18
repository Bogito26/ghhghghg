<?php
session_start();
require 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    if (!$user['is_verified']) {
        header("Location: verify.php?email=$email");
        exit();
    }
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    session_regenerate_id(true);

    if ($user['role'] === 'admin') header("Location: admin/index.php");
    elseif ($user['role'] === 'teacher') header("Location: teacher/index.php");
    else header("Location: user/index.php");
    exit();
} else {
    header("Location: login.php?error=Invalid email or password");
    exit();
}
?>