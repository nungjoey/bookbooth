<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

require 'db_connection.php'; // สมมติว่าคุณมีไฟล์นี้สำหรับเชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $zone = $_POST['zone'];
    $name = $_POST['name'];
    $size = $_POST['size'];
    $status = $_POST['status'];
    $price = $_POST['price'];

    $query = "INSERT INTO booths (zone, name, size, status, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$zone, $name, $size, $status, $price]);

    echo "เพิ่มบูธสำเร็จ";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>เพิ่มบูธ</title>
    <style>
        body { background-color: black; color: gold; }
        .form-container { margin: 0 auto; width: 300px; padding: 30px; background: #222; border-radius: 10px; }
        .form-container input[type="text"], .form-container input[type="number"] { width: 100%; padding: 10px; margin: 5px 0; border: none; border-radius: 5px; }
        .form-container input[type="submit"] { background-color: gold; border: none; padding: 10px; width: 100%; border-radius: 5px; color: black; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>เพิ่มบูธ</h2>
        <form action="add_booth.php" method="post">
            <input type="text" name="zone" placeholder="โซน" required>
            <input type="text" name="name" placeholder="ชื่อบูธ" required>
            <input type="text" name="size" placeholder="ขนาด" required>
            <input type="text" name="status" placeholder="สถานะ" required>
            <input type="number" name="price" placeholder="ราคา" required>
            <input type="submit" value="เพิ่มบูธ">
        </form>
    </div>
</body>
</html>
