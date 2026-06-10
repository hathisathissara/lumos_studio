<?php
require_once 'layout/header.php';

$upload_dir = "../assets/uploads/testimonials/";
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

// Add Testimonial
if (isset($_POST['add_testimonial'])) {
    $client_name = $_POST['client_name'];
    $review_text = $_POST['review_text'];
    $image_path  = "";
    if (!empty($_FILES['image']['name'])) {
        $image_path = uniqid('test_') . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_path);
    }
    $stmt = $conn->prepare("INSERT INTO testimonials (client_name, review_text, image_path) VALUES (?, ?, ?)");
    $stmt->execute([$client_name, $review_text, $image_path]);
    echo "<script>alert('Testimonial Added!'); window.location='testimonials';</script>";
}

// Delete Testimonial
if (isset($_GET['delete'])) {
    $id   = $_GET['delete'];
    $stmt = $conn->prepare("SELECT image_path FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $img  = $stmt->fetchColumn();
    if ($img && file_exists($upload_dir . $img)) unlink($upload_dir . $img);
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='testimonials';</script>";
}

$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();
?>

<style>
.review-card {
    background: linear-gradient(145deg, var(--surface), var(--surface-2));
    border: 1px solid var(--border-soft);
    border-radius: var(--radius);
    padding: 1.4rem;
    transition: all 0.25s ease;
    display: flex; flex-direction: column;
    height: 100%; position: relative; overflow: hidden;
}
.review-card::before {
    content: '\201C';
    font-family: 'Playfair Display', serif;
    font-size: 5rem; font-weight: 700;
    position: absolute; top: -0.5rem; right: 1.2rem;
    color: var(--accent); opacity: 0.1;
    line-height: 1; pointer-events: none;
}
.review-card:hover {
    transform: translateY(-4px);
    border-color: var(--border);
    box-shadow: 0 12px 32px rgba(0,0,0,0.35);
}
.review-avatar {
    width: 50px; height: 50px; border-radius: 50%;
    object-fit: cover; flex-shrink: 0;
    border: 2px solid var(--border);
}
.review-avatar-placeholder {
    width: 50px; height: 50px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; font-weight: 700; color: white; flex-shrink: 0;
}
.review-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1rem; }
.review-name { font-size: 0.95rem; font-weight: 700; color: var(--text); }
.review-stars { display: flex; gap: 2px; margin-top: 2px; }
.review-stars i { font-size: 0.7rem; color: #fbbf24; }
.review-text {
    font-size: 0.85rem; color: var(--text-soft); line-height: 1.7;
    font-style: italic; flex: 1; margin-bottom: 1.1rem;
}
.review-footer { display: flex; justify-content: flex-end; }
</style>

<!-- Page Header -->
<div class="section-header">
    <div>
        <h3 class="sh-title">Testimonials</h3>
        <div class="sh-sub"><?= count($testimonials) ?> client reviews</div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
        <i class="fa-solid fa-plus me-2"></i>Add Testimonial
    </button>
</div>

<!-- Testimonials Grid -->
<?php if (count($testimonials) > 0): ?>
<div class="row g-3">
    <?php foreach ($testimonials as $t): ?>
    <div class="col-sm-6 col-lg-4">
        <div class="review-card">
            <div class="review-header">
                <?php if ($t['image_path']): ?>
                    <img src="../assets/uploads/testimonials/<?= $t['image_path'] ?>" class="review-avatar" alt="<?= htmlspecialchars($t['client_name']) ?>">
                <?php else: ?>
                    <div class="review-avatar-placeholder"><?= strtoupper(substr($t['client_name'], 0, 1)) ?></div>
                <?php endif; ?>
                <div>
                    <div class="review-name"><?= htmlspecialchars($t['client_name']) ?></div>
                    <div class="review-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="review-text">"<?= nl2br(htmlspecialchars($t['review_text'])) ?>"</div>
            <div class="review-footer">
                <a href="testimonials?delete=<?= $t['id'] ?>" class="btn btn-outline-danger btn-sm"
                   onclick="return confirm('Delete this testimonial?')">
                    <i class="fa-solid fa-trash me-1"></i>Delete
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body text-center py-5" style="color:var(--text-muted);">
        <i class="fa-solid fa-star" style="font-size:3rem; opacity:0.2; display:block; margin-bottom:1rem;"></i>
        <h5 style="color:var(--text-soft);">No testimonials yet</h5>
        <p style="font-size:0.88rem;">Click <strong>Add Testimonial</strong> to share your first client review.</p>
    </div>
</div>
<?php endif; ?>

<!-- Add Testimonial Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-star me-2" style="color:#fbbf24;"></i>Add Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Client Name(s)</label>
                    <input type="text" name="client_name" class="form-control" placeholder="e.g. Kasun & Amali" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Review / Feedback</label>
                    <textarea name="review_text" class="form-control" rows="4" placeholder="What did they say..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Client Photo <span style="color:var(--text-muted); font-weight:400;">(Optional)</span></label>
                    <input type="file" name="image" class="form-control">
                    <div class="form-text">Displays next to the review. If not uploaded, initials will be shown.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="add_testimonial" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Save Testimonial
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Compression -->
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.min.js"></script>
<script>
document.addEventListener('submit', async function(e) {
    const form = e.target;
    if (form.enctype === 'multipart/form-data' && !form.dataset.compressed) {
        const fileInputs = form.querySelectorAll('input[type="file"]');
        let hasFiles = false;
        for (let input of fileInputs) {
            if (input.files.length > 0 && input.files[0].type.startsWith('image/')) hasFiles = true;
        }
        if (hasFiles) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const orig = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Compressing...';
                submitBtn.disabled = true;
                const options = { maxSizeMB: 1, maxWidthOrHeight: 1920, useWebWorker: true };
                try {
                    for (let input of fileInputs) {
                        if (input.files.length > 0) {
                            const dt = new DataTransfer();
                            for (let i = 0; i < input.files.length; i++) {
                                const file = input.files[i];
                                if (file.type.startsWith('image/')) {
                                    const c = await imageCompression(file, options);
                                    dt.items.add(new File([c], file.name, { type: file.type }));
                                } else { dt.items.add(file); }
                            }
                            input.files = dt.files;
                        }
                    }
                    if (submitBtn.name) {
                        const hi = document.createElement('input');
                        hi.type='hidden'; hi.name=submitBtn.name; hi.value=submitBtn.value||'1';
                        form.appendChild(hi);
                    }
                    form.dataset.compressed = "true";
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                    form.submit();
                } catch(err) {
                    submitBtn.innerHTML = orig; submitBtn.disabled = false;
                    alert('Compression failed. Please try again.');
                }
            }
        }
    }
});
</script>

<?php require_once 'layout/footer.php'; ?>