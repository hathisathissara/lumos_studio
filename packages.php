<?php

require_once 'layout/header.php';

$stmt = $conn->query("SELECT * FROM packages ORDER BY id ASC");
$packages = $stmt->fetchAll();
$total = count($packages);
$middle_index = floor($total / 2);
?>

<style>
    body { background-color: #ffffff; color: #333; }

    .page-title {
        font-weight: 300;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin-bottom: 15px;
        text-align: center;
        font-family: 'Times New Roman', serif;
    }

    .pricing-wrapper {
        display: flex;
        align-items: stretch;
        justify-content: center;
        gap: 0;
        flex-wrap: nowrap;
        overflow-x: auto;
    }

    .pricing-col {
        flex: 1;
        min-width: 260px;
        max-width: 340px;
        border: 1px solid #e0e0e0;
        background: #fff;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.3s ease;
        position: relative;
    }
    .pricing-col:hover {
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        z-index: 1;
    }

    .pricing-col.featured {
        background: #111;
        color: #fff;
        border-color: #111;
        transform: scaleY(1.03);
        z-index: 2;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    }

    .popular-badge {
        position: absolute;
        top: -1px;
        left: 50%;
        transform: translateX(-50%);
        background: #c9a84c;
        color: #fff;
        font-size: 0.7rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        padding: 5px 20px;
        white-space: nowrap;
    }

    .pricing-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        filter: grayscale(15%);
        display: block;
    }
    .pricing-col.featured .pricing-img { filter: grayscale(0%); }

    .pricing-header {
        padding: 30px 30px 20px;
        border-bottom: 1px solid #e0e0e0;
        text-align: center;
    }
    .pricing-col.featured .pricing-header { border-bottom-color: #333; }

    .pricing-name {
        font-family: 'Times New Roman', serif;
        font-size: 1.5rem;
        font-weight: 400;
        letter-spacing: 2px;
        margin-bottom: 12px;
    }

    .pricing-price {
        font-size: 1.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: #111;
    }
    .pricing-col.featured .pricing-price { color: #c9a84c; }

    /* Features Preview Area */
    .pricing-features-preview {
        padding: 20px 30px;
        flex-grow: 1;
    }
    .feature-preview-item {
        font-size: 0.88rem;
        color: #555;
        padding: 7px 0;
        border-bottom: 1px solid #f0f0f0;
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
        color: #999;
        flex-shrink: 0;
    }
    .pricing-col.featured .feature-preview-item::before { color: #c9a84c; }

    /* View All Button */
    .btn-view-features {
        display: block;
        width: calc(100% - 60px);
        margin: 0 30px 20px;
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
        color: #888;
    }
    .pricing-col.featured .btn-view-features:hover {
        border-color: #c9a84c;
        color: #c9a84c;
    }

    .pricing-footer { padding: 10px 30px 25px; }

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

    @media (max-width: 768px) {
        .pricing-wrapper { flex-direction: column; align-items: center; }
        .pricing-col { max-width: 100%; width: 100%; }
        .pricing-col.featured { transform: none; order: -1; }
    }
</style>

<div class="container-fluid pb-5" style="padding-top: 140px;">
    <h1 class="page-title">INVESTMENT & PACKAGES</h1>
    <p class="text-center text-muted mb-5" style="letter-spacing: 1px; font-weight: 300;">
        Choose the perfect package to preserve your beautiful day forever.
    </p>

    <?php if($total > 0): ?>
        <div class="pricing-wrapper px-3">
            <?php foreach ($packages as $i => $p): ?>
                <?php
                    $is_featured = ($i === $middle_index);
                    $features = array_filter(array_map('trim', explode("\n", $p['description'])));
                    $preview_features = array_slice($features, 0, 3); // පළමු features 3 පෙන්වයි
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
                        <a href="contact.php?package=<?= urlencode($p['package_name']) ?>"
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
                                <a href="contact.php?package=<?= urlencode($p['package_name']) ?>"
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