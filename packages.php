<?php

$page_title       = "Photography Packages & Pricing | Lumos Studio Sri Lanka";
$page_description = "Explore Lumos Studio's photography packages and pricing for weddings, engagements, and special events in Sri Lanka. Find the perfect package for your beautiful day.";
$page_keywords    = "Lumos Studio packages, wedding photography pricing Sri Lanka, photography packages, Lumos Studio pricing, wedding photographer packages Sri Lanka";
$page_canonical   = "https://lumos.unaux.com/packages";
require_once 'layout/header.php';

$stmt = $conn->query("SELECT * FROM packages ORDER BY id ASC");
$packages = $stmt->fetchAll();
$total = count($packages);
$middle_index = floor($total / 2);
?>

<style>
    body { background-color: #fafafa; color: #333; }

    .page-title {
        font-weight: 300;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin-bottom: 15px;
        text-align: center;
        font-family: 'Times New Roman', serif;
    }

    /* ── Grid Layout ── */
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        padding: 0 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .pricing-col {
        background: #fff;
        border: 1px solid #e8e8e8;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .pricing-col:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.10);
        z-index: 1;
    }

    .pricing-col.featured {
        background: #111;
        color: #fff;
        border-color: #111;
        box-shadow: 0 15px 45px rgba(0,0,0,0.20);
    }
    .pricing-col.featured:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 60px rgba(0,0,0,0.28);
    }

    .popular-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #c9a84c;
        color: #fff;
        font-size: 0.68rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        padding: 6px 18px;
        white-space: nowrap;
    }

    .pricing-img {
        width: 100%;
        height: 210px;
        object-fit: cover;
        filter: grayscale(15%);
        display: block;
        transition: filter 0.4s ease, transform 0.4s ease;
    }
    .pricing-col:hover .pricing-img { filter: grayscale(0%); transform: scale(1.03); }
    .pricing-col.featured .pricing-img { filter: grayscale(0%); }
    .pricing-img-wrap {
        overflow: hidden;
    }

    .pricing-header {
        padding: 25px 28px 18px;
        border-bottom: 1px solid #ebebeb;
        text-align: center;
    }
    .pricing-col.featured .pricing-header { border-bottom-color: #2a2a2a; }

    .pricing-name {
        font-family: 'Times New Roman', serif;
        font-size: 1.45rem;
        font-weight: 400;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }

    .pricing-price {
        font-size: 1.75rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: #111;
    }
    .pricing-col.featured .pricing-price { color: #c9a84c; }

    /* Features Preview Area */
    .pricing-features-preview {
        padding: 18px 28px;
        flex-grow: 1;
    }
    .feature-preview-item {
        font-size: 0.87rem;
        color: #555;
        padding: 8px 0;
        border-bottom: 1px solid #f2f2f2;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pricing-col.featured .feature-preview-item {
        color: #bbb;
        border-bottom-color: #222;
    }
    .feature-preview-item::before {
        content: "—";
        color: #c9a84c;
        flex-shrink: 0;
    }

    /* View All Button */
    .btn-view-features {
        display: block;
        width: calc(100% - 56px);
        margin: 0 28px 18px;
        border: 1px dashed #ccc;
        background: transparent;
        color: #888;
        font-size: 0.78rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 9px 0;
        text-align: center;
        cursor: pointer;
        transition: 0.3s ease;
    }
    .btn-view-features:hover {
        border-color: #999;
        color: #333;
    }
    .pricing-col.featured .btn-view-features {
        border-color: #333;
        color: #777;
    }
    .pricing-col.featured .btn-view-features:hover {
        border-color: #c9a84c;
        color: #c9a84c;
    }

    .pricing-footer { padding: 8px 28px 24px; }

    .btn-inquire {
        display: block;
        width: 100%;
        border: 1px solid #000;
        color: #000;
        background: transparent;
        border-radius: 0;
        padding: 12px 0;
        font-weight: 500;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-size: 0.8rem;
        text-align: center;
        text-decoration: none;
        transition: 0.3s ease;
    }
    .btn-inquire:hover { background: #000; color: #fff; }
    .pricing-col.featured .btn-inquire { border-color: #c9a84c; color: #c9a84c; }
    .pricing-col.featured .btn-inquire:hover { background: #c9a84c; color: #fff; }

    /* ── Features Modal ── */
    .features-modal .modal-content {
        border-radius: 0;
        border: none;
    }
    .features-modal .modal-header {
        background: #111;
        color: #fff;
        border-bottom: none;
        padding: 25px 30px;
    }
    .features-modal .modal-title {
        font-family: 'Times New Roman', serif;
        font-size: 1.4rem;
        font-weight: 400;
        letter-spacing: 2px;
    }
    .features-modal .btn-close { filter: invert(1); }
    .features-modal .modal-body { padding: 30px; }

    .modal-price-tag {
        font-size: 1.5rem;
        font-weight: 600;
        color: #c9a84c;
        letter-spacing: 1px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .modal-feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .modal-feature-list li {
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.95rem;
        color: #444;
        line-height: 1.5;
    }
    .modal-feature-list li:last-child { border-bottom: none; }
    .modal-feature-list li .feature-icon {
        color: #c9a84c;
        font-size: 1rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .modal-inquire-btn {
        display: block;
        width: 100%;
        border: 1px solid #000;
        color: #000;
        background: transparent;
        border-radius: 0;
        padding: 13px 0;
        font-weight: 500;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-size: 0.82rem;
        text-align: center;
        text-decoration: none;
        transition: 0.3s ease;
        margin-top: 25px;
    }
    .modal-inquire-btn:hover { background: #000; color: #fff; }

    @media (max-width: 900px) {
        .pricing-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .pricing-grid { grid-template-columns: 1fr; gap: 20px; }
    }
</style>

<div class="container-fluid pb-5" style="padding-top: 140px;">
    <h1 class="page-title">INVESTMENT &amp; PACKAGES<br><small style="font-size:0.5em; letter-spacing:3px; opacity:0.6;">Lumos Studio &mdash; Sri Lanka</small></h1>
    <p class="text-center text-muted mb-5" style="letter-spacing: 1px; font-weight: 300;">
        Choose the perfect package to preserve your beautiful day forever.
    </p>

    <?php if($total > 0): ?>
        <div class="pricing-grid">
            <?php foreach ($packages as $i => $p): ?>
                <?php
                    $is_featured = ($i === $middle_index);
                    $features = array_filter(array_map('trim', explode("\n", $p['description'])));
                    $preview_features = array_slice($features, 0, 3);
                    $modal_id = "featuresModal" . $p['id'];
                ?>
                <div class="pricing-col <?= $is_featured ? 'featured' : '' ?>">

                    <?php if($is_featured): ?>
                        <div class="popular-badge">Most Popular</div>
                    <?php endif; ?>

                    <!-- Image -->
                    <?php if($p['package_image']): ?>
                        <img src="assets/uploads/packages/<?= htmlspecialchars($p['package_image']) ?>"
                             class="pricing-img"
                             alt="<?= htmlspecialchars($p['package_name']) ?>">
                    <?php else: ?>
                        <div class="pricing-img bg-light d-flex align-items-center justify-content-center">
                            <span class="text-muted" style="font-size:0.85rem;">No Image</span>
                        </div>
                    <?php endif; ?>

                    <!-- Header -->
                    <div class="pricing-header">
                        <div class="pricing-name"><?= htmlspecialchars($p['package_name']) ?></div>
                        <div class="pricing-price"><?= htmlspecialchars($p['price']) ?></div>
                    </div>

                    <!-- Features Preview (first 3 only) -->
                    <div class="pricing-features-preview">
                        <?php foreach($preview_features as $f): ?>
                            <div class="feature-preview-item"><?= htmlspecialchars($f) ?></div>
                        <?php endforeach; ?>
                    </div>

                    <!-- View All Features Button -->
                    <?php if(count($features) > 0): ?>
                        <button class="btn-view-features"
                                data-bs-toggle="modal"
                                data-bs-target="#<?= $modal_id ?>">
                            + View All Features
                        </button>
                    <?php endif; ?>

                    <!-- Inquire Button -->
                    <div class="pricing-footer">
                        <a href="contact?package=<?= urlencode($p['package_name']) ?>"
                           class="btn-inquire">Inquire Now</a>
                    </div>
                </div>

                <!-- Features Modal -->
                <div class="modal fade features-modal" id="<?= $modal_id ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= htmlspecialchars($p['package_name']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="modal-price-tag"><?= htmlspecialchars($p['price']) ?></div>
                                <ul class="modal-feature-list">
                                    <?php foreach($features as $f): ?>
                                        <li>
                                            <span class="feature-icon">✦</span>
                                            <?= htmlspecialchars($f) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="contact?package=<?= urlencode($p['package_name']) ?>"
                                   class="modal-inquire-btn">Inquire Now</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <p class="text-muted">No packages have been added yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'layout/footer.php'; ?>