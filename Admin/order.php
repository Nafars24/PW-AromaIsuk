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
    $order_id = $_POST['order_id'];
    $new_status = 'Berhasil';

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
}

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchQuery)) {
    $sql = "SELECT * FROM orders WHERE alamat LIKE ? OR kurir LIKE ? OR status LIKE ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $searchParam = '%' . $searchQuery . '%';
    $stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .print-button-container {
            text-align: right;
            margin-bottom: 5px;
        }
        .print-button {
            background: #6f4e37;
            color: white;
            padding: 10px 0.2px;
            text-decoration: none;
            border-radius: 5px;
        }
        .print-button:hover {
            background: #d4a373;
        }
        .confirm-button {
            background: #8e6e53;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        .confirm-button.disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <section class="dashboard">
        <h1>Daftar Pesanan</h1>
        <form id="search-form" method="GET" action="">
            <input type="text" id="search-query" name="search" placeholder="Cari Pesanan..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Cari</button>
        </form>
        <div class="print-button-container">
            <a href="cetak_pdf.php" target="_blank" class="print-button"><i class="fas fa-file-pdf"></i> Cetak PDF</a>
            <a href="cetak_excel.php" target="_blank" class="print-button"><i class="fas fa-file-excel"></i> Cetak Excel
        </div>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Alamat</th>
                    <th>Kurir</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Konfirmasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $counter = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['kurir']) ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <form method="POST" class="confirm-form">
                                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="confirm-button">Konfirmasi</button>
                                    </form>
                                <?php else: ?>
                                    <button class="confirm-button disabled" disabled>Terkonfirmasi</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".confirm-button").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            let orderId = this.closest("form").querySelector("[name='order_id']").value;

            fetch("order.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "order_id=" + orderId
            })
            .then(response => response.text())
            .then(data => {
                location.reload();
            })
            .catch(error => console.error("Error:", error));
        });
    });
});
</script>

</body>
</html>
