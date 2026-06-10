<?php

require_once 'layout/header.php';

// Database එකෙන් සියලුම ඇල්බම් ලබා ගැනීම
$stmt = $conn->query("SELECT * FROM weddings ORDER BY id DESC");
$weddings = $stmt->fetchAll();
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
        margin-bottom: 50px;
        text-align: center;
        font-family: 'Times New Roman', serif; /* අලංකාර අකුරු විලාසයක් සඳහා */
    }
    
    /* Album Box Styling */
    .album-box {
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        cursor: pointer;
        display: block; /* ලින්ක් එකක් ලෙස ක්‍රියා කිරීමට */
    }
    
    .album-img {
        width: 100%;
        height: 350px; /* පින්තූරවල උස එකම මට්ටමකට තබා ගැනීමට */
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    
    .album-box:hover .album-img {
        transform: scale(1.05); /* මවුස් එක ගෙනිච්චම පින්තූරය ටිකක් ලොකු වේ */
    }
    
    /* Dark Overlay */
    .album-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5); /* කළු පැහැති පාරදෘශ්‍ය පසුබිමක් */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.5s ease;
        color: #ffffff;
        text-align: center;
        text-decoration: none;
    }
    
    .album-box:hover .album-overlay {
        opacity: 1; /* මවුස් එක ගෙනිච්චම overlay එක පෙනේ */
    }
    
    .album-title {
        font-size: 1.5rem;
        font-weight: 400;
        font-family: 'Times New Roman', serif;
        margin-bottom: 5px;
    }
    
    .album-category {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 3px;
        font-weight: 300;
    }
</style>

<div class="container pb-5" style="padding-top: 140px;">
    <h1 class="page-title">ALBUMS</h1>

    <div class="row">
        <?php if(count($weddings) > 0): ?>
            <?php foreach ($weddings as $w): ?>
                <div class="col-md-4 col-sm-6">
                    <!-- ඇල්බමය ක්ලික් කළ විට view_album.php පිටුවට යොමු වේ -->
                    <a href="view_album?id=<?= $w['id'] ?>" class="album-box">
                        <img src="assets/uploads/weddings/<?= $w['cover_image'] ?>" alt="<?= htmlspecialchars($w['title']) ?>" class="album-img">
                        <div class="album-overlay">
                            <h3 class="album-title"><?= htmlspecialchars($w['title']) ?></h3>
                            <span class="album-category"><?= htmlspecialchars($w['category']) ?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No albums have been added yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>