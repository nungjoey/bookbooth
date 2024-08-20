<!-- admin.php -->
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// ดึงข้อมูลจากฐานข้อมูล
?>

<!DOCTYPE html>
<html>
<head>
    <title>หน้า Admin</title>
    <style>
        body {
            background-color: black;
            color: gold;
        }
        .container {
            margin: 0 auto;
            width: 80%;
        }
        .admin-actions {
            text-align: center;
            margin: 20px;
        }
        .admin-actions button {
            background-color: gold;
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: black;
            margin: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid gold;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>หน้า Admin</h2>
        <div class="admin-actions">
            <button onclick="location.href='add_booth.php'">เพิ่มบูธ</button>
            <button onclick="location.href='manage_users.php'">จัดการสมาชิก</button>
        </div>
        <h3>ข้อมูลบูธ</h3>
        <table>
            <tr>
                <th>โซน</th>
                <th>ชื่อบูธ</th>
                <th>ขนาด</th>
                <th>สถานะ</th>
                <th>ราคา</th>
                <th>รูปภาพ</th>
                <th>การกระทำ</th>
            </tr>
            <?php
            // ดึงข้อมูลบูธจากฐานข้อมูลและแสดงในตาราง
            ?>
        </table>
    </div>
</body>
</html>
