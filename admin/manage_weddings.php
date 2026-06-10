<?php
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if (!$is_ajax) {
    require_once 'layout/header.php';
} else {
    require_once '../config/config.php';
}

$upload_dir = "../assets/uploads/weddings/";
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

function extractFbSrc($input) {
    if (preg_match('/src="([^"]+)"/', $input, $match)) return $match[1];
    return $input;
}

// Add Wedding
if (isset($_POST['add_wedding'])) {
    $title     = $_POST['title'];
    $category  = $_POST['category'];
    $is_embed  = $_POST['is_embed'];
    $cover_image = "";
    if ($_FILES['cover_image']['name']) {
        $cover_image = uniqid('cover_') . "_" . $_FILES['cover_image']['name'];
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_dir . $cover_image);
    }
    if ($is_embed == '1') {
        $fb_code = extractFbSrc($_POST['fb_code']);
        $stmt = $conn->prepare("INSERT INTO weddings (title, category, cover_image, fb_embed_code, is_embed) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$title, $category, $cover_image, $fb_code]);
    } else {
        $stmt = $conn->prepare("INSERT INTO weddings (title, category, cover_image, is_embed) VALUES (?, ?, ?, 0)");
        $stmt->execute([$title, $category, $cover_image]);
        $wedding_id = $conn->lastInsertId();
        if (!empty($_FILES['album_images']['name'][0])) {
            foreach ($_FILES['album_images']['tmp_name'] as $key => $tmp_name) {
                $img_name = uniqid('img_') . "_" . $key . "_" . $_FILES['album_images']['name'][$key];
                if (move_uploaded_file($tmp_name, $upload_dir . $img_name)) {
                    $stmt_img = $conn->prepare("INSERT INTO wedding_images (wedding_id, image_path) VALUES (?, ?)");
                    $stmt_img->execute([$wedding_id, $img_name]);
                }
            }
        }
    }
    if ($is_ajax) { echo "success"; exit(); }
    echo "<script>alert('Album Added Successfully!'); window.location='weddings';</script>"; exit();
}

// Delete Wedding
if (isset($_GET['delete'])) {
    $id   = $_GET['delete'];
    $stmt = $conn->prepare("SELECT cover_image FROM weddings WHERE id = ?");
    $stmt->execute([$id]);
    $wedding = $stmt->fetch();
    if ($wedding && !empty($wedding['cover_image'])) {
        $cover_path = $upload_dir . $wedding['cover_image'];
        if (file_exists($cover_path)) unlink($cover_path);
    }
    $stmt_imgs = $conn->prepare("SELECT image_path FROM wedding_images WHERE wedding_id = ?");
    $stmt_imgs->execute([$id]);
    foreach ($stmt_imgs->fetchAll() as $img) {
        $img_path = $upload_dir . $img['image_path'];
        if (file_exists($img_path)) unlink($img_path);
    }
    $conn->prepare("DELETE FROM wedding_images WHERE wedding_id = ?")->execute([$id]);
    $conn->prepare("DELETE FROM weddings WHERE id = ?")->execute([$id]);
    echo "<script>window.location='weddings';</script>"; exit();
}

$weddings = $conn->query("SELECT * FROM weddings ORDER BY id DESC")->fetchAll();
?>

<style>
.wedding-card {
    background: linear-gradient(145deg, var(--surface), var(--surface-2));
    border: 1px solid var(--border-soft);
    border-radius: var(--radius);
    overflow: hidden;
    transition: all 0.25s ease;
    display: flex; flex-direction: column; height: 100%;
}
.wedding-card:hover {
    transform: translateY(-5px);
    border-color: var(--border);
    box-shadow: 0 16px 40px rgba(0,0,0,0.4), var(--shadow-glow);
}
.wedding-cover {
    height: 190px; overflow: hidden; position: relative;
    background: var(--surface-3); flex-shrink: 0;
}
.wedding-cover img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
.wedding-card:hover .wedding-cover img { transform: scale(1.06); }
.wedding-cover-placeholder {
    height: 100%; display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); flex-direction: column; gap: 8px;
}
.wedding-cover-placeholder i { font-size: 2.5rem; opacity: 0.25; }
.wedding-type-badge {
    position: absolute; top: 10px; left: 10px;
    font-size: 0.7rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;
    letter-spacing: 0.03em;
}
.badge-local { background: rgba(16,185,129,0.85); color: white; }
.badge-fb    { background: rgba(99,102,241,0.85); color: white; }
.wedding-body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; }
.wedding-category {
    font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.08em;
    color: var(--accent-2); font-weight: 600; margin-bottom: 4px;
}
.wedding-title { font-size: 0.98rem; font-weight: 700; color: var(--text); flex: 1; margin-bottom: 1rem; }
.wedding-actions { display: flex; gap: 0.5rem; }
.wedding-actions .btn { flex: 1; font-size: 0.8rem; padding: 0.42rem; }
</style>

<!-- Page Header -->
<div class="section-header">
    <div>
        <h3 class="sh-title">Wedding Albums</h3>
        <div class="sh-sub"><?= count($weddings) ?> albums in your collection</div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWeddingModal">
        <i class="fa-solid fa-plus me-2"></i>Add Album
    </button>
</div>

