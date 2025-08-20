<?php
session_start();

if (!isset($_SESSION['order_data'])) {
    header("Location: informasi.php");
    exit;
}

$order_data = $_SESSION['order_data'];

$payment_methods = [
    ['BRI', 'image/bri_logo.png', '1234567890', 'Moh. Naufal Alfarisi'],
    ['BCA', 'image/bca_logo.png', '0987654321', 'Moh. Naufal Alfarisi'],
    ['JAGO', 'image/JAGO.png', '1122334455', 'Moh. Naufal Alfarisi'],
    ['QRIS', 'image/QRIS.png', 'image/QRIS1.png', 'Moh. Naufal Alfarisi']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = 'Pending';

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'aroma_isuk');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Perbaikan query INSERT INTO
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, nama, alamat, pengiriman, kurir, ongkir, total_harga, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Pastikan jumlah kolom dan nilai sesuai
    $stmt->bind_param(
        "isssisss", 
        $order_data['user_id'], 
        $order_data['nama'], 
        $order_data['alamat'], 
        $order_data['pengiriman'], 
        $order_data['kurir'], 
        $order_data['ongkir'], 
        $order_data['total_harga'], 
        $status
    );

    // Eksekusi query
    if ($stmt->execute()) {
        // Hapus session data
        unset($_SESSION['cart']);
        unset($_SESSION['order_data']);

        // Redirect ke halaman pesanan
        header("Location: pesanan.php");
        exit;
    } else {
        die("Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .grid-item {
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .grid-item:hover {
            transform: scale(1.05);
        }
        .grid-item img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .account-info {
            display: none;
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }
        .btn-copy {
            background: #8e6e53;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            padding: 5px 10px;
            font-size: 12px;
            margin-top: 5px;
        }
        .payment-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .payment-buttons button {
            background: #8e6e53;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
            margin-left: 10px;
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
    </style>
</head>
<body background="image/home-bg.jpg">
<section class="book" id="book">
<h1 class="heading">Metode <span>Pembayaran</span></h1>
<div>
    <a href="informasi.php" class="back-to-index">← Kembali</a>
        <div class="grid-container">
            <?php foreach ($payment_methods as $method): ?>
                <div class="grid-item" onclick="toggleAccountInfo('<?= $method[0] ?>')">
                    <img src="<?= $method[1] ?>" alt="<?= $method[0] ?>">
                    <div class="account-info" id="account-info-<?= $method[0] ?>">
                        <?php if ($method[0] === 'QRIS'): ?>
                            <p>Scan QRIS untuk pembayaran:</p>
                            <img src="<?= $method[2] ?>" alt="QRIS" style="width: 120px; height: 120px;">
                        <?php else: ?>
                            <p>Nomor Rekening: <span id="account-number-<?= $method[0] ?>"><?= $method[2] ?></span></p>
                            <p>a.n. <?= $method[3] ?></p>
                            <button class="btn-copy" onclick="copyToClipboard('<?= $method[2] ?>')">Salin</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST" action="">
            <div class="payment-buttons">
                <button type="submit" name="metode_pembayaran" value="sudah_bayar">Sudah Bayar</button>
            </div>
        </form>
    </div>
</section>
<script>
    function toggleAccountInfo(bankName) {
        const accountInfo = document.getElementById('account-info-' + bankName);
        accountInfo.style.display = accountInfo.style.display === 'none' || accountInfo.style.display === '' ? 'block' : 'none';
    }

    function copyToClipboard(accountNumber) {
        navigator.clipboard.writeText(accountNumber).then(function() {
            alert('Nomor rekening disalin: ' + accountNumber);
        }, function(err) {
            console.error('Tidak dapat menyalin', err);
        });
    }
</script>
</body>
</html>