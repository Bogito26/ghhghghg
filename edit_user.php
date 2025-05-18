<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) {
        header("Location: manage_users.php");
        exit();
    }
} else {
    header("Location: manage_users.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = strtolower(trim($_POST["email"]));
    $role = $_POST["role"];
    if ($name && $email && $role) {
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
        $stmt->execute([$name, $email, $role, $id]);
        header("Location: manage_users.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>
    <form method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control" name="name" required maxlength="100" value="<?=htmlspecialchars($user['name'])?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required maxlength="100" value="<?=htmlspecialchars($user['email'])?>">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select class="form-control" name="role" required>
                <option value="user" <?=$user['role']=='user'?'selected':''?>>User</option>
                <option value="teacher" <?=$user['role']=='teacher'?'selected':''?>>Teacher</option>
                <option value="admin" <?=$user['role']=='admin'?'selected':''?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update User</button>
        <a href="manage_users.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>