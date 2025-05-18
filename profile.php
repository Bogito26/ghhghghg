<?php
session_start();
require 'db.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    if ($pass) {
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
        $stmt->execute([$name, $email, $pass, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->execute([$name, $email, $user_id]);
    }
    header("Location: profile.php?success=1");
    exit();
}
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Profile</h2>
    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success">Profile updated!</div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?=htmlspecialchars($user['name'])?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($user['email'])?>" required>
        </div>
        <div class="mb-3">
            <label>New Password (leave blank to keep current password)</label>
            <input type="password" name="password" class="form-control" placeholder="New Password">
        </div>
        <button type="submit" class="btn btn-success">Update Profile</button>
    </form>
</div>
</body>
</html>