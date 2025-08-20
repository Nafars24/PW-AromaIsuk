<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM menu WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM menu";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        button {
            margin: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #4E342E;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            color: white;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
        }
        .close {
            cursor: pointer;
            font-size: 22px;
            color: #FFCCBC;
        }
        .close:hover {
            color: #FF8A65;
        }
        .modal-body input, .modal-body textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
        }
        .modal-footer {
            margin-top: 10px;
            text-align: center;
        }
        .modal-footer button {
            background: #FF5722;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Kelola Menu</h1>

    <form method="get" action="menu.php">
        <input type="text" name="search" placeholder="Cari menu..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Cari</button>
    </form>

    <button onclick="openModal('add')">Tambah Menu Baru</button>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="../<?php echo htmlspecialchars($row['image']); ?>">
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button onclick="openModal('edit', <?php echo $row['id']; ?>)">Edit</button>
                            <form method="post" action="menu_delete.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="menuModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
    </div>

    <script>
        function openModal(action, id = '') {
            let url = action === 'edit' ? `menu_form.php?action=edit&id=${id}` : 'tambah_menu.php?action=add';
            document.getElementById('menuFrame').src = url;
            document.getElementById('menuModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('menuModal').style.display = 'none';
        }
    </script>

</body>
</html>