<?php
session_start();
require 'db.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = strtolower(trim($_POST["email"]));
    $password = $_POST["password"];
    $role = $_POST["role"];
    $verification_code = rand(100000, 999999);

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        header("Location: register.php?error=Please fill in all fields");
        exit();
    }

    // Check for existing email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header("Location: register.php?error=Email already exists");
        exit();
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, verification_code, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->execute([$name, $email, $hashed, $role, $verification_code]);

    // Send verification code by email
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server here
        $mail->SMTPAuth   = true;
        $mail->Username   = 'agustinmceduard26@gmail.com'; // SMTP username
        $mail->Password   = 'rghj azsw hmhf afwx';   // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('agustinmceduard26@gmail.com', 'Your System');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body    = "Hello <b>$name</b>,<br>Your verification code is <b>$verification_code</b>.<br>Enter this code to activate your account.";

        $mail->send();
        header("Location: verify.php?email=$email");
        exit();
    } catch (Exception $e) {
        header("Location: register.php?error=Could not send verification email. Mailer Error: {$mail->ErrorInfo}");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>