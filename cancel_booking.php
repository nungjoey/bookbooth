<?php
// cancel_booking.php
session_start();
require 'db_connection.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$booking_id = $_POST['booking_id'];

// ยกเลิกการจอง
$query = "UPDATE bookings SET status = 'ยกเลิกการจอง' WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$booking_id]);

// เปลี่ยนสถานะของบูธ
$query = "UPDATE booths SET status = 'ว่าง' WHERE zone = (SELECT zone FROM bookings WHERE id = ?) AND booth = (SELECT booth FROM bookings WHERE id = ?)";
$stmt = $pdo->prepare($query);
$stmt->execute([$booking_id, $booking_id]);

echo "การจองถูกยกเลิก";
?>
