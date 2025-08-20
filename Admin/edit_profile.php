<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');
$admin_id = $_SESSION['admin_id'];
$result = $conn->query("SELECT profile_pic, username FROM admin WHERE id = $admin_id");
$admin = $result->fetch_assoc();
$profilePic = !empty($admin['profile_pic']) ? $admin['profile_pic'] : 'image/admin_profile.jpg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
        $profilePic = $target_file;
        $conn->query("UPDATE admin SET profile_pic='$profilePic' WHERE id=$admin_id");
    }
    echo "success";
    exit;
}
?>

<div class="container">
    <h2>Edit Profile Admin</h2>
    <img id="profilePicPreview" src="<?= $profilePic ?>" alt="Admin Profile">
    <form id="editProfileForm" action="edit_profile.php" method="POST" enctype="multipart/form-data">
        <input type="file" id="profilePicInput" name="profile_pic" style="display: none;">
        <button type="button" onclick="document.getElementById('profilePicInput').click();">Ganti Foto</button>
        <button type="submit">Update Profile</button>
    </form>
</div>

<script>
    document.getElementById('profilePicInput').addEventListener('change', function(event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePicPreview').src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    document.getElementById('editProfileForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('edit_profile.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text())
          .then(result => {
              if (result.trim() === 'success') {
                  loadContent('edit_profile');
              } else {
                  alert('Gagal memperbarui profil');
              }
          });
    });
</script>
