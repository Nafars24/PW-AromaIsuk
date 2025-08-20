<?php
session_start();
if (!isset($_SESSION['order_data'])) {
    die("Tidak ada data pesanan.");
}

$order = $_SESSION['order_data'];
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total_harga = 0;

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aroma_isuk';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $sql = "SELECT * FROM menu WHERE id IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart[$row['id']];
        $items[] = $row;
        $total_harga += $row['price'] * $row['quantity'];
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            text-align: center;
        }
        .struk-container {
            width: 350px;
            margin: auto;
            padding: 15px;
            border: 1px solid #000;
            text-align: left;
        }
        h2 {
            text-align: center;
        }
        .struk-table {
            width: 100%;
            border-collapse: collapse;
        }
        .struk-table th, .struk-table td {
            border-bottom: 1px dashed #000;
            padding: 5px;
            text-align: left;
        }
        .total {
            font-weight: bold;
        }
        .btn-cetak {
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="struk-container">
        <h2>Struk Pembelian</h2>
        <p><strong>Nama:</strong> <?= $_POST['nama'] ?? 'Pelanggan' ?></p>
        <p><strong>Alamat:</strong> <?= $order['alamat'] ?></p>
        <p><strong>Metode Pengiriman:</strong> <?= $order['pengiriman'] ?></p>
        <?php if ($order['pengiriman'] === 'dikirim'): ?>
            <p><strong>Kurir:</strong> <?= $order['kurir'] ?></p>
            <p><strong>Ongkos Kirim:</strong> Rp <?= number_format($order['ongkir'], 0, ',', '.') ?></p>
        <?php endif; ?>
        <hr>
        <table class="struk-table">
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Harga</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="2">Total</td>
                <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
            </tr>
        </table>
        <hr>
        <p><em>Terima kasih telah berbelanja!</em></p>
    </div>
    <button class="btn-cetak" onclick="window.print()">Cetak Struk</button>
</body>
</html>
