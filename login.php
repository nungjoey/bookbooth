<!-- login.php -->
<?php
// เริ่ม session
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            background-color: gold;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            color: black;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .register-link a {
            color: gold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form action="login_process.php" method="post">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <div class="register-link">
            <p>ไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
        </div>
    </div>
</body>
</html>
