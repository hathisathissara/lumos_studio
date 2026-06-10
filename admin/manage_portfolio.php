<?php
require_once 'layout/header.php';

$upload_dir = "../assets/uploads/portfolio/";
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

// AJAX check
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Add portfolio images
if (isset($_POST['add_portfolio'])) {
    $title    = $_POST['title'];
    $category = $_POST['category'];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = uniqid('port_') . "_" . $key . "_" . $_FILES['images']['name'][$key];
            if (move_uploaded_file($tmp_name, $upload_dir . $image_name)) {
                $stmt = $conn->prepare("INSERT INTO portfolio (title, category, image_path) VALUES (?, ?, ?)");
                $stmt->execute([$title, $category, $image_name]);
            }
        }
    }
    if ($is_ajax) { echo "success"; exit(); }
    else { echo "<script>alert('Images Added Successfully!'); window.location='portfolio';</script>"; exit(); }
}

// Delete image
if (isset($_GET['delete'])) {
    $id   = $_GET['delete'];
    $stmt = $conn->prepare("SELECT image_path FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $img  = $stmt->fetchColumn();
    if ($img && file_exists($upload_dir . $img)) unlink($upload_dir . $img);
    $stmt = $conn->prepare("DELETE FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='portfolio';</script>";
    exit();
}

$items = $conn->query("SELECT * FROM portfolio ORDER BY id DESC")->fetchAll();

$categories = [];
foreach ($items as $item) {
    if (!in_array($item['category'], $categories)) $categories[] = $item['category'];
}
?>

<style>
.portfolio-grid {
    columns: 2; column-gap: 0.9rem;
}
@media (min-width: 576px)  { .portfolio-grid { columns: 3; } }
@media (min-width: 768px)  { .portfolio-grid { columns: 4; } }
@media (min-width: 1200px) { .portfolio-grid { columns: 5; } }

.port-item {
    break-inside: avoid; margin-bottom: 0.9rem;
    border-radius: var(--radius-sm); overflow: hidden;
    position: relative; cursor: pointer;
    display: block;
}
.port-item img {
    width: 100%; display: block;
    transition: transform 0.35s ease;
    border-radius: var(--radius-sm);
}
.port-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(8,13,26,0.9) 0%, transparent 55%);
    opacity: 0; transition: opacity 0.25s ease;
    display: flex; flex-direction: column;
    justify-content: flex-end; padding: 0.9rem;
    border-radius: var(--radius-sm);
}
.port-item:hover img { transform: scale(1.04); }
.port-item:hover .port-overlay { opacity: 1; }
.port-cat { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.07em; color: var(--accent-2); font-weight: 600; }
.port-title { font-size: 0.82rem; color: var(--text); font-weight: 600; margin-top: 2px; }
.port-delete {
    position: absolute; top: 8px; right: 8px;
    width: 28px; height: 28px;
    background: rgba(244,63,94,0.85);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    color: white; font-size: 0.75rem;
    opacity: 0; transition: opacity 0.25s ease;
    text-decoration: none;
    backdrop-filter: blur(4px);
}
.port-item:hover .port-delete { opacity: 1; }

/* Progress Modal */
#uploadProgressModal { background: rgba(0,0,0,0.8) !important; }

.filter-tab {
    background: var(--surface-2); border: 1px solid var(--border-soft);
    border-radius: 20px; padding: 4px 14px;
    font-size: 0.78rem; font-weight: 500; color: var(--text-muted);
    cursor: pointer; transition: all 0.17s ease; white-space: nowrap;
}
.filter-tab:hover, .filter-tab.active {
    background: var(--accent); border-color: var(--accent);
    color: white; box-shadow: 0 3px 10px var(--accent-glow);
}
</style>

<!-- Page Header -->
<div class="section-header">
    <div>
        <h3 class="sh-title">Portfolio</h3>
        <div class="sh-sub"><?= count($items) ?> photos across your gallery</div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus me-2"></i>Add Photos
    </button>
</div>

<!-- Category filter tabs -->
<?php if (count($categories) > 0): ?>
<div class="d-flex gap-2 flex-wrap mb-4" id="filterTabs">
    <button class="filter-tab active" data-cat="all">All</button>
    <?php foreach ($categories as $cat): ?>
        <button class="filter-tab" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Portfolio Grid -->
