<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $result = $conn->query("SELECT image FROM menu WHERE id = $id");
    $data = $result->fetch_assoc();

    if ($data && !empty($data['image'])) {
        $imagePath = "uploads/" . $data['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $conn->query("DELETE FROM menu WHERE id = $id");

    header("Location: ../notifikasi/notifikasi_hapusmenu.php");
    exit;
} else {
    header("Location: menu.php");
    exit;
}
?>
