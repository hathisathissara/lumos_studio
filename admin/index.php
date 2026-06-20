<?php
require_once 'layout/header.php';

// Fetch counts for stat cards
$weddingCount     = $conn->query("SELECT COUNT(*) FROM weddings")->fetchColumn();
$packageCount     = $conn->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$portfolioCount   = $conn->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();
$testimonialCount = $conn->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
try {
    $messageCount = $conn->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
} catch (Exception $e) { $messageCount = 0; }
?>

<style>
.stat-card {
    background: linear-gradient(145deg, var(--surface), var(--surface-2));
    border: 1px solid var(--border-soft);
    border-radius: var(--radius);
    padding: 1.4rem 1.5rem;
    display: flex; align-items: center; gap: 1.1rem;
    transition: all 0.25s ease;
    text-decoration: none; color: inherit;
    position: relative; overflow: hidden;
}
.stat-card::after {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
}
.stat-card:hover {
    transform: translateY(-4px);
    border-color: var(--border);
    box-shadow: 0 12px 32px rgba(0,0,0,0.35);
    color: inherit;
    text-decoration: none;
}
.stat-icon {
    width: 52px; height: 52px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.stat-info { min-width: 0; }
.stat-value { font-size: 1.9rem; font-weight: 800; line-height: 1; color: var(--text); }
.stat-label { font-size: 0.78rem; color: var(--text-muted); margin-top: 4px; text-transform: uppercase; letter-spacing: 0.05em; }
.stat-arrow {
    margin-left: auto; color: var(--text-muted); font-size: 0.85rem;
    transition: all 0.2s ease; flex-shrink: 0;
}
.stat-card:hover .stat-arrow { color: var(--accent-2); transform: translateX(3px); }

/* Quick action cards */
.action-card {
    background: linear-gradient(145deg, var(--surface), var(--surface-2));
    border: 1px solid var(--border-soft);
    border-radius: var(--radius);
    padding: 1.5rem;
    text-align: center;
    transition: all 0.25s ease;
    text-decoration: none; color: var(--text);
    display: flex; flex-direction: column; align-items: center; gap: 0.8rem;
    position: relative; overflow: hidden;
}
.action-card::after {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    opacity: 0; transition: opacity 0.25s ease;
}
.action-card:hover { transform: translateY(-5px); color: var(--text); text-decoration: none; border-color: var(--border); box-shadow: 0 12px 32px rgba(0,0,0,0.35); }
.action-card:hover::after { opacity: 1; }
.action-card.ac-indigo::after  { background: linear-gradient(90deg, var(--accent), var(--accent-2)); }
.action-card.ac-green::after   { background: linear-gradient(90deg, #10b981, #34d399); }
.action-card.ac-amber::after   { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.action-card.ac-rose::after    { background: linear-gradient(90deg, #f43f5e, #fb7185); }
.action-card.ac-sky::after     { background: linear-gradient(90deg, #0ea5e9, #38bdf8); }

.action-icon {
    width: 56px; height: 56px; border-radius: 15px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
}
.ac-indigo .action-icon { background: rgba(99,102,241,0.15); color: var(--accent-2); }
.ac-green  .action-icon { background: rgba(16,185,129,0.15); color: #34d399; }
.ac-amber  .action-icon { background: rgba(245,158,11,0.15); color: #fbbf24; }
.ac-rose   .action-icon { background: rgba(244,63,94,0.15);  color: #fb7185; }
.ac-sky    .action-icon { background: rgba(14,165,233,0.15); color: #38bdf8; }

.action-label { font-size: 0.88rem; font-weight: 600; }
</style>

<!-- Welcome Row -->
<div class="section-header">
    <div>
        <h3 class="sh-title" style="font-family:'Playfair Display',serif; font-size:1.6rem;">
            Good <span id="dynamicGreeting">...</span>,
            <?= htmlspecialchars(ucfirst($_SESSION['admin_username'] ?? 'Admin')) ?> 👋
        </h3>
        <div class="sh-sub">Here's an overview of your Lumos Studio content.</div>
    </div>
    <div style="color:var(--text-muted); font-size:0.82rem;">
        <i class="fa-regular fa-calendar me-1"></i><?= date('l, d F Y') ?>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2-4">
        <a href="albums" class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,0.15); color:var(--accent-2);">
                <i class="fa-solid fa-ring"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?= $weddingCount ?></div>
                <div class="stat-label">Albums</div>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <a href="packages" class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,0.15); color:#34d399;">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?= $packageCount ?></div>
                <div class="stat-label">Packages</div>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <a href="portfolio" class="stat-card">
            <div class="stat-icon" style="background:rgba(245,158,11,0.15); color:#fbbf24;">
                <i class="fa-solid fa-images"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?= $portfolioCount ?></div>
                <div class="stat-label">Photos</div>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <a href="testimonials" class="stat-card">
            <div class="stat-icon" style="background:rgba(251,191,36,0.15); color:#fbbf24;">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?= $testimonialCount ?></div>
                <div class="stat-label">Reviews</div>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2-4">
        <a href="messages" class="stat-card">
            <div class="stat-icon" style="background:rgba(14,165,233,0.15); color:#38bdf8;">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?= $messageCount ?></div>
                <div class="stat-label">Messages</div>
            </div>
            <i class="fa-solid fa-chevron-right stat-arrow"></i>
        </a>
    </div>
</div>

<!-- Quick Actions -->
<div style="margin-bottom:1rem;">
    <h5 style="font-size:0.82rem; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:1rem;">Quick Actions</h5>
    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg">
            <a href="albums" class="action-card ac-indigo">
                <div class="action-icon"><i class="fa-solid fa-ring"></i></div>
                <span class="action-label">Albums</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="packages" class="action-card ac-green">
                <div class="action-icon"><i class="fa-solid fa-box-open"></i></div>
                <span class="action-label">Packages</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="portfolio" class="action-card ac-amber">
                <div class="action-icon"><i class="fa-solid fa-images"></i></div>
                <span class="action-label">Portfolio</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="testimonials" class="action-card ac-rose">
                <div class="action-icon"><i class="fa-solid fa-star"></i></div>
                <span class="action-label">Testimonials</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="messages" class="action-card ac-sky">
                <div class="action-icon"><i class="fa-solid fa-envelope"></i></div>
                <span class="action-label">Messages</span>
            </a>
        </div>
    </div>
</div>

<style>
/* 5-column layout helper */
@media (min-width: 992px) {
    .col-lg-2-4 { width: 20%; flex: 0 0 20%; max-width: 20%; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hour = new Date().getHours();
    let greeting = 'evening';
    if (hour < 12) greeting = 'morning';
    else if (hour < 18) greeting = 'afternoon';
    const el = document.getElementById('dynamicGreeting');
    if (el) el.textContent = greeting;
});
</script>

<?php require_once 'layout/footer.php'; ?>