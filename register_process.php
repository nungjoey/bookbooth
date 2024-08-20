<?php
session_start();
require 'db_connection.php'; // สมมติว่าคุณมีไฟล์นี้สำหรับเชื่อมต่อฐานข้อมูล

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// ตรวจสอบว่ามีอีเมลนี้อยู่ในฐานข้อมูลหรือไม่
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "อีเมลนี้มีการลงทะเบียนไปแล้ว";
} else {
    $query = "INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name, $phone, $email, $password]);
    echo "ลงทะเบียนสำเร็จ";
}
?>
