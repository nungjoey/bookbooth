<?php
// check_payment.php
session_start();
require 'db_connection.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_POST['booking_id'];
$payment_proof = $_FILES['payment_proof'];

// ตรวจสอบวันที่จัดงาน
$query = "SELECT event_date FROM bookings WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$booking_id]);
$event_date = $stmt->fetchColumn();

$today = new DateTime();
$event_date = new DateTime($event_date);
$interval = $today->diff($event_date);

if ($interval->days < 5) {
    echo "ชำระเงินไม่ได้";
    // ยกเลิกการจองและตั้งสถานะบูธเป็น "ว่าง"
    $query = "UPDATE bookings SET status = 'ยกเลิกการจอง' WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$booking_id]);

    $query = "UPDATE booths SET status = 'ว่าง' WHERE zone = (SELECT zone FROM bookings WHERE id = ?) AND booth = (SELECT booth FROM bookings WHERE id = ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$booking_id, $booking_id]);
} else {
    // อัพโหลดไฟล์
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($payment_proof["name"]);
    move_uploaded_file($payment_proof["tmp_name"], $target_file);

    // อัพเดตสถานะการชำระเงิน
    $query = "UPDATE bookings SET payment_proof = ?, status = 'ชำระเงิน' WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$target_file, $booking_id]);

    echo "ชำระเงินได้";
}
?>
