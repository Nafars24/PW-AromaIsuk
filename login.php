<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body background="image/book-bg.jpg">

    <section class="book" id="book">
        <h1 class="heading">LOGIN <span>AromaIsuk</span></h1>

        <form action="proses_login.php" method="POST" autocomplete="off">
        <input type="text" name="username" placeholder="Username" class="box" autocomplete="off" required>
        <input type="password" name="password" placeholder="Password" class="box" autocomplete="off" required>
        <input type="submit" value="LOGIN" class="btn">
        <a href="daftar.php" class="btn">DAFTAR</a>
        </form>
    </section>

    <script src="js/script.js"></script>

</body>

</html>
