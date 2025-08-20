<?php
$status = $_GET['status'] ?? 'pending';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
</head>
<body>
    <h1>Konfirmasi Pembayaran</h1>
    <?php if ($status === 'success'): ?>
        <p>Pembayaran Anda berhasil. Terima kasih atas pesanan Anda!</p>
    <?php else: ?>
        <p>Status pembayaran: <?= htmlspecialchars($status); ?></p>
    <?php endif; ?>
</body>
</html>