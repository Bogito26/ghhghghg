<?php
session_start();
require '../db.php';
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=contacts.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['Name', 'Email', 'Phone']);

if ($role == 'admin' || $role == 'teacher') {
    $stmt = $pdo->query("SELECT name, email, phone FROM contacts");
} else {
    $stmt = $pdo->prepare("SELECT name, email, phone FROM contacts WHERE user_id=?");
    $stmt->execute([$user_id]);
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
exit();
?>