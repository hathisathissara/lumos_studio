<?php
require_once 'layout/header.php';

$upload_dir = "../assets/uploads/packages/";
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

// Add Package
if (isset($_POST['add_package'])) {
    $name        = $_POST['package_name'];
    $price       = $_POST['price'];
    $description = $_POST['description'];
    $package_image = "";
    if (!empty($_FILES['package_image']['name'])) {
        $package_image = uniqid('pkg_') . "_" . $_FILES['package_image']['name'];
        move_uploaded_file($_FILES['package_image']['tmp_name'], $upload_dir . $package_image);
    }
    $stmt = $conn->prepare("INSERT INTO packages (package_name, price, description, package_image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $package_image]);
    echo "<script>alert('Package Added Successfully!'); window.location='packages';</script>";
}

// Edit Package
if (isset($_POST['edit_package'])) {
    $id             = $_POST['package_id'];
    $name           = $_POST['package_name'];
    $price          = $_POST['price'];
    $description    = $_POST['description'];
    $existing_image = $_POST['existing_image'];
    $package_image  = $existing_image;
    if (!empty($_FILES['package_image']['name'])) {
        $package_image = uniqid('pkg_') . "_" . $_FILES['package_image']['name'];
        move_uploaded_file($_FILES['package_image']['tmp_name'], $upload_dir . $package_image);
        if (file_exists($upload_dir . $existing_image) && $existing_image != "") {
            unlink($upload_dir . $existing_image);
        }
    }
    $stmt = $conn->prepare("UPDATE packages SET package_name=?, price=?, description=?, package_image=? WHERE id=?");
    $stmt->execute([$name, $price, $description, $package_image, $id]);
    echo "<script>alert('Package Updated Successfully!'); window.location='packages';</script>";
}

// Delete Package
if (isset($_GET['delete'])) {
    $id   = $_GET['delete'];
    $stmt = $conn->prepare("SELECT package_image FROM packages WHERE id = ?");
    $stmt->execute([$id]);
    $img  = $stmt->fetchColumn();
    if ($img && file_exists($upload_dir . $img)) unlink($upload_dir . $img);
    $stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location='packages';</script>";
}

$packages = $conn->query("SELECT * FROM packages ORDER BY id DESC")->fetchAll();
?>

<style>
.pkg-card {
    background: linear-gradient(145deg, var(--surface), var(--surface-2));
    border: 1px solid var(--border-soft);
    border-radius: var(--radius);
    overflow: hidden;
    transition: all 0.25s ease;
    display: flex; flex-direction: column;
    height: 100%;
}
.pkg-card:hover {
    transform: translateY(-5px);
    border-color: var(--border);
    box-shadow: 0 16px 40px rgba(0,0,0,0.4), var(--shadow-glow);
}
.pkg-img-wrap {
    height: 180px; overflow: hidden; position: relative;
    background: var(--surface-3);
    flex-shrink: 0;
}
.pkg-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
.pkg-card:hover .pkg-img-wrap img { transform: scale(1.06); }
.pkg-no-img {
    height: 100%; display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); flex-direction: column; gap: 8px;
}
.pkg-no-img i { font-size: 2.5rem; opacity: 0.3; }
.pkg-badge {
    position: absolute; top: 12px; right: 12px;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    color: white; font-size: 0.78rem; font-weight: 700;
    padding: 4px 12px; border-radius: 20px;
    box-shadow: 0 2px 10px var(--accent-glow);
    letter-spacing: 0.02em;
}
.pkg-body {
    padding: 1.25rem; flex: 1;
    display: flex; flex-direction: column;
}
.pkg-name { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 0.3rem; }
.pkg-price {
    font-size: 1.15rem; font-weight: 800;
    background: linear-gradient(135deg, var(--accent-2), var(--amber));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; margin-bottom: 0.7rem;
}
.pkg-desc {
    font-size: 0.82rem; color: var(--text-muted); line-height: 1.6;
    flex: 1; margin-bottom: 1rem;
    display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;
    overflow: hidden;
}
.pkg-actions { display: flex; gap: 0.5rem; }
.pkg-actions .btn { flex: 1; font-size: 0.82rem; padding: 0.45rem; }

