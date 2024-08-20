<!-- delete_reservation.php -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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

// ดึงข้อมูลการจอง
$sql_reservations = "SELECT zone_id, booth_number, product FROM reservations WHERE user_id = ?";
$stmt_reservations = $conn->prepare($sql_reservations);
$stmt_reservations->bind_param("i", $_SESSION['user_id']);
$stmt_reservations->execute();
$reservations_result = $stmt_reservations->get_result();

$stmt_reservations->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ลบการจอง</title>
    <style>
        body {
            background-color: black;
            color: gold;
        }
        .form-container {
            margin: 0 auto;
            width: 300px;
            padding: 30px;
            background: #222;
            border-radius: 10px;
        }
        .form-container input[type="text"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            background-color: red;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            color: black;
        }
        .back-button {
            background-color: gold;
            color: black;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .reservations {
            margin-top: 20px;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            color: gold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>ลบการจอง</h2>
        <form action="process_delete.php" method="post">
            <label for="email">อีเมล:</label>
            <input type="email" name="email" id="email" required>

            <br>    </br><label for="zone_id">โซน:</label>
            <input type="text" name="zone_id" id="zone_id" required>

            <label for="booth_number">หมายเลขบูธ:</label>
            <input type="text" name="booth_number" id="booth_number" required>

            <input type="submit" value="ยืนยันการลบ">
        </form>

        <!-- ปุ่มกลับไปหน้าแรก -->
        <form action="index.php" method="get">
            <button type="submit" class="back-button">กลับไปหน้าแรก</button>
        </form>
    </div>
    <div class="reservations">
        <h2>รายละเอียดการจอง</h2>
        <?php
        if ($reservations_result->num_rows > 0) {
            while ($row = $reservations_result->fetch_assoc()) {
                echo '<p>โซน: ' . htmlspecialchars($row['zone_id']) . ', บูธ: ' . htmlspecialchars($row['booth_number']) . ', สิ่งที่ขาย: ' . htmlspecialchars($row['product']) . '</p>';
            }
        } else {
            echo '<p>ไม่มีการจองบูธ</p>';
        }
        ?>
    </div>
</body>
</html>
