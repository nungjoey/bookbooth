<?php
// update_profile.php
session_start();
require 'db_connection.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];

// ตรวจสอบอีเมลซ้ำ
$query = "SELECT * FROM users WHERE email = ? AND id != ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$email, $user_id]);
if ($stmt->fetch()) {
    echo "อีเมลนี้ถูกใช้แล้ว";
    exit();
}

// อัพเดตข้อมูลผู้ใช้
$query = "UPDATE users SET name = ?, phone = ?, email = ?, password = ? WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$name, $phone, $email, password_hash($password, PASSWORD_BCRYPT), $user_id]);

echo "ข้อมูลถูกอัพเดต";
?>
