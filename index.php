<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aroma_isuk';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM menu";
$menuResult = $conn->query($sql);

include_once 'config.php';

$isLoggedIn = isset($_SESSION['user_id']);

if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $user = $userResult->fetch_assoc();

    $profilePicture = isset($user['profile_image']) && $user['profile_image']
        ? 'uploads/' . $user['profile_image']
        : 'image/default-profile.jpg';

    $stmt->close();
} else {
    $profilePicture = 'image/default-profile.jpg';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AROMA ISUK</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
<header class="header">
    <div id="menu-btn" class="fas fa-bars"></div>
    <a href="index.php" class="logo">Aroma<span>Isuk</span> <i class="fas fa-mug-hot"></i></a>

    <nav>
        <?php if ($isLoggedIn): ?>
            <a href="keranjang.php" class="cart-btn">
                <i class="fas fa-shopping-cart"></i>
            </a>
            <a href="profile.php" class="profile-btn">
                <img src="<?= htmlspecialchars($profilePicture) ?>" alt="Profile" class="profile-pic">
            </a>
        <?php else: ?>
            <a href="keranjang.php" class="cart-btn">
                <i class="fas fa-shopping-cart"></i>
            </a>
            <a href="login.php" class="btn">Login</a>
        <?php endif; ?>
    </nav>
</header>

    <section class="home" id="home">
        <div class="row">
            <div class="content">
                <h3>Kopi Segar untuk Awali Pagi</h3>
                <a href="#menu" class="btn">Beli Sekarang</a>
                <a href="Pesanan.php" class="btn">Pesanan Saya</a>
            </div>

            <div class="image">
                <img src="image/home-img-1.png" class="main-home-image" alt="">
            </div>
        </div>

        <div class="image-slider">
            <img src="image/home-img-1.png" alt="">
            <img src="image/home-img-2.png" alt="">
            <img src="image/home-img-3.png" alt="">
        </div>
    </section>

    <section class="menu" id="menu">
    <h1 class="heading">Rekomendasi<span>Produk</span></h1>

    <div class="box-container">
        <?php if ($menuResult->num_rows > 0): ?>
            <?php while ($row = $menuResult->fetch_assoc()): ?>
                <a href="detail.php?id=<?= $row['id'] ?>" class="box">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div class="content">
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <span>Rp <?= number_format($row['price'], 0, ',', '.') ?></span>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada menu yang tersedia.</p>
        <?php endif; ?>
    </div>
    </section>


    <!-- REVIEW -->
    <section class="review" id="review">
        <h1 class="heading">reviews <span>Apa kata dunia</span></h1>

        <div class="swiper review-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <i class="fas fa-quote-left"></i>
                    <i class="fas fa-quote-right"></i>
                    <img src="image/pic-1.png" alt="">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>Im the Only King!</p>
                    <h3>King Ahem</h3>
                    <span>Best Player</span>
                </div>

                <div class="swiper-slide box">
                    <i class="fas fa-quote-left"></i>
                    <i class="fas fa-quote-right"></i>
                    <img src="image/pic-2.png" alt="">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>Humas Tanpa Kopi = Hampa</p>
                    <h3>Bangsawan Pateh</h3>
                    <span>#1 Humas</span>
                </div>

                <div class="swiper-slide box">
                    <i class="fas fa-quote-left"></i>
                    <i class="fas fa-quote-right"></i>
                    <img src="image/pic-3.png" alt="">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>-</p>
                    <h3>Bang Miqo</h3>
                    <span>CEO MnM</span>
                </div>

                <div class="swiper-slide box">
                    <i class="fas fa-quote-left"></i>
                    <i class="fas fa-quote-right"></i>
                    <img src="image/pic-4.png" alt="">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>Gondrong For Life</p>
                    <h3>Ojan</h3>
                    <span>Ketua Gondrong Dunia</span>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <section class="about" id="about">
        <h1 class="heading">about kami<span>Aroma Isuk</span></h1>

        <div class="row">
            <div class="image">
                <img src="image/about-img.png" alt="">
            </div>

            <div class="content">
                <h3 class="title">What Makes Our Coffee Special?</h3>
                    <p>Kami menggunakan biji kopi pilihan berkualitas tinggi yang disangrai dengan sempurna untuk menciptakan rasa yang khas. 
                        Setiap cangkir diracik dengan cinta Bangsawan Fateh dan perhatian terhadap detail, menghadirkan pengalaman kopi yang tak terlupakan.  
                        Temukan harmoni sempurna antara aroma, rasa, dan kenikmatan dalam setiap tegukan.</p>
                <a href="#" class="btn">Gas Beli!</a>
                <div class="icons-container">
                    <div class="icons">
                        <img src="image/about-icon-1.png" alt="">
                        <h3>Kopi Berkualitas</h3>
                    </div>
                    <div class="icons">
                        <img src="image/about-icon-2.png" alt="">
                        <h3>Toko Kita</h3>
                    </div>
                    <div class="icons">
                        <img src="image/about-icon-3.png" alt="">
                        <h3>Gratis Ongkir</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="book" id="book">
        <h1 class="heading">Hubungi <span>Moh. Naufal Alfarisi</span></h1>

        <form action="notifikasi/proses_hubungi.php" method="POST">
            <input type="text" name="nama" placeholder="Nama" class="box" required>
            <input type="email" name="email" placeholder="Email" class="box" required>
            <input type="number" name="nomor_hp" placeholder="Nomor HP" class="box" required>
            <textarea name="alamat" placeholder="Pesan" class="box" cols="30" rows="10" required></textarea>
            <input type="submit" value="Kirim" class="btn">
            <a href="lihat_pesan.php" class="btn">Cek Balasan</a>
        </form>
    </section>        

    <!-- FOOTER -->
    <section class="footer">
        <div class="box-container">

            <div class="box">
                <h3>quick links</h3>
                <a href="#home"><i class="fas fa-arrow-right"></i> home</a>
                <a href="#about"><i class="fas fa-arrow-right"></i> about</a>
                <a href="#menu"><i class="fas fa-arrow-right"></i> menu</a>
                <a href="#review"><i class="fas fa-arrow-right"></i> review</a>
                <a href="#book"><i class="fas fa-arrow-right"></i> hubungi</a>
            </div>

            <div class="box">
                <h3>contact info</h3>
                <a href="https://web.facebook.com/zyfars"><i class="fab fa-facebook-f"></i> facebook</a>
                <a href="https://x.com/falzyfars"><i class="fab fa-twitter"></i> twitter</a>
                <a href="https://www.instagram.com/fal.zfrs/"><i class="fab fa-instagram"></i> instagram</a>
                <a href="https://www.linkedin.com/in/moh-naufal-alfarisi-26472a28b/"><i class="fab fa-linkedin"></i> linkedin</a>
                <a href="https://steamcommunity.com/id/Falzyfars"><i class="fab fa-steam"></i> steam</a>
            </div>
        </div>

        <div class="credit">created by <span>Zyfars</span> | Gaskan</div>
    </section>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>

</body>

</html>