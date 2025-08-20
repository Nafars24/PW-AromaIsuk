<?php
session_start();
require_once('tcpdf/tcpdf.php'); // Pastikan TCPDF sudah diinstal

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aroma_isuk';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$items = [];
$total_harga = 0;

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

$kurir_ongkir = [
    'Kurir Toko' => 10000,
    'Gosend' => 15000,
];

$ongkir = 0;
$kurir_terpilih = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat = $_POST['alamat'];
    $pengiriman = $_POST['pengiriman'];
    $kurir_terpilih = $_POST['kurir'] ?? null;

    if ($pengiriman === 'dikirim' && $kurir_terpilih && isset($kurir_ongkir[$kurir_terpilih])) {
        $ongkir = $kurir_ongkir[$kurir_terpilih];
    }
    
    $total_semua = $total_harga + $ongkir;
    $_SESSION['order_data'] = [
        'user_id' => 1, 
        'alamat' => $alamat,
        'pengiriman' => $pengiriman,
        'kurir' => $kurir_terpilih,
        'ongkir' => $ongkir,
        'total_harga' => $total_semua,
    ];
    header("Location: metode_pembayaran.php");
    exit;
}

// Fungsi Cetak Struk
// Fungsi Cetak Struk
if (isset($_GET['cetak'])) {
    if (!isset($_SESSION['order_data'])) {
        die("Tidak ada data pesanan.");
    }

    $order = $_SESSION['order_data'];
    $alamat = $order['alamat'];
    $pengiriman = $order['pengiriman'];
    $kurir_terpilih = $order['kurir'];
    $ongkir = $order['ongkir'];
    $total_semua = $order['total_harga'];

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    
    $html = '<h2>Struk Pembelian</h2><table border="1" cellpadding="5"><tr><th>Nama Menu</th><th>Jumlah</th><th>Harga</th></tr>';
    foreach ($items as $item) {
        $html .= "<tr><td>{$item['name']}</td><td>{$item['quantity']}</td><td>Rp " . number_format($item['price'] * $item['quantity'], 0, ',', '.') . "</td></tr>";
    }
    $html .= "<tr><td colspan='2'><strong>Total Harga Barang</strong></td><td><strong>Rp " . number_format($total_harga, 0, ',', '.') . "</strong></td></tr>";
    $html .= "<tr><td colspan='2'><strong>Ongkir ({$kurir_terpilih})</strong></td><td><strong>Rp " . number_format($ongkir, 0, ',', '.') . "</strong></td></tr>";
    $html .= "<tr><td colspan='2'><strong>Total Keseluruhan</strong></td><td><strong>Rp " . number_format($total_semua, 0, ',', '.') . "</strong></td></tr></table>";

    // Menambahkan informasi tambahan seperti alamat, pengiriman, kurir
    $html .= "<p><strong>Alamat Pengiriman:</strong> {$alamat}</p>";
    $html .= "<p><strong>Metode Pengiriman:</strong> {$pengiriman}</p>";
    $html .= "<p><strong>Kurir:</strong> {$kurir_terpilih}</p>";

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('struk_pembelian.pdf', 'D');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pengiriman</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            display: flex;
            max-width: 1000px;
            margin: auto;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            flex-grow: 1;
            padding-right: 20px;
            border-right: 2px solid #ddd;
        }
        .summary {
            width: 300px;
            padding-left: 20px;
        }
        .header, .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
            font-size: 18px;
        }
        .form-group {
            margin: 15px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="radio"],
        .form-group select,
        .form-group textarea,
        .form-group p {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .form-group textarea {
            resize: vertical;
        }
        .summary-footer {
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            flex-direction: column;
        }
        .btn i {
            margin-left: 5px;
        }
        #kurir-options {
            display: none;
        }
        #ongkir-display {
            display: none;
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
<h1 class="heading">Informasi<span>Pengiriman</span></h1>
    <a href="keranjang.php" class="back-to-index">← Kembali</a>
    <div class="container">
        <div class="form-section">
            <section class="book" id="book">
                <form method="POST" action="">
                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" class="box" required>
                </div>

                <div class="form-group">
                    <label>Metode Pengiriman:</label>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <label><input type="radio" name="pengiriman" value="dikirim" required> Dikirim</label>
                        <label><input type="radio" name="pengiriman" value="ambil"> Ambil di Tempat</label>
                    </div>
                </div>

                <div id="kurir-options">
                    <div class="form-group">
                        <label for="alamat">Alamat Lengkap:</label>
                        <textarea id="alamat" name="alamat" class="box" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="kurir">Pilih Kurir:</label>
                        <select id="kurir" name="kurir" class="box">
                            <option value="">-- Pilih Kurir --</option>
                            <?php foreach ($kurir_ongkir as $kurir => $harga): ?>
                                <option value="<?= $kurir ?>"><?= $kurir ?> - Rp <?= number_format($harga, 0, ',', '.') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                </section>
        </div>
        <div class="summary">
            <div class="summary-header">
                <span>Ringkasan Belanja</span>
            </div>
            <div class="summary-footer">
                <span>Total Harga Barang:</span>
                <span>Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                <div id="ongkir-display">
                    <label>Ongkos Kirim:</label>
                    <p id="ongkir-amount">Rp 0</p>
                </div>
                <span>Total Keseluruhan:</span>
                <p id="total-display">Rp <?= number_format($total_harga, 0, ',', '.') ?></p>
                <button type="submit" class="btn">Lanjutkan <i class="fas fa-arrow-right"></i></button>
                <a href="?cetak=true" class="btn">Cetak Struk</a>
            </div>
        </div>
    </div>
</section>

<script>
    const pengirimanRadios = document.getElementsByName('pengiriman');
    const kurirOptions = document.getElementById('kurir-options');
    const ongkirDisplay = document.getElementById('ongkir-display');
    const ongkirAmount = document.getElementById('ongkir-amount');
    const totalDisplay = document.getElementById('total-display');
    const kurirSelect = document.getElementById('kurir');

    const totalBarang = <?= $total_harga ?>;
    const ongkirData = <?= json_encode($kurir_ongkir) ?>;

    pengirimanRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'dikirim') {
                kurirOptions.style.display = 'block';
            } else {
                kurirOptions.style.display = 'none';
                ongkirDisplay.style.display = 'none';
                totalDisplay.innerText = `Rp ${totalBarang.toLocaleString('id-ID')}`;
            }
        });
    });

    kurirSelect.addEventListener('change', () => {
        const kurir = kurirSelect.value;
        if (kurir && ongkirData[kurir]) {
            const ongkir = ongkirData[kurir];
            ongkirAmount.innerText = `Rp ${ongkir.toLocaleString('id-ID')}`;
            totalDisplay.innerText = `Rp ${(totalBarang + ongkir).toLocaleString('id-ID')}`;
            ongkirDisplay.style.display = 'block';
        } else {
            ongkirDisplay.style.display = 'none';
            totalDisplay.innerText = `Rp ${totalBarang.toLocaleString('id-ID')}`;
        }
    });
</script>
</body>
</html>