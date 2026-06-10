<?php
// portfolio.php

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
    
    .portfolio-item:hover img {
        transform: scale(1.03); /* Hover කරද්දී පින්තූරය සෙමින් ලොකු වේ */
    }
    
    /* Responsive Layouts */
    @media (max-width: 992px) {
        .portfolio-grid { column-count: 2; }
    }
    @media (max-width: 576px) {
        .portfolio-grid { column-count: 1; }
    }
</style>

<div class="container pb-5" style="padding-top: 140px;">
    <h1 class="page-title">PORTFOLIO</h1>
    
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