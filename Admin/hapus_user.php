<?php
session_start();
include_once 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo 'Akses ditolak.';
    exit;
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo 'User berhasil dihapus.';
    } else {
        echo 'Gagal menghapus user.';
    }

    $stmt->close();
}
?>
