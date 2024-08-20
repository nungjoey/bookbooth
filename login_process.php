<?php
session_start();
require 'db_connection.php'; // ตรวจสอบว่ามีไฟล์นี้สำหรับเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มผ่าน POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // ตรวจสอบว่าอีเมลและรหัสผ่านไม่ว่างเปล่า
    if (!empty($email) && !empty($password)) {
        // ตรวจสอบว่ามีผู้ใช้ที่มีอีเมลนี้ในฐานข้อมูลหรือไม่
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // เก็บข้อมูลผู้ใช้ในเซสชัน
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit; // หยุดการทำงานของสคริปต์เพื่อป้องกันการส่งข้อมูลเพิ่มเติม
        } else {
            // ข้อมูลเข้าสู่ระบบไม่ถูกต้อง
            echo "ข้อมูลเข้าสู่ระบบไม่ถูกต้อง";
        }
    } else {
        echo "กรุณากรอกอีเมลและรหัสผ่าน";
    }
}
?>
