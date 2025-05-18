<?php
require 'db.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_GET['email'] ?? '';
if (!$email) {
    header("Location: login.php?error=Missing email.");
    exit();
}

// Generate a new code
$verification_code = rand(100000, 999999);

// Update code in the database (only if not verified yet)
$stmt = $pdo->prepare("UPDATE users SET verification_code=?, is_verified=0 WHERE email=? AND is_verified=0");
$stmt->execute([$verification_code, $email]);

// Fetch the user's name (for email)
$stmt2 = $pdo->prepare("SELECT name FROM users WHERE email=?");
$stmt2->execute([$email]);
$user = $stmt2->fetch();

if ($user) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';           // Set your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'agustinmceduard26@gmail.com';      // SMTP username
        $mail->Password   = 'rghj azsw hmhf afwx';   // SMTP password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('agustinmceduard26@gmail.com', 'Your System');
        $mail->addAddress($email, $user['name']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your New Verification Code';
        $mail->Body    = "Hello <b>{$user['name']}</b>,<br>Your new verification code is <b>$verification_code</b>.<br>Enter this code to activate your account.";

        $mail->send();
        header("Location: verify.php?email=$email&resent=1");
        exit();
    } catch (Exception $e) {
        header("Location: verify.php?email=$email&error=Could not resend email. Mailer Error: {$mail->ErrorInfo}");
        exit();
    }
} else {
    header("Location: login.php?error=User not found or already verified.");
    exit();
}
?>