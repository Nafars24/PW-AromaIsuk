<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "aroma_isuk";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $confirm_password = mysqli_real_escape_string($conn, $_POST["confirm_password"]);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "Semua field harus diisi!";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Password dan Konfirmasi Password tidak cocok!";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $check_user = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($check_user);

    if ($result->num_rows > 0) {
        echo "Username atau Email sudah terdaftar!";
        exit;
    }

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Pendaftaran berhasil! <a href='login.php'>Login di sini</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
