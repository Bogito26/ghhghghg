<?php
session_start();
require '../db.php';
$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM contacts WHERE id=?");
$stmt->execute([$id]);
$contact = $stmt->fetch();
if (!$contact || ($contact['user_id']!=$user_id && $_SESSION['role']=='user')) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $stmt = $pdo->prepare("UPDATE contacts SET name=?, email=?, phone=? WHERE id=?");
    $stmt->execute([$name, $email, $phone, $id]);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Contact</h2>
    <form method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?=htmlspecialchars($contact['name'])?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($contact['email'])?>">
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?=htmlspecialchars($contact['phone'])?>">
        </div>
        <button class="btn btn-success" type="submit">Update</button>
        <a class="btn btn-secondary" href="index.php">Back</a>
    </form>
</div>
</body>
</html>