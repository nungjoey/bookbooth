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

// รับข้อมูลการจองบูธของผู้ใช้
$sql = "SELECT id, zone_id, booth_number, product FROM reservations WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>บูธที่คุณจอง</title>
    <style>
        body {
            background-color: black;
            color: gold;
        }
        .reservation-container {
            width: 600px;
            margin: 0 auto;
            background: #222;
            padding: 20px;
            border-radius: 10px;
        }
        .reservation {
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid gold;
        }
        .reservation form {
            display: inline;
        }
        .reservation form input[type="submit"] {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="reservation-container">
        <h2>บูธที่คุณจอง</h2>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="reservation">
                <p>โซน: <?php echo htmlspecialchars($row['zone_id']); ?> บูธ: <?php echo htmlspecialchars($row['booth_number']); ?></p>
                <p>สินค้า: <?php echo htmlspecialchars($row['product']); ?></p>
                <form action="delete_reservation.php" method="post">
                    <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                    <input type="submit" value="ลบการจอง">
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
