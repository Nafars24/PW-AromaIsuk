<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aroma_isuk';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_logged_in'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            color: #f5ebe0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #2e2625;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .icon-coffee {
            font-size: 48px;
            color: #d4a373;
            margin-bottom: 10px;
        }

        .container h2 {
            margin-bottom: 10px;
            font-size: 24px;
            color: #d4a373;
        }

        .container p {
            margin-bottom: 20px;
            font-size: 14px;
            color: #c8b6a6;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #f5ebe0;
            margin-bottom: 5px;
        }

        .form-group input {
            width: calc(100% - 20px); /* Sesuaikan ukuran input agar tidak terlalu besar */
            padding: 8px;
            border: 1px solid #6c4e4e;
            border-radius: 5px;
            background-color: #3b2f2f;
            color: #f5ebe0;
            font-size: 14px;
            outline: none;
        }

        .form-group input:focus {
            border-color: #d4a373;
            box-shadow: 0 0 5px rgba(212, 163, 115, 0.5);
        }

        .btn {
            background-color: #6f4e37;
            color: #f5ebe0;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .btn:hover {
            background-color: #8d6346;
        }

        .error {
            color: #ff6b6b;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body background="../image/home-bg.jpg">
    <div class="container">
        <div class="icon-coffee">☕</div>
        <h2>Login Admin</h2>
        <p>Silakan login untuk mengelola website Anda</p>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Username Admin" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password Admin" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>