<!-- Albums Grid -->
<?php if (count($weddings) > 0): ?>
<div class="row g-3">
    <?php foreach ($weddings as $w): ?>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="wedding-card">
            <div class="wedding-cover">
                <?php if ($w['cover_image']): ?>
                    <img src="../assets/uploads/weddings/<?= $w['cover_image'] ?>" alt="<?= htmlspecialchars($w['title']) ?>">
                <?php else: ?>
                    <div class="wedding-cover-placeholder">
                        <i class="fa-solid fa-image"></i>
                        <span style="font-size:0.73rem;">No Cover</span>
                    </div>
                <?php endif; ?>
                <span class="wedding-type-badge <?= $w['is_embed'] ? 'badge-fb' : 'badge-local' ?>">
                    <i class="fa-solid <?= $w['is_embed'] ? 'fa-brands fa-facebook' : 'fa-folder-open' ?> me-1"></i>
                    <?= $w['is_embed'] ? 'FB Embed' : 'Local Gallery' ?>
                </span>
            </div>
            <div class="wedding-body">
                <div class="wedding-category"><?= htmlspecialchars($w['category']) ?></div>
                <div class="wedding-title"><?= htmlspecialchars($w['title']) ?></div>
                <div class="wedding-actions">
                    <a href="weddings?delete=<?= $w['id'] ?>" class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Delete this album and all its images?')">
                        <i class="fa-solid fa-trash me-1"></i>Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body text-center py-5" style="color:var(--text-muted);">
        <i class="fa-solid fa-ring" style="font-size:3rem; opacity:0.2; display:block; margin-bottom:1rem;"></i>
        <h5 style="color:var(--text-soft);">No albums yet</h5>
        <p style="font-size:0.88rem;">Click <strong>Add Album</strong> to create your first wedding gallery.</p>
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
                <h6 id="progressTitle" style="font-weight:600; margin-bottom:0.5rem;">Processing Album...</h6>
                <p id="progressText" style="font-size:0.82rem; color:var(--text-muted); margin-bottom:1rem;">Initializing...</p>
                <div class="progress" style="height:8px; border-radius:20px; background:var(--surface-3);">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%; background:linear-gradient(90deg, var(--accent), var(--accent-2)); border-radius:20px; transition:width 0.3s ease;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Wedding Modal -->
<div class="modal fade" id="addWeddingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" id="addWeddingForm" class="modal-content">
            <input type="hidden" name="add_wedding" value="1">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-ring me-2" style="color:var(--accent-2);"></i>Add New Album</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Album Title</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Kasun & Nilu Wedding">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Wedding">Wedding</option>
                        <option value="Bridal Shoot">Bridal Shoot</option>
                        <option value="Engagement">Engagement</option>
                        <option value="Pre Shoot">Pre Shoot</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Album Type</label>
                    <select name="is_embed" id="typeSelect" class="form-select" onchange="toggleType()">
                        <option value="0">Local Album (Upload Images)</option>
                        <option value="1">Facebook Embed</option>
                    </select>
                </div>
                <div id="fbField" class="mb-3 d-none">
                    <label class="form-label">FB Embed Code or Link</label>
                    <textarea name="fb_code" class="form-control" rows="3" placeholder="Paste FB iframe code or link here..."></textarea>
                </div>
                <div id="localField" class="mb-3">
                    <label class="form-label">Album Images <span style="color:var(--text-muted); font-weight:400;">(Max 20)</span></label>
                    <input type="file" name="album_images[]" id="album_images" class="form-control" multiple>
                    <div class="form-text">Select up to 20 images at once.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Save Album</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.min.js"></script>
<script>
function toggleType() {
    const v = document.getElementById('typeSelect').value;
    document.getElementById('fbField').classList.toggle('d-none', v !== '1');
    document.getElementById('localField').classList.toggle('d-none', v === '1');
}

const form = document.getElementById('addWeddingForm');
form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const isEmbed = document.getElementById('typeSelect').value;
    const albumInput = document.getElementById('album_images');
    const files = albumInput.files;

    if (isEmbed == '0' && files.length > 0) {
        if (files.length > 20) { alert('Max 20 images allowed!'); return; }
        const addModal = bootstrap.Modal.getInstance(document.getElementById('addWeddingModal'));
        if (addModal) addModal.hide();
        await new Promise(r => setTimeout(r, 400));
        const progressModal = new bootstrap.Modal(document.getElementById('uploadProgressModal'), { backdrop: 'static' });
        progressModal.show();
        const progressText = document.getElementById('progressText');
        const progressBar  = document.getElementById('progressBar');
        const options = { maxSizeMB: 1, maxWidthOrHeight: 1920, useWebWorker: true };
        const compressedFiles = [];
        for (let i = 0; i < files.length; i++) {
            progressText.textContent = `Compressing ${i+1} of ${files.length}...`;
            progressBar.style.width = `${Math.round((i/files.length)*50)}%`;
            try { compressedFiles.push(await imageCompression(files[i], options)); }
            catch { compressedFiles.push(files[i]); }
        }
        progressText.textContent = 'Uploading...';
        progressBar.style.width = '55%';
        const formData = new FormData(form);
        formData.delete('album_images[]');
        compressedFiles.forEach((f, i) => formData.append('album_images[]', f, files[i].name));
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'manage_weddings.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.upload.onprogress = function(ev) {
            if (ev.lengthComputable) {
                progressBar.style.width = `${Math.round(50 + (ev.loaded/ev.total)*50)}%`;
                progressText.textContent = `Uploading ${Math.round(ev.loaded/1024/1024)}MB / ${Math.round(ev.total/1024/1024)}MB`;
            }
        };
        xhr.onload = function() {
            progressModal.hide();
            if (xhr.status===200 && xhr.responseText.includes('success')) {
                alert('Album Added!'); window.location = 'weddings';
            } else { alert('Upload failed.'); }
        };
        xhr.onerror = function() { progressModal.hide(); alert('Error.'); };
        xhr.send(formData);
    } else {
        form.submit();
    }
});
</script>

<?php if (!$is_ajax) require_once 'layout/footer.php'; ?>