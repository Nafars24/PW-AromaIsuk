<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Terkirim</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
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
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #5c4033;
            margin: 10px 0 20px;
        }
        .btn {
            display: inline-block;
            background: #6f4e37;
            color: #fff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin: 10px 5px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #5c4033;
        }
        .coffee-icon {
            font-size: 60px;
            color: #6f4e37;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="coffee-icon">☕</div>
        <h1><?= $pesan ?></h1>
        <p>Data Berhasil di Hapus.</p>
        <a href="../admin/dashboard.php" class="btn">Oke!</a>
    </div>
</body>
</html>