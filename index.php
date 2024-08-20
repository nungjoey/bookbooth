<?php
session_start();
// ตรวจสอบการล็อกอิน
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

// ดึงข้อมูลผู้ใช้
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT name, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// ดึงข้อมูลการจองบูธ
$sql_reservations = "SELECT zone_id, booth_number FROM reservations WHERE user_id = ?";
$stmt_reservations = $conn->prepare($sql_reservations);
$stmt_reservations->bind_param("i", $user_id);
$stmt_reservations->execute();
$result_reservations = $stmt_reservations->get_result();
$booths_booked = $result_reservations->num_rows;

$reservation_details = [];
$booked_booths = [];
while ($row = $result_reservations->fetch_assoc()) {
    // แปลง zone_id จากตัวเลขเป็นตัวอักษร
    $zone_id = $row['zone_id'];
    $zone_name = '';
    if ($zone_id == '0') $zone_name = 'A';
    if ($zone_id == '1') $zone_name = 'B';
    if ($zone_id == '2') $zone_name = 'C';
    if ($zone_id == '3') $zone_name = 'D';

    $reservation_details[] = $zone_name . " - บูธ " . $row['booth_number'];
    $booked_booths[] = $zone_name . $row['booth_number']; // เก็บบูธที่จองไปแล้ว
}

$sql_reservations = "SELECT zone_id, booth_number, product FROM reservations WHERE user_id = ?";
$stmt_reservations = $conn->prepare($sql_reservations);
$stmt_reservations->bind_param("i", $_SESSION['user_id']);
$stmt_reservations->execute();
$reservations_result = $stmt_reservations->get_result();


$stmt_user->close();
$stmt_reservations->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>หน้าแรก</title>
    <style>
        body {
            background-color: black;
            color: gold;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            margin: 0;
        }
        .user-info {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
        }
        .zone-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 100px;
            margin-bottom: 150px;
            width: 100%;
        }
        .zone {
            background-color: #333;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            width: 200px;
        }
        .zone h3 {
            text-align: center;
        }
        .booth {
            text-align: center;
            margin: 10px 0;
        }
        .booth button {
            background-color: gold;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: black;
        }
        .booth button[disabled] {
            background-color: gray;
            color: white;
        }
        .logout-delete {
            display: flex;
            justify-content: flex-end;
            margin: 20px;
            width: 100%;
        }
        .logout-delete form {
            margin-left: 10px;
        }
        .logout-delete button {
            background-color: gold;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: black;
        }
        .delete-button {
            background-color: red;
            color: white;
        }
        .reservations {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="logout-delete">
        <!-- ปุ่มไปหน้าลบการจอง -->
        <form action="delete_reservation.php" method="get">
            <button type="submit" class="delete-button">ลบการจอง</button>
        </form>
        
        <!-- ปุ่มออกจากระบบ -->
        <form action="logout.php" method="post">
            <button type="submit">ออกจากระบบ</button>
        </form>
    </div>

    <!-- ข้อมูลผู้ใช้ -->
    <div class="user-info">
        <h3>ข้อมูลผู้ใช้</h3>
        <p>ชื่อ: <?php echo htmlspecialchars($user['name']); ?></p>
        <p>อีเมล: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>จำนวนบูธที่จอง: <?php echo $booths_booked; ?></p>
    </div>

    <div class="zone-container">
    <?php
    // ดึงข้อมูลโซนและบูธจากฐานข้อมูล
    $zones = ['A', 'B', 'C', 'D'];
    foreach ($zones as $zone) {
        echo '<div class="zone">';
        echo '<h3>โซน ' . $zone . '</h3>';
        for ($i = 1; $i <= 4; $i++) {
            $booth_key = $zone . $i;
            $is_booked = in_array($booth_key, $booked_booths);
            echo '<div class="booth">';
            echo '<p>บูธ ' . $i . '</p>';
            echo '<button ' . ($is_booked ? 'disabled' : 'onclick="bookBooth(\'' . $zone . '\', ' . $i . ')"') . '>';
            echo $is_booked ? 'จองแล้ว' : 'จอง';
            echo '</button>';
            echo '</div>';
        }
        echo '</div>';
    }
    ?>
    </div>

    <!-- ข้อมูลการจองบูธ -->
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

    <script>
    function bookBooth(zone, booth) {
        window.location.href = 'booking.php?zone_id=' + encodeURIComponent(zone) + '&booth_number=' + encodeURIComponent(booth);
    }
    </script>
</body>
</html>