<?php if (count($items) > 0): ?>
<div class="portfolio-grid" id="portfolioGrid">
    <?php foreach ($items as $item): ?>
    <div class="port-item" data-cat="<?= htmlspecialchars($item['category']) ?>">
        <img src="../assets/uploads/portfolio/<?= $item['image_path'] ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
        <div class="port-overlay">
            <div class="port-cat"><?= htmlspecialchars($item['category']) ?></div>
            <div class="port-title"><?= htmlspecialchars($item['title']) ?></div>
        </div>
        <a href="portfolio?delete=<?= $item['id'] ?>" class="port-delete" onclick="return confirm('Delete this photo?')" title="Delete">
            <i class="fa-solid fa-trash"></i>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body text-center py-5" style="color:var(--text-muted);">
        <i class="fa-solid fa-images" style="font-size:3rem; opacity:0.2; display:block; margin-bottom:1rem;"></i>
        <h5 style="color:var(--text-soft);">No photos yet</h5>
        <p style="font-size:0.88rem;">Click <strong>Add Photos</strong> to upload your first portfolio images.</p>
    </div>
</div>
<?php endif; ?>

<!-- Upload Progress Modal -->
<div class="modal fade" id="uploadProgressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
        <div class="modal-content text-center">
            <div class="modal-body py-4 px-4">
                <div class="mb-3">
                    <div style="width:52px; height:52px; background:rgba(99,102,241,0.15); border-radius:14px; display:inline-flex; align-items:center; justify-content:center; font-size:1.4rem; color:var(--accent-2);">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                </div>
                <h6 id="progressTitle" style="font-weight:600; margin-bottom:0.5rem;">Processing Images...</h6>
                <p id="progressText" style="font-size:0.82rem; color:var(--text-muted); margin-bottom:1rem;">Initializing compression...</p>
                <div class="progress" style="height:8px; border-radius:20px; background:var(--surface-3);">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%; background:linear-gradient(90deg, var(--accent), var(--accent-2)); border-radius:20px; transition:width 0.3s ease;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Portfolio Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" id="addPortfolioForm" class="modal-content">
            <input type="hidden" name="add_portfolio" value="1">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-images me-2" style="color:var(--accent-2);"></i>Add to Portfolio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Kasun & Amali">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="Wedding">Wedding</option>
                        <option value="Portrait">Portrait</option>
                        <option value="Event">Event</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Images <span style="color:var(--text-muted); font-weight:400;">(Max 20)</span></label>
                    <input type="file" name="images[]" id="portfolio_images" class="form-control" multiple required>
                    <div class="form-text">You can select up to 20 images at once.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.min.js"></script>
<script>
// Category filter
document.querySelectorAll('.filter-tab').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.cat;
        document.querySelectorAll('.port-item').forEach(item => {
            item.style.display = (cat === 'all' || item.dataset.cat === cat) ? '' : 'none';
        });
    });
});

// Upload with compression
const form = document.getElementById('addPortfolioForm');
form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const imagesInput = document.getElementById('portfolio_images');
    const files = imagesInput.files;
    if (files.length === 0) { form.submit(); return; }
    if (files.length > 20) { alert('Maximum 20 images allowed!'); return; }

    const addModal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
    if (addModal) addModal.hide();
    await new Promise(r => setTimeout(r, 400));

    const progressModal = new bootstrap.Modal(document.getElementById('uploadProgressModal'), { backdrop: 'static' });
    progressModal.show();
    const progressText = document.getElementById('progressText');
    const progressBar  = document.getElementById('progressBar');
    const options = { maxSizeMB: 1, maxWidthOrHeight: 1920, useWebWorker: true };
    const compressedFiles = [];

    for (let i = 0; i < files.length; i++) {
        progressText.textContent = `Compressing image ${i + 1} of ${files.length}...`;
        progressBar.style.width = `${Math.round((i / files.length) * 50)}%`;
        try { compressedFiles.push(await imageCompression(files[i], options)); }
        catch (err) { compressedFiles.push(files[i]); }
    }

    progressText.textContent = 'Uploading...';
    progressBar.style.width = '55%';

    const formData = new FormData(form);
    formData.delete('images[]');
    compressedFiles.forEach((f, i) => formData.append('images[]', f, files[i].name));

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'manage_portfolio.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            progressBar.style.width = `${Math.round(50 + (event.loaded / event.total) * 50)}%`;
            progressText.textContent = `Uploading ${Math.round(event.loaded/1024/1024)}MB / ${Math.round(event.total/1024/1024)}MB`;
        }
    };
    xhr.onload = function() {
        progressModal.hide();
        if (xhr.status === 200 && xhr.responseText.includes('success')) {
            alert('Images uploaded successfully!');
            window.location = 'portfolio';
        } else { alert('Upload failed. Please try again.'); }
    };
    xhr.onerror = function() { progressModal.hide(); alert('Upload error.'); };
    xhr.send(formData);
});
</script>

<?php if (!$is_ajax) require_once 'layout/footer.php'; ?>