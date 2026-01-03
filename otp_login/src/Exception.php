<?php
$conn = new mysqli("localhost", "root", "", "otp_login");

if ($conn->connect_error) {
    die("Database connection failed");
}
?>

