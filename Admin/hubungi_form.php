<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['balasan'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $balasan = $conn->real_escape_string($_POST['balasan']);
    $conn->query("UPDATE hubungi SET balasan='$balasan' WHERE id=$id");
    echo "<script>window.parent.closeModal(); window.parent.location.reload();</script>";
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM hubungi WHERE id=$id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balas Pesan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #4e342e; 
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container h2 {
            text-align: center;
            color: #D7CCC8;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            color: #FFCCBC;
            font-weight: bold;
            margin-bottom: 5px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
            background: #795548;
            color: #fff;
            resize: none;
            height: 100px;
        }

        button {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
            background-color: #D84315; 
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            position: relative;
        }

        button:hover {
            background-color: #BF360C;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Balas Pesan</h2>
        <form method="post">
            <div class="form-group">
                <label>Pesan</label>
                <p><?php echo htmlspecialchars($data['alamat']); ?></p>
            </div>

            <div class="form-group">
                <label>Balasan</label>
                <textarea name="balasan" required></textarea>
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            </div>

            <button type="submit">Kirim</button>
        </form>
    </div>
</body>
</html>
