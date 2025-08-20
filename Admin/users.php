<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $idsToDelete = implode(",", array_map('intval', explode(',', $_POST['delete_ids'])));
    $conn->query("DELETE FROM users WHERE id IN ($idsToDelete)");
    exit;
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM users WHERE username LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM users";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        }   
    </style>
</head>
<body>
<h1>Kelola Users</h1>

<div>
    <form id="search-form" method="GET" action="">
        <input type="text" id="search-query" name="search" placeholder="Cari user..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Cari</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Tgl Dibuat</th>
            <th>Foto Profil</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <?php if ($row['profile_image']): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($row['profile_image']); ?>" width="50">
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Tidak ada data.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>