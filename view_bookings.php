<?php
// view_bookings.php
session_start();
require 'db_connection.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT b.zone, b.booth, b.product, b.payment_proof, b.status, b.booking_date FROM bookings b WHERE b.user_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>รายการการจอง</title>
    <style>
        body { background-color: black; color: gold; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid gold; }
        th, td { padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <h2>รายการการจองของคุณ</h2>
    <table>
        <tr>
            <th>โซน</th>
            <th>บูธ</th>
            <th>สิ่งที่ขาย</th>
            <th>สลิปการชำระเงิน</th>
            <th>สถานะการจอง</th>
            <th>วันที่จอง</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?php echo htmlspecialchars($booking['zone']); ?></td>
            <td><?php echo htmlspecialchars($booking['booth']); ?></td>
            <td><?php echo htmlspecialchars($booking['product']); ?></td>
            <td><a href="<?php echo htmlspecialchars($booking['payment_proof']); ?>">ดูสลิป</a></td>
            <td><?php echo htmlspecialchars($booking['status']); ?></td>
            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
