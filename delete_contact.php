<?php
session_start();
require '../db.php';
$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT user_id FROM contacts WHERE id=?");
$stmt->execute([$id]);
$contact = $stmt->fetch();
if ($contact && ($contact['user_id']==$user_id || $_SESSION['role']!='user')) {
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id=?");
    $stmt->execute([$id]);
}
header("Location: index.php");
exit();
?>