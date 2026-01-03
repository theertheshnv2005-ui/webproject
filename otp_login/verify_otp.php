<?php
session_start();
include 'db.php';
include 'send_mail.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if ($otp == $user['otp'] && strtotime($user['otp_expiry']) > time()) {

        sendMail($email, "Login Successful", "<h2>You have logged in successfully</h2>");

        unset($_SESSION['email']); // clear OTP session

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid or expired OTP";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="insta_style.css">
</head>
<body>

<div class="container">
    <div class="logo">Instagram</div>
    <h2>Verify OTP</h2>
    <p>Enter the 6-digit code sent to your email</p>

    <form method="post">
        <input name="otp" placeholder="Enter OTP" required>
        <button>Verify</button>
    </form>
</div>

</body>
</html>

