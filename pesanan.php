<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aroma_isuk';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "
    SELECT id AS order_id, created_at, status, total_harga
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$pesanan = [];
while ($row = $result->fetch_assoc()) {
    $pesanan[] = [
        'order_id' => $row['order_id'],
        'created_at' => $row['created_at'],
        'status' => $row['status'],
        'total_harga' => $row['total_harga'],
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('image/home-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            justify-content: center;
            padding: 10px;
        }

        .order-card {
            background: #d3b8a6;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s;
        }

        .order-card:hover {
            transform: scale(1.05);
        }

        .order-card p {
            margin: 8px 0;
            font-size: 14px;
            color: #4e3629;
        }

        .status {
            font-weight: bold;
            color: #4e3629;
        }

        .back-to-index {
            position: absolute;
            top: 10px;
            left: 10px;
            text-decoration: none;
            font-size: 14px;
            color: #8e6e53;
            font-weight: bold;
        }

        .print-button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #8e6e53;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-button:hover {
            background-color: #6d5140;
        }
    </style>
</head>
<body>
<section class="book" id="book">
<h1 class="heading">Pesanan <span>Saya</span></h1>
    <a href="index.php" class="back-to-index">&larr; Kembali</a>

<div class="container">
    <?php if (empty($pesanan)): ?>
        <p>Anda belum memiliki pesanan.</p>
    <?php else: ?>
        <div class="grid-container">
            <?php foreach ($pesanan as $order): ?>
                <div class="order-card">
                    <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></p>
                    <p class="status"><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                    <p><strong>Total Harga:</strong> Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>
                    <a href="cetak_pdf.php?order_id=<?= $order['order_id'] ?>" target="_blank">
                    <button class="print-button" onclick="window.print()">Cetak</button>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>