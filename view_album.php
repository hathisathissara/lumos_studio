<?php
require_once './config/config.php';

$album_slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$album_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($album_slug) {
    $stmt = $conn->prepare("SELECT * FROM weddings WHERE slug = ?");
    $stmt->execute([$album_slug]);
} else {
    $stmt = $conn->prepare("SELECT * FROM weddings WHERE id = ?");
    $stmt->execute([$album_id]);
}
$album = $stmt->fetch();

if (!$album) {
    require_once 'layout/header.php';
    echo "<div class='container py-5 text-center mt-5'><h2>Album Not Found!</h2><a href='albums' class='btn btn-dark mt-3'>Back to Albums</a></div>";
    require_once 'layout/footer.php';
    exit();
}

$album_id = $album['id'];

// Setup Dynamic SEO Meta Tags
$page_title = htmlspecialchars($album['title'] . " | Lumos Studio");
$page_description = "Explore the beautiful " . strtolower($album['category']) . " of " . $album['title'] . " by Lumos Studio. Professional portrait and lifestyle photography in Sri Lanka.";
if (!empty($album['description'])) {
    $page_description = strip_tags($album['description']);
}
$page_keywords = $album['title'] . ", " . $album['category'] . ", Lumos Studio, Photography, Sri Lanka";
$page_canonical = "https://lumos.unaux.com/albums/" . (!empty($album['slug']) ? $album['slug'] : $album_id);

if (!empty($album['cover_image'])) {
    $page_og_image = "https://lumos.unaux.com/assets/uploads/weddings/" . $album['cover_image'];
}

// Now include header
require_once 'layout/header.php';
?>

<style>
    body { background-color: #ffffff; color: #333; }
    .album-header {
        text-align: center;
        padding: 60px 0 40px;
        font-family: 'Times New Roman', serif;
    }
    .album-title {
        font-weight: 300;
        letter-spacing: 3px;
        text-transform: uppercase;
    }
    .album-category {
        font-size: 0.9rem;
        letter-spacing: 5px;
        text-transform: uppercase;
        color: #777;
    }

    /* Masonry Style Grid for Local Images */
    .image-grid {
        display: column;
        column-count: 3; /* තීරු 3කට බෙදීම */
        column-gap: 15px;
    }
    .image-grid img {
        width: 100%;
        margin-bottom: 15px;
        border-radius: 3px;
        transition: transform 0.3s ease;
    }
    .image-grid img:hover {
        transform: scale(1.02);
    }
    
    /* Mobile view for Grid */
    @media (max-width: 768px) {
        .image-grid { column-count: 2; }
    }
    @media (max-width: 576px) {
        .image-grid { column-count: 1; }
    }

    /* FB Iframe container */
    .fb-container {
        display: flex;
        justify-content: center;
        width: 100%;
        overflow: hidden;
    }
</style>

<div class="container mt-4 mb-5">
    <!-- Album Title Section -->
    <div class="album-header">
        <h1 class="album-title"><?= htmlspecialchars($album['title']) ?> &ndash; <?= htmlspecialchars(strtoupper($album['category'])) ?></h1>
        <?php if (!empty($album['description'])): ?>
            <p class="mt-4 text-muted" style="max-width: 800px; margin: 0 auto; line-height: 1.8;"><?= nl2br(htmlspecialchars($album['description'])) ?></p>
        <?php else: ?>
            <div class="album-category mt-2"><?= htmlspecialchars($album['category']) ?></div>
        <?php endif; ?>
    </div>

    <!-- Album Content -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <?php if ($album['is_embed'] == 1): ?>
                <!-- Facebook Embed පෙන්වීම -->
                <div class="fb-container">
                    <iframe src="<?= htmlspecialchars($album['fb_embed_code']) ?>" 
                            width="500" 
                            height="800" 
                            style="border:none;overflow:hidden; max-width: 100%;" 
                            scrolling="no" 
                            frameborder="0" 
                            allowfullscreen="true" 
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                </div>
                <div class="text-center mt-4">
                    <a href="<?= htmlspecialchars($album['fb_embed_code']) ?>" target="_blank" class="btn btn-outline-dark">View Original on Facebook</a>
                </div>

            <?php else: ?>
                <!-- Local Upload කළ පින්තූර පෙන්වීම -->
                <?php
                $img_stmt = $conn->prepare("SELECT * FROM wedding_images WHERE wedding_id = ?");
                $img_stmt->execute([$album_id]);
                $images = $img_stmt->fetchAll();
                ?>

                <?php if (count($images) > 0): ?>
                    <div class="image-grid">
                        <?php foreach ($images as $img): ?>
                            <a data-fslightbox="gallery" href="assets/uploads/weddings/<?= htmlspecialchars($img['image_path']) ?>">
                                <img src="assets/uploads/weddings/<?= htmlspecialchars($img['image_path']) ?>" alt="<?= htmlspecialchars($album['title']) ?> <?= htmlspecialchars($album['category']) ?> by Lumos Studio Sri Lanka">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">No images uploaded for this album yet.</p>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>

    <div class="text-center mt-5">
        <a href="albums" class="btn btn-dark px-4 py-2">&larr; Back to Albums</a>
    </div>
</div>

<!-- fsLightbox JS for image gallery -->
<script src="https://cdn.jsdelivr.net/npm/fslightbox@3.4.1/index.min.js"></script>

<?php require_once 'layout/footer.php'; ?>