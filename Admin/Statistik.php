<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$userCount = $userResult->fetch_assoc()['total_users'] ?? 0;

$menuQuery = "SELECT COUNT(*) AS total_menu FROM menu";
$menuResult = $conn->query($menuQuery);
$menuCount = $menuResult->fetch_assoc()['total_menu'] ?? 0;

$orderQuery = "SELECT COUNT(*) AS total_order FROM orders";
$orderResult = $conn->query($orderQuery);
$orderCount = $orderResult->fetch_assoc()['total_order'] ?? 0;

$hubungiQuery = "SELECT COUNT(*) AS total_hubungi FROM hubungi";
$hubungiResult = $conn->query($hubungiQuery);
$hubungiCount = $hubungiResult->fetch_assoc()['total_hubungi'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            width: 180px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background: white;
        }
        .card h1 {
            font-size: 24px;
            margin: 10px 0;
            color: #333;
        }
        .card p {
            font-size: 14px;
            color: #666;
        }
        .card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .yellow { color: #f1c40f; }
        .blue { color: #3498db; }
        .green { color: #2ecc71; }
        .pink { color: #e74c3c; }
    </style>
</head>
<body>

    <div class="dashboard-cards">
        <div class="card">
            <i class="fas fa-user yellow"></i>
            <h1><?php echo $userCount; ?></h1>
            <p>Total Users</p>
        </div>
        <div class="card">
            <i class="fas fa-mug-hot blue"></i>
            <h1><?php echo $menuCount; ?></h1>
            <p>Total Menu</p>
        </div>
        <div class="card">
            <i class="fas fa-shopping-cart green"></i>
            <h1><?php echo $orderCount; ?></h1>
            <p>Pesanan Masuk</p>
        </div>
        <div class="card">
            <i class="fas fa-comment pink"></i>
            <h1><?php echo $hubungiCount; ?></h1>
            <p>Menghubungi</p>
        </div>
    </div>

</body>
</html>