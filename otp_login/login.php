<?php
session_start();
include 'db.php';
include 'send_mail.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $otp = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            $conn->query("UPDATE users 
                          SET otp='$otp', otp_expiry='$expiry' 
                          WHERE email='$email'");

            sendMail($email, "Your OTP", "<h2>Your OTP is $otp</h2>");

            $_SESSION['email'] = $email;

            header("Location: email_status.php");
            exit;
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Email not registered";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="insta_style.css">
</head>
<body>

<div class="container">
    <div class="logo">Instagram</div>
    <h2>Login</h2>
    <p>Enter your details to continue</p>

    <form method="post">
        <input type="email" name="email" placeholder="Email address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button>Log In</button>
    </form>

    <div class="link">
        Don’t have an account? <a href="register.php">Sign up</a>
    </div>
</div>

</body>
</html>
