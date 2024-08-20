<?php
// db_connection.php

$host = 'localhost';  // ชื่อโฮสต์ของคุณ
$db   = 'booth_booking';  // ชื่อฐานข้อมูลของคุณ
$user = 'root';  // ชื่อผู้ใช้ MySQL
$pass = '';  // รหัสผ่าน MySQL
$charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
