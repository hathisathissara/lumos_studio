<?php 
require_once 'layout/header.php'; 
// 1. Latest Weddings (6)
$weddings = $conn->query("SELECT * FROM weddings ORDER BY id DESC LIMIT 6")->fetchAll();

// 2. Latest Testimonials (4)
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY id DESC LIMIT 4")->fetchAll();

// 3. Portfolio (Portfolio Table එකෙන් දත්ත)
$portfolio = $conn->query("SELECT * FROM portfolio ORDER BY id DESC LIMIT 6")->fetchAll();

// 4. Wedding Images (For Portfolio Slideshow)
$wedding_images = $conn->query("SELECT * FROM wedding_images ORDER BY id DESC LIMIT 10")->fetchAll();
?>

<!-- Black & White Theme Custom CSS -->
<style>
    /* Theme Colors */
   body { background-color: #ffffff; color: #333; }
    .section-title { font-weight: 300; letter-spacing: 5px; text-transform: uppercase; margin-bottom: 50px; text-align: center; }
    .album-card img { transition: 0.5s; }
    .album-card:hover img { transform: scale(1.05); }
    .btn-outline-custom { border: 1px solid #333; color: #333; border-radius: 0; padding: 10px 30px; }
    .btn-outline-custom:hover { background: #333; color: #fff; }
    
    /* Hero Carousel Image Settings */
    .hero-img {
        height: 100vh;
        object-fit: cover;
        filter: grayscale(20%); /* පොඩි Black & white ගතියක් පින්තූර වලට දෙනවා */
    }

    /* Black Overlay for Slider text */
    .carousel-caption {
        background: rgba(0, 0, 0, 0.5);
        padding: 20px;
        border-radius: 5px;
    }

    /* Section Titles */
    .section-title {
        font-weight: 300;
        letter-spacing: 5px;
        text-transform: uppercase;
        text-align: center;
        margin: 0 auto 50px;
        display: block;
    }

    /* Card Styling (Albums) */
    .album-card {
        border: none;
        transition: 0.3s;
        cursor: pointer;
        background-color: #f8f9fa;
    }
    .album-card:hover {
        transform: translateY(-5px);
    }
    .album-img {
        height: 350px;
        object-fit: cover;
        transition: 0.5s;
    }
    .album-card:hover .album-img {
        filter: brightness(60%);
        transform: scale(1.05);
    }
    .album-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        transition: all 0.4s ease;
    }
    .album-card:hover .album-overlay {
        opacity: 1;
    }

    /* Buttons */
    .btn-theme {
        background-color: #000;
        color: #fff;
        border: 1px solid #000;
        border-radius: 0;
        padding: 10px 30px;
        transition: 0.3s;
    }
    .btn-theme:hover {
        background-color: #fff;
        color: #000;
    }
</style>

<!-- 1. Hero Section (Slideshow) -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item ">
            <!-- පින්තූරය මෙතනට දාන්න -->
            <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1920&auto=format&fit=crop" class="d-block w-100 hero-img" alt="Wedding Image 1">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="fw-bold">TIMELESS MEMORIES</h1>
                <p>Capturing your beautiful moments with elegance.</p>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1920&auto=format&fit=crop" class="d-block w-100 hero-img" alt="Wedding Image 2">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="fw-bold">ART OF LOVE</h1>
                <p>Every picture tells a story of affection.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=1920&auto=format&fit=crop" class="d-block w-100 hero-img" alt="Wedding Image 3">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="fw-bold">ELEGANT STORIES</h1>
                <p>Crafting visual stories that you will cherish forever.</p>
            </div>
        </div>
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1460978812857-470ed1c77af0?q=80&w=1920&auto=format&fit=crop" class="d-block w-100 hero-img" alt="Wedding Image 4">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="fw-bold">TIMELESS EMOTIONS</h1>
                <p>Preserving the true essence of your beautiful bond.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- 2. Photographer Description (About Us Preview) -->
<section class="container py-5 mt-5">
    <div class="row align-items-center">
        <div class="col-md-5 mb-4 mb-md-0">
            <!-- Photographer ගේ පින්තූරය -->
            <img src="assets/about/dinith.webp" class="img-fluid" style="filter: grayscale(100%);" alt="Photographer">
        </div>
        <div class="col-md-7 px-md-5">
            <h2 class="section-title">Hello, I'm Dinith Nishan</h2>
            <p class="lead">A professional visual storyteller based in Sri Lanka.</p>
            <p class="text-muted">
                With over a decade of experience in wedding photography, I believe in capturing the raw, unscripted moments of your special day. My style is a blend of fine-art photography and photojournalism, presented in a timeless Black & White and natural color palette. Let's make your memories eternal.
            </p>
            <a href="about.php" class="btn btn-theme mt-3">Read My Story</a>
        </div>
    </div>
</section>

<!-- ALBUMS (Database එකෙන්) -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">ALBUMS</h2>
        <div class="row g-4">
            <?php foreach ($weddings as $w): ?>
            <div class="col-md-4">
                <a href="weddings?id=<?= $w['id'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="album-card position-relative overflow-hidden">
                        <img src="assets/uploads/weddings/<?= $w['cover_image'] ?>" class="img-fluid w-100 album-img" alt="<?= $w['title'] ?>">
                        <div class="album-overlay d-flex flex-column justify-content-center align-items-center">
                            <h4 class="text-white text-center px-3 fw-light" style="letter-spacing: 1px;"><?= $w['title'] ?></h4>
                            <small class="text-white-50" style="letter-spacing: 2px;">Wedding</small>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- TESTIMONIALS (Database එකෙන්) -->
<section class="py-5" style="background-color: #ebebeb;">
    <div class="container">
        <h2 class="section-title">TESTIMONIALS</h2>
        <div class="row">
            <?php $i = 0; foreach ($testimonials as $t): ?>
            <div class="col-md-12 mb-5">
                <div class="row align-items-center">
                    <?php if ($i % 2 == 0): ?>
                        <div class="col-md-6 pe-md-5">
                            <h5 class="text-uppercase mb-3" style="letter-spacing: 2px; font-weight: 400;"><?= htmlspecialchars($t['client_name']) ?></h5>
                            <p class="text-muted" style="line-height: 2; font-size: 0.95rem;">
                                <?= nl2br(htmlspecialchars($t['review_text'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-center">
                            <?php if (!empty($t['image_path'])): ?>
                                <img src="assets/uploads/testimonials/<?= $t['image_path'] ?>" class="img-fluid w-100" style="object-fit: cover;" alt="<?= htmlspecialchars($t['client_name']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="col-md-6 text-center order-md-1 order-2">
                            <?php if (!empty($t['image_path'])): ?>
                                <img src="assets/uploads/testimonials/<?= $t['image_path'] ?>" class="img-fluid w-100" style="object-fit: cover;" alt="<?= htmlspecialchars($t['client_name']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 ps-md-5 order-md-2 order-1">
                            <h5 class="text-uppercase mb-3" style="letter-spacing: 2px; font-weight: 400;"><?= htmlspecialchars($t['client_name']) ?></h5>
                            <p class="text-muted" style="line-height: 2; font-size: 0.95rem;">
                                <?= nl2br(htmlspecialchars($t['review_text'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php $i++; endforeach; ?>
        </div>
        
        <div class="text-center mt-3 mb-2">
            <a href="https://www.facebook.com/profile.php?id=61550491520210&sk=reviews" target="_blank" class="btn btn-outline-dark px-4 py-2" style="letter-spacing: 2px; text-transform: uppercase;">
                Read More Reviews on Facebook
            </a>
        </div>
    </div>
</section>
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- PORTFOLIO (Slideshow) -->
<section class="py-5 bg-white">
    <div class="container-fluid px-0">
        <h2 class="section-title text-center">PORTFOLIO</h2>
        
        <div class="swiper portfolioSwiper mt-4">
            <div class="swiper-wrapper">
                <?php foreach ($portfolio as $p): ?>
                <div class="swiper-slide">
                    <a data-fslightbox="portfolio" href="assets/uploads/portfolio/<?= $p['image_path'] ?>">
                        <img src="assets/uploads/portfolio/<?= $p['image_path'] ?>" alt="<?= htmlspecialchars($p['title']) ?>" style="height: 450px; width: 100%; object-fit: cover; cursor: pointer;">
                    </a>
                </div>
                <?php endforeach; ?>

                <!-- Wedding Folder Images -->
                <?php foreach ($wedding_images as $wi): ?>
                <div class="swiper-slide">
                    <a data-fslightbox="portfolio" href="assets/uploads/weddings/<?= $wi['image_path'] ?>">
                        <img src="assets/uploads/weddings/<?= $wi['image_path'] ?>" alt="Wedding Image" style="height: 450px; width: 100%; object-fit: cover; cursor: pointer;">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Navigation Arrows -->
            <div class="swiper-button-next" style="color: rgba(255, 255, 255, 0.8); text-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>
            <div class="swiper-button-prev" style="color: rgba(255, 255, 255, 0.8); text-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>
        </div>
    </div>
</section>

<!-- Swiper JS & Lightbox -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fslightbox@3.4.1/index.min.js"></script>
<script>
  var swiper = new Swiper(".portfolioSwiper", {
    slidesPerView: 2,
    spaceBetween: 0,
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      576: { slidesPerView: 3 },
      768: { slidesPerView: 4 },
      1024: { slidesPerView: 5 },
      1440: { slidesPerView: 6 },
    },
  });
</script>
<?php 
// Footer එක Include කිරීම
require_once 'layout/footer.php'; 
?>