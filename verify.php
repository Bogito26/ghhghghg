<?php
require 'db.php';
$error = '';
$email = $_GET["email"] ?? ($_POST['email'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"];
    $stmt = $pdo->prepare("SELECT verification_code FROM users WHERE email=?");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if ($row && $row['verification_code'] == $code) {
        $pdo->prepare("UPDATE users SET is_verified=1, verification_code=NULL WHERE email=?")->execute([$email]);
        header("Location: login.php?success=verified");
        exit();
    } else {
        $error = "Invalid code!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Email Verification</h2>
    <?php if($error): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif; ?>
    <?php if(isset($_GET['resent'])): ?>
        <div class="alert alert-success">A new code was sent to your email!</div>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="email" value="<?=htmlspecialchars($email)?>">
        <div class="mb-3">
            <label>Enter the 6-digit code sent to your email:</label>
            <input type="text" name="code" class="form-control" maxlength="6" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
        <a href="resend_code.php?email=<?=urlencode($email)?>" class="btn btn-link">Resend Code</a>
    </form>
</div>
</body>
</html>