<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$profile_image = isset($user['profile_image']) && file_exists('uploads/' . $user['profile_image']) ? 'uploads/' . $user['profile_image'] : 'image/default-profile.jpg';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profile-container {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 30px;
            margin: 20px auto;
            max-width: 900px;
        }

        .image-container {
            text-align: center;
        }

        .image-container img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin-bottom: 10px;
        }

        .image-container .btn {
            display: inline-block;
            margin-top: 0;
            cursor: pointer;
        }

        .form {
            margin: 0 auto 2rem auto;
            max-width: 500px;
            border-radius: var(--border-radius-hover);
            padding: 3rem;
            border: var(--border);
        }

        .form-container {
            flex: 1;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group .box {
            width: 100%;
            padding: 1rem 1.2rem;
            border-radius: .5rem;
            font-size: 1.6rem;
            background: none;
            text-transform: none;
            color: var(--main-color);
            border: var(--border);
            margin: .7rem 0;
        }

        .form .box:focus {
            border: var(--border-hover);
        }

        .form textarea {
            height: 15rem;
            resize: none;
        }

        .form-group .box:focus {
            outline: none;
            border-color: #666;
        }

        .logout-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }

        input[type="file"] {
            display: none;
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
    <section class="about" id="profil">
        <a href="index.php"></a><h1 class="heading">Edit Profil <span>Aroma Isuk</span></h1>
        <a href="index.php" class="back-to-index">← Kembali</a>

    <div class="form-container">
        <h3 class="title">Ubah Informasi Profil Anda</h3>
        <form action="proses_edit_profile.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="image-container">
                <img id="profile-pic" src="<?php echo $profile_image; ?>" alt="Foto Profil">
                <div>
                    <label for="profile-input" class="btn">Ganti Foto</label>
                    <input type="file" id="profile-input" name="profile_image" accept="image/*">
                </div>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="box" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="box" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="old_password">Password Lama</label>
                <input type="password" id="old_password" name="old_password" class="box" placeholder="Isi dengan Password Lama mu">
            </div>
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" class="box" placeholder="Isi dengan Password Baru mu">
            </div>
            <input type="submit" value="Simpan Perubahan" class="btn">
        </form>
    </div>

    <!-- Tombol Logout -->
    <div class="logout-btn">
        <a href="logout.php" class="btn">Logout</a>
    </div>

    <script>
        const profileInput = document.getElementById("profile-input");
        const profilePic = document.getElementById("profile-pic");

        profileInput.addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    profilePic.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
