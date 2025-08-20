<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - AromaIsuk</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body background="image/book-bg.jpg">

    <section class="book" id="book">
        <h1 class="heading">DAFTAR <span>AromaIsuk</span></h1>

        <form action="proses_daftar.php" method="POST" autocomplete="off">
            <input type="text" name="username" placeholder="Username" class="box" required>
            <input type="email" name="email" placeholder="Email" class="box" required>
            <input type="password" name="password" placeholder="Password" class="box" required>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" class="box" required>
            <input type="submit" value="DAFTAR" class="btn">
            <a href="login.php">Sudah punya akun?</a>
        </form>

        <div style="margin-top: 10px; text-align: center;">

        </div>
    </section>

    <script src="js/script.js"></script>

</body>

</html>
