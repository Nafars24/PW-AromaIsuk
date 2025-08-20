<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cek BalasanMU!</title>
<link rel="stylesheet" href="css/style.css">
<style>
    body {
        font-family: Arial, sans-serif;
        color: #3e2723;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        color: #6d4c41;
        margin-top: 20px;
    }

    .container {
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .message {
        background-color: #efebe9;
        border-left: 5px solid #795548;
        margin: 10px 0;
        padding: 15px;
        border-radius: 5px;
    }

    .message:hover {
        background-color: #d7ccc8;
    }

    .message-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .message-content {
        margin-bottom: 10px;
    }

    .reply {
        color: #6d4c41;
        font-style: italic;
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
<h1 class="heading">Balasan <span>Lihat</span></h1>
    <a href="index.php" class="back-to-index">← Kembali</a>
<div class="container">
    <?php
    $conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM hubungi";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='message'>";
            echo "<div class='message-title'>" . htmlspecialchars($row['nama']) . " (" . htmlspecialchars($row['email']) . ")</div>";
            echo "<div class='message-content'>" . htmlspecialchars($row['alamat']) . "</div>";
            echo "<div class='reply'>Balasan: " . htmlspecialchars($row['balasan'] ?? 'Belum ada balasan') . "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='message'>Tidak ada pesan.</div>";
    }
    ?>
</div>
</body>
</html>
