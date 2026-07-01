<?php
// portfolio.php

$page_title       = "Photography Portfolio | Lumos Studio Sri Lanka";
$page_description = "Explore Lumos Studio's photography portfolio showcasing wedding, portrait, and event photography from across Sri Lanka. Every image is a timeless story.";
$page_keywords    = "Lumos Studio portfolio, wedding photography portfolio, portrait photography Sri Lanka, Lumos Studio gallery, professional photography portfolio";
$page_canonical   = "https://lumos.unaux.com/portfolio";
require_once 'layout/header.php';

// Database එකෙන් සියලුම Portfolio පින්තූර ලබා ගැනීම
$stmt = $conn->query("SELECT * FROM portfolio ORDER BY id DESC");
$portfolio = $stmt->fetchAll();
?>

<style>
    body {
        background-color: #ffffff;
        color: #333;
    }
    .page-title {
        font-weight: 300;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin-bottom: 30px;
        text-align: center;
        font-family: 'Times New Roman', serif;
    }
    
    /* Filter Bar Styling */
    .filter-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 50px;
    }
    .filter-btn {
        background: transparent;
        border: none;
        color: #888;
        font-size: 0.85rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 5px 15px;
        padding-bottom: 5px;
        border-bottom: 1px solid transparent;
        transition: 0.3s ease;
        cursor: pointer;
    }
    .filter-btn.active, .filter-btn:hover {
        color: #000000;
        border-bottom: 1px solid #000000;
    }
    
    /* Masonry Grid Setup */
    .portfolio-grid {
        column-count: 3; /* Desktop වලදී තීරු 3ක් පෙන්වයි */
        column-gap: 15px;
        width: 100%;
    }
    
    .portfolio-item {
        width: 100%;
        margin-bottom: 15px;
        break-inside: avoid;
        display: inline-block;
        position: relative;
        overflow: hidden;
        transition: opacity 0.4s ease, transform 0.4s ease;
        opacity: 1;
    }
    
    .portfolio-item img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }

    .portfolio-item .portfolio-caption {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background: rgba(0, 0, 0, 0.65);
        color: #ffffff;
        padding: 16px;
        opacity: 0;
        transform: scale(0.98);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .portfolio-item:hover .portfolio-caption {
        opacity: 1;
        transform: scale(1);
    }
    
    .portfolio-item:hover img {
        transform: scale(1.03); /* Hover කරද්දී පින්තූරය සෙමින් ලොකු වේ */
    }
    
    .portfolio-item .portfolio-caption .caption-category {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 3px;
        margin-bottom: 10px;
        font-size: 0.85rem;
        color: #f7d794;
    }

    .portfolio-item .portfolio-caption .caption-title {
        font-size: 1rem;
        font-weight: 500;
    }
    
    /* Responsive Layouts */
    @media (max-width: 992px) {
        .portfolio-grid { column-count: 2; }
    }
    @media (max-width: 576px) {
        .portfolio-grid { column-count: 1; }
    }

    /* Page Hero Section */
    .page-hero {
        position: relative;
        height: 100vh;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: 0;
    }
    .page-hero img.hero-bg-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(20%) brightness(60%);
        z-index: -1;
    }
    .page-hero .hero-overlay {
        text-align: center;
        color: #fff;
        padding: 0 20px;
    }
    .hero-page-title {
        font-family: 'Times New Roman', serif;
        font-weight: 300;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin: 0;
        font-size: 2.5rem;
    }
    @media (max-width: 767.98px) {
        .hero-page-title {
            font-size: 1.8rem;
        }
    }
</style>

<!-- Hero Section -->
<div class="page-hero">
    <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?q=80&w=1920&auto=format&fit=crop" class="hero-bg-img" alt="Portfolio Lumos Studio">
    <div class="hero-overlay">
        <h1 class="hero-page-title">PORTFOLIO<br><small style="font-size:0.5em; letter-spacing:3px; opacity:0.8;">Lumos Studio</small></h1>
    </div>
</div>

<div class="container pb-5" style="padding-top: 60px;">
    
    <!-- Filter Buttons (පිටුව reload නොවී වැඩ කරන filter එක) -->
    <div class="filter-container">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="Wedding">Wedding</button>
        <button class="filter-btn" data-filter="Portrait">Portrait</button>
        <button class="filter-btn" data-filter="Event">Event</button>
    </div>

    <!-- Portfolio Masonry Grid -->
    <div class="portfolio-grid">
        <?php if(count($portfolio) > 0): ?>
            <?php foreach ($portfolio as $p): ?>
                <div class="portfolio-item" data-category="<?= htmlspecialchars($p['category']) ?>">
                    <a data-fslightbox="gallery" href="assets/uploads/portfolio/<?= htmlspecialchars($p['image_path']) ?>">
                        <img src="assets/uploads/portfolio/<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" style="cursor: pointer;">
                        <div class="portfolio-caption">
                            <div class="caption-category"><?= htmlspecialchars($p['category']) ?></div>
                            <div class="caption-title"><?= htmlspecialchars($p['title']) ?></div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No portfolio items have been added yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript Filter Logic -->
<script>
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Active Class එක වෙනස් කිරීම
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filterValue = this.getAttribute('data-filter');
        const items = document.querySelectorAll('.portfolio-item');
        
        items.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            
            if (filterValue === 'all' || itemCategory === filterValue) {
                // පෙන්වීම සඳහා Animation එකක් සහිතව display වෙනස් කිරීම
                item.style.display = 'inline-block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 10);
            } else {
                // සැඟවීම
                item.style.opacity = '0';
                item.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 400); // css transition කාලය සමග සමපාත වේ
            }
        });
    });
});
</script>

<!-- fsLightbox JS for image gallery -->
<script src="https://cdn.jsdelivr.net/npm/fslightbox@3.4.1/index.min.js"></script>

<?php require_once 'layout/footer.php'; ?>