<!-- process_delete.php -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบและรับค่า
if (!isset($_POST['email']) || !isset($_POST['zone_id']) || !isset($_POST['booth_number'])) {
    die('ข้อมูลไม่ครบถ้วน');
}

$email = htmlspecialchars($_POST['email']);
$zone_id = htmlspecialchars($_POST['zone_id']);
$booth_number = htmlspecialchars($_POST['booth_number']);

// ตรวจสอบค่าที่ป้อนเข้ามา
$valid_zones = ['A', 'B', 'C', 'D'];
$valid_booths = ['1', '2', '3', '4'];

if (!in_array($zone_id, $valid_zones) || !in_array($booth_number, $valid_booths)) {
    echo "ข้อมูลโซนหรือหมายเลขบูธไม่ถูกต้อง";
    exit();
}

// การตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root"; // ชื่อผู้ใช้ MySQL
$password = ""; // รหัสผ่าน MySQL
$dbname = "booth_booking";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
$sql_check = "SELECT * FROM reservations WHERE email = ? AND zone_id = ? AND booth_number = ? AND user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ssii", $email, $zone_id, $booth_number, $_SESSION['user_id']);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // ลบการจองบูธที่ระบุ
    $sql = "DELETE FROM reservations WHERE email = ? AND zone_id = ? AND booth_number = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $email, $zone_id, $booth_number, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "ลบสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการลบ: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ช่องที่เลือกไม่มีข้อมูลให้ลบไอสัส";
}

// ปิดการเชื่อมต่อ
$stmt_check->close();
$conn->close();
?>
