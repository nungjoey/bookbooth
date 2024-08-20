<!-- register.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form action="register_process.php" method="post">
            <input type="text" name="name" placeholder="ชื่อ - นามสกุล" required>
            <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
