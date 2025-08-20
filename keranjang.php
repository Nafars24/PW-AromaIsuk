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

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['menu_id']) && isset($_POST['change'])) {
        $menuId = intval($_POST['menu_id']);
        $change = intval($_POST['change']);

        if (isset($_SESSION['cart'][$menuId])) {
            $_SESSION['cart'][$menuId] += $change;

            if ($_SESSION['cart'][$menuId] <= 0) {
                unset($_SESSION['cart'][$menuId]);
            }
        }
        header("Location: keranjang.php");
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        if (isset($_POST['delete_items'])) {
            foreach ($_POST['delete_items'] as $deleteId) {
                unset($_SESSION['cart'][$deleteId]);
            }
        }
        header("Location: keranjang.php");
        exit;
    }
}

$cart = $_SESSION['cart'];
$items = [];
$totalHargaKeseluruhan = 0;

if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $sql = "SELECT * FROM menu WHERE id IN ($ids)";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['quantity'] = $cart[$row['id']];
            $items[] = $row;
            $totalHargaKeseluruhan += $row['price'] * $row['quantity'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
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
        .cart-items {
            flex-grow: 1;
            padding-right: 20px;
            border-right: 2px solid #ddd;
        }
        .summary {
            width: 300px;
            padding-left: 20px;
        }
        .cart-header, .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
            font-size: 18px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .cart-item img {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
            margin-right: 15px;
        }
        .cart-info {
            flex-grow: 1;
        }
        .cart-info h3 {
            margin: 0;
            font-size: 16px;
        }
        .cart-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }
        .custom-checkbox {
            display: inline-block;
            position: relative;
            margin-right: 10px;
        }
        .custom-checkbox input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        .custom-checkbox .checkmark {
            width: 20px;
            height: 20px;
            background-color: #ddd;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
            cursor: pointer;
        }
        .custom-checkbox input[type="checkbox"]:checked + .checkmark {
            background-color: #8e6e53;
        }
        .custom-checkbox .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 4px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .custom-checkbox input[type="checkbox"]:checked + .checkmark:after {
            display: block;
        }
        .quantity-control {
            display: flex;
            align-items: center;
        }
        .quantity-control button {
            border: none;
            background: #8e6e53;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }
        .quantity-control span {
            margin: 0 10px;
            font-size: 16px;
        }
        .summary-footer {
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            flex-direction: column;
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
<h1 class="heading">Keranjang <span>Belanja</span></h1>
    <a href="index.php" class="back-to-index"><- Kembali</a>
    <div class="container">
        <div class="cart-items">
            <div class="cart-header">
                <span>Keranjang Belanja</span>
            </div>
            <?php if (empty($items)): ?>
                <p class="empty-cart">Keranjang Anda kosong.</p>
            <?php else: ?>
                <form method="POST" action="">
                    <?php foreach ($items as $item): ?>
                        <div class="cart-item">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="delete_items[]" value="<?= $item['id'] ?>">
                                <span class="checkmark"></span>
                            </label>
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="cart-info">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p>Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                            </div>
                            <div class="quantity-control">
                                <button type="button" onclick="updateQuantity(<?= $item['id'] ?>, -1)">-</button>
                                <span><?= $item['quantity'] ?></span>
                                <button type="button" onclick="updateQuantity(<?= $item['id'] ?>, 1)">+</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="cart-footer">
                        <button type="submit" name="action" value="delete" class="btn btn-delete">Hapus yang Dipilih</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
        <div class="summary">
            <div class="summary-header">
                <span>Ringkasan Belanja</span>
            </div>
            <div class="summary-footer">
                <span>Total Harga:</span>
                <span>Rp <?= number_format($totalHargaKeseluruhan, 0, ',', '.') ?></span>
                <a href="informasi.php" class="btn">Checkout</a>
            </div>
        </div>
    </div>
    <script>
        function updateQuantity(menuId, change) {
            const formData = new FormData();
            formData.append('menu_id', menuId);
            formData.append('change', change);
            fetch('keranjang.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(() => {
                window.location.reload();
            }).catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
