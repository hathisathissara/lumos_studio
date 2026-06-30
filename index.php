<?php
// Homepage SEO Variables (must be set BEFORE header include)
$page_title       = "Lumos Studio | Professional Wedding Photography in Sri Lanka";
$page_description = "Welcome to Lumos Studio — Sri Lanka's premier wedding photography studio. Explore our wedding albums, photography packages, and timeless portrait portfolio by Dinith Nishan.";
$page_keywords    = "Lumos Studio, Lumos Studio Sri Lanka, wedding photography Sri Lanka, professional photographer, wedding photographer, portrait photography, Dinith Nishan, Mahiyanganaya photographer";
$page_canonical   = "https://lumos.unaux.com/";
require_once 'layout/header.php';
// 1. Latest Albums (Exclude Baby Shoot, fetch up to 7 to check for more)
$weddings = $conn->query("SELECT * FROM weddings WHERE category NOT IN ('Baby Shoot', 'Birthday Shoot') ORDER BY id DESC LIMIT 7")->fetchAll();
$has_more_albums = count($weddings) > 6;
if ($has_more_albums) {
    array_pop($weddings);
}

// 2. Latest Testimonials (4)
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY id DESC LIMIT 4")->fetchAll();

// 3. Portfolio (Portfolio Table එකෙන් දත්ත)
$portfolio = $conn->query("SELECT * FROM portfolio ORDER BY id DESC LIMIT 10")->fetchAll();

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
    @keyframes zoomOut {
        0% { transform: scale(1.15); }
        100% { transform: scale(1); }
    }
    
    .hero-img {
        height: 100vh;
        object-fit: cover;
        filter: grayscale(20%); /* පොඩි Black & white ගතියක් පින්තූර වලට දෙනවා */
        transform: scale(1.15); /* Default state to prevent popping */
        will-change: transform;
    }

    .carousel-item.active .hero-img,
    .carousel-item-next .hero-img,
    .carousel-item-prev .hero-img {
        animation: zoomOut 7s linear forwards;
    }

    /* Black Overlay for Slider text */
    .carousel-caption {
        background: rgba(0, 0, 0, 0.5);
        padding: 20px;
        border-radius: 5px;
    }

    /* ===== Mobile Responsive - Hero Section ===== */
    @media (max-width: 767.98px) {
        .hero-img {
            height: 60vh;          /* mobile එකේ 60vh enough */
            min-height: 300px;     /* minimum height එකක් */
        }
        .carousel-caption {
            padding: 10px 15px;
            bottom: 10px;
            left: 5%;
            right: 5%;
        }
        .carousel-caption h1 {
            font-size: 1.2rem;
            letter-spacing: 2px;
        }
        .carousel-caption p {
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        /* Carousel arrows mobile friendly */
        .carousel-control-prev,
        .carousel-control-next {
            width: 12%;
        }
    }

    @media (max-width: 575.98px) {
        .hero-img {
            height: 50vh;
            min-height: 250px;
        }
        .carousel-caption h1 {
            font-size: 1rem;
            letter-spacing: 1px;
        }
        .carousel-caption p {
            font-size: 0.7rem;
        }
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

    /* Entrance / Scroll Animations */
    .animate-in {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
        will-change: opacity, transform;
    }
    .animate-in.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .animate-left {
        transform: translateX(-40px);
    }
    .animate-right {
        transform: translateX(40px);
    }
    .animate-left.visible,
    .animate-right.visible {
        transform: translateX(0);
    }
    .testimonial-item {
        transition-delay: 0.15s;
    }
    .testimonial-item.visible {
        transition-delay: 0s;
    }
</style>

<!-- 1. Hero Section (Slideshow) -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="false">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <!-- පින්තූරය මෙතනට දාන්න -->
            <img src="assets/hero/ChatGPT Image Jun 30, 2026, 08_50_47 PM.webp" class="d-block w-100 hero-img" alt="Lumos Studio - Wedding Photography Sri Lanka">
            <div class="carousel-caption d-block">
                <h1 class="fw-bold">WELCOME TO LUMOS STUDIO</h1>
                <p>Professional wedding photography — capturing your timeless moments with elegance.</p>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="assets/hero/ChatGPT Image Jun 30, 2026, 08_50_10 PM.webp" class="d-block w-100 hero-img" alt="Lumos Studio - Art of Love Wedding Photography">
            <div class="carousel-caption d-block">
                <h1 class="fw-bold">ART OF LOVE</h1>
                <p>Every picture tells a story of affection — by Lumos Studio.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/hero/ChatGPT Image Jun 30, 2026, 08_52_33 PM.webp" class="d-block w-100 hero-img" alt="Wedding Image 3">
            <div class="carousel-caption d-block">
                <h1 class="fw-bold">ELEGANT STORIES</h1>
                <p>Crafting visual stories that you will cherish forever.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/hero/ChatGPT Image Jun 30, 2026, 08_55_06 PM.webp" class="d-block w-100 hero-img" alt="Wedding Image 4">
            <div class="carousel-caption d-block">
                <h1 class="fw-bold">TIMELESS EMOTIONS</h1>
                <p>Preserving the true essence of your beautiful bond.</p>
            </div>
        </div>
    </div>
</div>

<!-- 2. Photographer Description (About Us Preview) -->
<section class="container py-5 mt-5">
    <div class="row align-items-center">
        <div class="col-md-5 mb-4 mb-md-0 animate-in animate-left">
            <!-- Photographer ගේ පින්තූරය -->
            <img src="assets/about/dinith.webp" class="img-fluid" style="filter: grayscale(100%);" alt="Photographer">
        </div>
        <div class="col-md-7 px-md-5 animate-in animate-right">
            <h2 class="section-title">Hello, I'm Dinith Nishan<br><small style="font-size:0.55em; letter-spacing:3px; opacity:0.7;">Founder & Lead Photographer, Lumos Studio</small></h2>
            <p class="lead">A professional visual storyteller based in Sri Lanka.</p>
            <p class="text-muted">
                With over a decade of experience in wedding photography, I believe in capturing the raw, unscripted moments of your special day. My style is a blend of fine-art photography and photojournalism, presented in a timeless Black & White and natural color palette. Let's make your memories eternal.
            </p>
            <a href="about" class="btn btn-theme mt-3">Read My Story</a>
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
                <a href="albums/<?= !empty($w['slug']) ? htmlspecialchars($w['slug']) : $w['id'] ?>" style="text-decoration: none; color: inherit;">
                    <div class="album-card position-relative overflow-hidden">
                        <img src="assets/uploads/weddings/<?= $w['cover_image'] ?>" class="img-fluid w-100 album-img" alt="<?= $w['title'] ?>">
                        <div class="album-overlay d-flex flex-column justify-content-center align-items-center">
                            <h4 class="text-white text-center px-3 fw-light" style="letter-spacing: 1px;"><?= $w['title'] ?></h4>
                            <small class="text-white-50" style="letter-spacing: 2px;"><?= $w['category'] ?></small>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ($has_more_albums): ?>
        <div class="text-center mt-5">
            <a href="albums" class="btn btn-theme px-5 py-3" style="letter-spacing: 2px;">VIEW MORE</a>
        </div>
        <?php endif; ?>
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
                        <div class="col-md-6 pe-md-5 animate-in animate-left testimonial-item">
                            <h5 class="text-uppercase mb-3" style="letter-spacing: 2px; font-weight: 400;"><?= htmlspecialchars($t['client_name']) ?></h5>
                            <p class="text-muted" style="line-height: 2; font-size: 0.95rem;">
                                <?= nl2br(htmlspecialchars($t['review_text'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6 text-center animate-in animate-right testimonial-item">
                            <?php if (!empty($t['image_path'])): ?>
                                <img src="assets/uploads/testimonials/<?= $t['image_path'] ?>" class="img-fluid w-100" style="object-fit: cover;" alt="<?= htmlspecialchars($t['client_name']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="col-md-6 text-center order-md-1 order-2 animate-in animate-left testimonial-item">
                            <?php if (!empty($t['image_path'])): ?>
                                <img src="assets/uploads/testimonials/<?= $t['image_path'] ?>" class="img-fluid w-100" style="object-fit: cover;" alt="<?= htmlspecialchars($t['client_name']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 ps-md-5 order-md-2 order-1 animate-in animate-right testimonial-item">
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
            <a href="https://www.facebook.com/profile.php?id=61550491520210&sk=reviews" target="_blank" rel="noopener" class="btn btn-outline-dark px-4 py-2" style="letter-spacing: 2px; text-transform: uppercase;" aria-label="Read more client reviews for Lumos Studio on Facebook">
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

  // Animate sections on scroll
  var animatedElements = document.querySelectorAll('.animate-in');
  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  animatedElements.forEach(function(el) {
    observer.observe(el);
  });
</script>
<?php 
// Footer එක Include කිරීම
require_once 'layout/footer.php'; 
?>