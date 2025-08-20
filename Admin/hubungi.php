<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM hubungi WHERE nama LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%' OR nomor_hp LIKE '%$searchQuery%' OR alamat LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM hubungi";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hubungi</title>
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
    </style>
</head>
<body>

    <h1>Siapa yang Menghubungi?</h1>
    <div>
        <form id="search-form">
            <input type="text" id="search-query" placeholder="Cari data..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Pesan</th>
                <th>Balasan</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomor_hp']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td>
                            <button onclick="openModal('reply', <?php echo $row['id']; ?>)">Balas</button>
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

    <div id="replyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
    </div>

    <script>
        function openModal(action, id = '') {
            let url = action === 'reply' ? `hubungi_form.php?id=${id}` : '';
            document.getElementById('replyFrame').src = url;
            document.getElementById('replyModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('replyModal').style.display = 'none';
        }
    </script>

</body>
</html>