.empty-state {
    text-align: center; padding: 4rem 2rem;
    color: var(--text-muted);
}
.empty-state i { font-size: 3.5rem; opacity: 0.2; margin-bottom: 1rem; display: block; }
.empty-state p { font-size: 0.9rem; }
</style>

<!-- Page Header -->
<div class="section-header">
    <div>
        <h3 class="sh-title">Packages</h3>
        <div class="sh-sub">Manage your photography pricing and service packages</div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPackageModal">
        <i class="fa-solid fa-plus me-2"></i>Add Package
    </button>
</div>

<!-- Packages Grid -->
<?php if (count($packages) > 0): ?>
<div class="row g-3">
    <?php foreach ($packages as $p): ?>
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="pkg-card">
            <div class="pkg-img-wrap">
                <?php if ($p['package_image']): ?>
                    <img src="../assets/uploads/packages/<?= $p['package_image'] ?>" alt="<?= htmlspecialchars($p['package_name']) ?>">
                <?php else: ?>
                    <div class="pkg-no-img">
                        <i class="fa-solid fa-image"></i>
                        <span style="font-size:0.75rem;">No Image</span>
                    </div>
                <?php endif; ?>
                <div class="pkg-badge"><?= htmlspecialchars($p['price']) ?></div>
            </div>
            <div class="pkg-body">
                <div class="pkg-name"><?= htmlspecialchars($p['package_name']) ?></div>
                <div class="pkg-price"><?= htmlspecialchars($p['price']) ?></div>
                <div class="pkg-desc"><?= nl2br(htmlspecialchars($p['description'])) ?></div>
                <div class="pkg-actions">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>">
                        <i class="fa-solid fa-pen me-1"></i>Edit
                    </button>
                    <a href="packages?delete=<?= $p['id'] ?>" class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Delete this package?')">
                        <i class="fa-solid fa-trash me-1"></i>Delete
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-pen me-2" style="color:var(--accent-2);"></i>Edit Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="package_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="existing_image" value="<?= $p['package_image'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Package Name</label>
                            <input type="text" name="package_name" class="form-control" value="<?= htmlspecialchars($p['package_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" name="price" class="form-control" value="<?= htmlspecialchars($p['price']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($p['description']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Update Image <span style="color:var(--text-muted); font-weight:400;">(Optional)</span></label>
                            <input type="file" name="package_image" class="form-control">
                            <?php if ($p['package_image']): ?>
                                <div class="form-text mt-1"><i class="fa-solid fa-image me-1"></i>Current: <?= $p['package_image'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_package" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body empty-state">
        <i class="fa-solid fa-box-open"></i>
        <h5 style="color:var(--text-soft);">No packages yet</h5>
        <p>Click <strong>Add Package</strong> to create your first pricing package.</p>
    </div>
</div>
<?php endif; ?>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-plus me-2" style="color:var(--accent-2);"></i>Add New Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Package Name</label>
                    <input type="text" name="package_name" class="form-control" placeholder="e.g. Premium Wedding Package" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" placeholder="e.g. Rs. 150,000" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Enter package features, line by line..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Package Image</label>
                    <input type="file" name="package_image" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="add_package" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-2"></i>Save Package
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
                const options = { maxSizeMB: 0.5, maxWidthOrHeight: 1080, useWebWorker: true, fileType: 'image/webp' };
                try {
                    for (let input of fileInputs) {
                        if (input.files.length > 0) {
                            const dt = new DataTransfer();
                            for (let i = 0; i < input.files.length; i++) {
                                const file = input.files[i];
                                if (file.type.startsWith('image/')) {
                                    const compressed = await imageCompression(file, options);
                                    const newName = file.name.replace(/\.[^/.]+$/, "") + ".webp";
                                    dt.items.add(new File([compressed], newName, { type: 'image/webp' }));
                                } else { dt.items.add(file); }
                            }
                            input.files = dt.files;
                        }
                    }
                    if (submitBtn.name) {
                        const hi = document.createElement('input');
                        hi.type = 'hidden'; hi.name = submitBtn.name; hi.value = submitBtn.value || '1';
                        form.appendChild(hi);
                    }
                    form.dataset.compressed = "true";
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
                    form.submit();
                } catch (err) {
                    submitBtn.innerHTML = orig;
                    submitBtn.disabled = false;
                    alert('Image compression failed. Please try again.');
                }
            }
        }
    }
});
</script>

<?php require_once 'layout/footer.php'; ?>