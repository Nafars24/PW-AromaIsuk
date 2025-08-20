<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan POST tidak kosong
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nomor_hp = trim($_POST['nomor_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    // Validasi input
    if (empty($nama) || empty($email) || empty($nomor_hp) || empty($alamat)) {
        $pesan = "Semua kolom harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pesan = "Format email tidak valid!";
    } elseif (!preg_match('/^[0-9]+$/', $nomor_hp)) {
        $pesan = "Nomor HP harus berupa angka!";
    } else {
        try {
            // Gunakan Prepared Statement untuk keamanan
            $stmt = $conn->prepare("INSERT INTO hubungi (nama, email, nomor_hp, alamat) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $email, $nomor_hp, $alamat);

            if ($stmt->execute()) {
                $pesan = "Pesan Anda berhasil dikirim!";
            } else {
                $pesan = "Terjadi kesalahan saat mengirim pesan.";
            }

            $stmt->close();
        } catch (Exception $e) {
            $pesan = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
} else {
    $pesan = "Akses tidak diizinkan!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Terkirim</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: url('../image/home-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            max-width: 450px;
        }
        h1 {
            color: #6f4e37;
            font-size: 28px;
        }
        p {
            font-size: 16px;
            color: #5c4033;
        }
        .btn {
            display: inline-block;
            background: #6f4e37;
            color: #fff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn:hover {
            background: #5c4033;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($pesan) ?></h1>
        <p>Terima kasih telah menghubungi Aroma Isuk.</p>
        <a href="../index.php" class="btn">Kembali ke Halaman Utama</a>
    </div>
</body>
</html>