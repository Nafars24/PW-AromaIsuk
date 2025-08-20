<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $balasan = $conn->real_escape_string($_POST['balasan']);

    $sql = "UPDATE hubungi SET balasan = '$balasan' WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Balasan berhasil dikirim!";
    } else {
        echo "Error: " . $conn->error;
    }
}

if ($conn->query($sql) === TRUE) {
    echo "Balasan berhasil dikirim!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
header("Location: dashboard.php");
exit;
?>