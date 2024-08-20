<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// ตรวจสอบและรับค่า zone_id, booth_number, email
if (!isset($_POST['zone_id']) || !isset($_POST['booth_number']) || !isset($_POST['email']) || !isset($_FILES['payment_proof'])) {
    die('ข้อมูลไม่ครบถ้วน');
}

$zone_id = htmlspecialchars($_POST['zone_id']);
$booth_number = htmlspecialchars($_POST['booth_number']);
$email = htmlspecialchars($_POST['email']);
$product = htmlspecialchars($_POST['product']);

// ตรวจสอบว่า zone_id เป็นค่าที่ถูกต้อง
$valid_zones = ['A', 'B', 'C', 'D'];
if (!in_array($zone_id, $valid_zones)) {
    die('ค่า zone_id ไม่ถูกต้อง');
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

// ตรวจสอบจำนวนบูธที่จองโดยอีเมลเดียว
$sql_check = "SELECT COUNT(*) AS count FROM reservations WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();
$row = $result->fetch_assoc();

if ($row['count'] >= 4) {
    die('คุณได้จองบูธครบ 4 บูธแล้ว');
}

// จัดการไฟล์อัปโหลด
$upload_dir = 'uploads/';
$upload_file = $upload_dir . basename($_FILES['payment_proof']['name']);
if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $upload_file)) {
    // สร้างการจองบูธ
    $user_id = $_SESSION['user_id'];
    $sql_book = "INSERT INTO reservations (user_id, zone_id, booth_number, email, product, payment_proof) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_book = $conn->prepare($sql_book);
    $stmt_book->bind_param("isssss", $user_id, $zone_id, $booth_number, $email, $product, $upload_file);
    
    if ($stmt_book->execute()) {
        echo 'จองบูธสำเร็จ';
    } else {
        echo 'เกิดข้อผิดพลาดในการจองบูธ';
    }

    $stmt_book->close();
} else {
    echo 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์';
}

$stmt_check->close();
$conn->close();
?>
