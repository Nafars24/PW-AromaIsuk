<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    if ($image) {
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "INSERT INTO menu (name, description, price, image) VALUES ('$name', '$description', '$price', '$image')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.parent.closeModal(); window.parent.location.reload();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-dialog {
        background: #4E342E;
        border-radius: 10px;
        padding: 20px;
        width: 400px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #FFCCBC;
        padding-bottom: 10px;
    }

    .modal-header h2 {
        color: #FFCCBC;
        margin: 0;
    }

    .close {
        cursor: pointer;
        font-size: 24px;
        color: #FF8A65;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        color: #FFCCBC;
        font-weight: bold;
    }

    input, textarea {
        width: 100%;
        padding: 8px;
        border: none;
        border-radius: 5px;
        background: #795548;
        color: #fff;
    }

    button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #D84315;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background-color: #BF360C;
    }

    .preview-container {
        text-align: center;
        margin-top: 10px;
    }

    .preview-container img {
        max-width: 100px;
        border-radius: 5px;
        border: 2px solid #FFAB91;
    }
    </style>
</head>
<body>

<div id="menuModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tambah Menu</h2>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama Menu</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="price" required>
                    </div>

                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
                    </div>

                    <div class="preview-container">
                        <img id="preview" src="" alt="Preview Gambar">
                    </div>

                    <button type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
