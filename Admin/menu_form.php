<?php
$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM menu WHERE id=$id");
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'] ?: $row['image'];

    if ($_FILES['image']['name']) {
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "UPDATE menu SET name='$name', description='$description', price='$price', image='$image' WHERE id=$id";
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
    <title>Edit Menu</title>
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

<div class="form-container">
    <h2>Edit Menu</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Menu</label>
            <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" required><?php echo $row['description']; ?></textarea>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="price" value="<?php echo $row['price']; ?>" required>
        </div>

        <div class="form-group">
            <label>Upload Gambar</label>
            <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="preview-container">
            <img id="preview" src="uploads/<?php echo $row['image']; ?>" alt="Preview Gambar">
        </div>

        <button type="submit">Update</button>
    </form>
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
