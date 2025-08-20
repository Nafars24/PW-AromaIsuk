<?php
session_start();
include 'config.php'; // Koneksi ke database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Cek apakah file telah diunggah
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['profile_image']['tmp_name'];
    $file_name = $_FILES['profile_image']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi ekstensi file
    if (in_array($file_ext, $allowed_ext)) {
        $new_file_name = uniqid() . '.' . $file_ext; // Nama file unik
        $upload_dir = 'uploads/';
        $upload_path = $upload_dir . $new_file_name;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Simpan nama file ke database
            $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $stmt->bind_param("si", $new_file_name, $user_id);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Foto profil berhasil diubah.';
                header('Location: profile.php');
            } else {
                $_SESSION['error'] = 'Gagal menyimpan foto profil ke database.';
                unlink($upload_path); // Hapus file yang sudah diunggah
            }
        } else {
            $_SESSION['error'] = 'Gagal mengunggah file.';
        }
    } else {
        $_SESSION['error'] = 'Format file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.';
    }
} else {
    $_SESSION['error'] = 'Tidak ada file yang diunggah.';
}

header('Location: profile.php');
exit;
?>
