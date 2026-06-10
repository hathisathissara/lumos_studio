<?php
require_once '../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$is_login_page = (basename($_SERVER['PHP_SELF']) == 'login.php') ||
                 (strpos($_SERVER['REQUEST_URI'], '/login') !== false);

if (!$is_login_page && !isset($_SESSION['admin_logged_in'])) {
    header("Location: login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Lumos Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* =============================================
           LUMOS STUDIO — ADMIN DESIGN SYSTEM
        ============================================= */
        :root {
            --bg:           #080d1a;
            --surface:      #0f172a;
            --surface-2:    #141e33;
            --surface-3:    #1e293b;
            --accent:       #6366f1;
            --accent-2:     #818cf8;
            --accent-glow:  rgba(99,102,241,0.22);
            --amber:        #f59e0b;
            --success:      #10b981;
            --danger:       #f43f5e;
            --text:         #f1f5f9;
            --text-muted:   #64748b;
            --text-soft:    #94a3b8;
            --border:       rgba(99,102,241,0.15);
            --border-soft:  rgba(255,255,255,0.055);
            --radius:       14px;
            --radius-sm:    9px;
            --sidebar-w:    268px;
            --topbar-h:     66px;
            --shadow:       0 4px 28px rgba(0,0,0,0.45);
            --shadow-glow:  0 0 40px rgba(99,102,241,0.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            overflow-x: hidden;
            line-height: 1.65;
        }

        /* === SCROLLBAR === */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--surface-3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* === LAYOUT === */
        .admin-wrapper { display: flex; min-height: 100vh; }

        /* =============================================
           SIDEBAR
        ============================================= */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border-soft);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1050;
            transition: transform 0.32s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        /* Ambient glow top-left */
        .sidebar::before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(99,102,241,0.14), transparent 70%);
            pointer-events: none;
        }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 1.4rem 1.4rem;
            text-decoration: none;
            border-bottom: 1px solid var(--border-soft);
            flex-shrink: 0;
        }
        .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.05rem;
            box-shadow: 0 4px 14px var(--accent-glow);
            flex-shrink: 0;
        }
        .brand-text { display: flex; flex-direction: column; }
        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem; font-weight: 700;
            color: var(--text); line-height: 1.2;
            letter-spacing: -0.01em;
        }
        .brand-sub {
            font-size: 0.68rem; color: var(--text-muted);
            letter-spacing: 0.08em; text-transform: uppercase;
        }

        /* Nav */
        .sidebar-nav {
            padding: 1rem 0.6rem;
            display: flex; flex-direction: column;
            gap: 2px; flex: 1;
            overflow-y: auto;
        }
        .nav-label {
            font-size: 0.67rem; text-transform: uppercase;
            letter-spacing: 0.09em; color: var(--text-muted);
            padding: 0.65rem 0.75rem 0.25rem;
            margin-top: 0.3rem;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 11px;
            padding: 9px 13px;
            color: var(--text-soft);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all 0.17s ease;
            font-weight: 500; font-size: 0.88rem;
            position: relative;
        }
        .nav-icon {
            width: 33px; height: 33px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; font-size: 0.88rem;
            transition: all 0.17s ease; flex-shrink: 0;
            color: var(--text-muted);
        }
        .sidebar-link:hover {
            background: rgba(99,102,241,0.08);
            color: var(--text);
        }
        .sidebar-link:hover .nav-icon {
            background: rgba(99,102,241,0.15);
            color: var(--accent-2);
        }
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(99,102,241,0.2), rgba(99,102,241,0.06));
            color: var(--text);
        }
        .sidebar-link.active .nav-icon {
            background: rgba(99,102,241,0.22);
            color: var(--accent-2);
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute; left: 0; top: 20%; bottom: 20%;
            width: 3px; border-radius: 0 3px 3px 0;
            background: linear-gradient(180deg, var(--accent), var(--accent-2));
        }

        /* Sidebar footer */
        .sidebar-divider { height: 1px; background: var(--border-soft); margin: 0.4rem 0.6rem; }
        .sidebar-footer { padding: 0.5rem 0.6rem 1.1rem; flex-shrink: 0; }
        .logout-link { color: var(--text-muted) !important; }
        .logout-link .nav-icon { color: var(--danger); }
        .logout-link:hover { background: rgba(244,63,94,0.09) !important; color: var(--danger) !important; }
        .logout-link:hover .nav-icon { background: rgba(244,63,94,0.15) !important; }

        /* =============================================
           MAIN CONTENT
        ============================================= */
        .main-content {
            flex: 1; margin-left: var(--sidebar-w);
            display: flex; flex-direction: column;
            min-height: 100vh;
        }

        /* TOP BAR */
        .top-bar {
            height: var(--topbar-h);
            padding: 0 2rem;
            display: flex; align-items: center; gap: 1rem;
            background: rgba(8,13,26,0.82);
            backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border-soft);
            position: sticky; top: 0; z-index: 999;
        }
        .mobile-toggle {
            display: none;
            background: var(--surface-2);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius-sm);
            color: var(--text); width: 38px; height: 38px;
            cursor: pointer; align-items: center; justify-content: center;
            font-size: 1rem; transition: all 0.18s ease; flex-shrink: 0;
        }
        .mobile-toggle:hover { background: var(--accent); border-color: var(--accent); }
        .page-info { flex: 1; min-width: 0; }
        .page-title { font-size: 1rem; font-weight: 600; color: var(--text); line-height: 1.2; }
        .breadcrumb-trail { font-size: 0.73rem; color: var(--text-muted); }
        .top-bar-actions { display: flex; align-items: center; gap: 0.7rem; margin-left: auto; }
        .clock-pill {
            background: var(--surface-2); border: 1px solid var(--border-soft);
            border-radius: 20px; padding: 5px 13px;
            font-size: 0.77rem; color: var(--text-soft);
            font-variant-numeric: tabular-nums;
        }
        .admin-pill {
            display: flex; align-items: center; gap: 9px;
            background: var(--surface-2); border: 1px solid var(--border-soft);
            border-radius: 30px; padding: 5px 13px 5px 5px;
            cursor: default; transition: all 0.18s ease;
        }
        .admin-pill:hover { border-color: var(--border); }
        .avatar-circle {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 700; color: white;
        }
        .avatar-name { font-size: 0.82rem; font-weight: 600; color: var(--text); }

        /* CONTENT AREA */
        .content-area { padding: 2rem; flex: 1; }

        /* =============================================
           SHARED COMPONENT STYLES
        ============================================= */

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border-soft) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow);
            color: var(--text);
        }
        .card-body { color: var(--text); }
        .glass-card {
            background: linear-gradient(145deg, var(--surface), var(--surface-2));
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            position: relative; overflow: hidden;
        }
        .glass-card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.45), transparent);
        }
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-glow), var(--shadow);
            border-color: rgba(99,102,241,0.25);
        }

        /* Tables */
        .table { color: var(--text); border-color: var(--border-soft); margin-bottom: 0; }
        .table > :not(caption) > * > * {
            padding: 1rem 1.2rem;
            background: transparent;
            border-bottom-color: var(--border-soft);
            color: var(--text);
            vertical-align: middle;
        }
        .table-dark thead { background: transparent !important; }
        .table-dark thead th {
            background: var(--surface-2) !important;
            color: var(--text-muted) !important;
            border-color: var(--border-soft) !important;
            border-bottom: 1px solid var(--border) !important;
            font-size: 0.73rem; text-transform: uppercase;
            letter-spacing: 0.07em; font-weight: 600; padding: 0.9rem 1.2rem;
        }
        .table-hover tbody tr { transition: background 0.14s ease; }
        .table-hover tbody tr:hover > * { background: rgba(99,102,241,0.055) !important; }

        /* Badges */
        .badge { font-weight: 500; font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; letter-spacing: 0.02em; }
        .badge.bg-secondary { background: var(--surface-3) !important; color: var(--text-soft) !important; border: 1px solid var(--border-soft); }
        .badge.bg-info { background: rgba(99,102,241,0.2) !important; color: var(--accent-2) !important; }

        /* Buttons */
        .btn { border-radius: var(--radius-sm); font-weight: 500; font-size: 0.88rem; transition: all 0.17s ease; }
        .btn-primary { background: linear-gradient(135deg, var(--accent), var(--accent-2)); border: none; color: white; box-shadow: 0 3px 10px var(--accent-glow); }
        .btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 6px 18px var(--accent-glow); color: white; }
        .btn-dark { background: var(--surface-3); border: 1px solid var(--border-soft); color: var(--text); }
        .btn-dark:hover { background: var(--accent); border-color: var(--accent); color: white; box-shadow: 0 4px 12px var(--accent-glow); }
        .btn-outline-primary { border-color: rgba(99,102,241,0.4); color: var(--accent-2); }
        .btn-outline-primary:hover { background: var(--accent); border-color: var(--accent); color: white; box-shadow: 0 4px 12px var(--accent-glow); }
        .btn-outline-danger { border-color: rgba(244,63,94,0.35); color: var(--danger); background: transparent; }
        .btn-outline-danger:hover { background: var(--danger); color: white; border-color: var(--danger); }
        .btn-success { background: var(--success); border: none; color: white; box-shadow: 0 3px 10px rgba(16,185,129,0.2); }
        .btn-success:hover { filter: brightness(1.1); transform: translateY(-1px); color: white; }

        /* Forms */
        .form-control, .form-select {
            background: var(--surface-2); border: 1px solid var(--border-soft);
            color: var(--text); border-radius: var(--radius-sm);
            padding: 0.58rem 0.9rem; transition: all 0.17s ease;
        }
        .form-control:focus, .form-select:focus {
            background: var(--surface-2); color: var(--text);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow); outline: none;
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-label { color: var(--text-soft); font-size: 0.83rem; font-weight: 500; margin-bottom: 0.38rem; }
        .form-select option { background: var(--surface-2); color: var(--text); }
        .form-text { color: var(--text-muted) !important; font-size: 0.77rem; }

        /* Modals */
        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 24px 70px rgba(0,0,0,0.65), var(--shadow-glow);
            color: var(--text);
        }
        .modal-header { border-bottom: 1px solid var(--border-soft); padding: 1.2rem 1.5rem; }
        .modal-body { padding: 1.5rem; }
        .modal-footer { border-top: 1px solid var(--border-soft); padding: 1rem 1.5rem; }
        .modal-title, .modal-content label, .modal-content h5 { color: var(--text) !important; }
        .modal-content .text-muted, .modal-content small.text-muted { color: var(--text-muted) !important; }
        .btn-close { filter: invert(1) opacity(0.6); transition: opacity 0.15s ease; }
        .btn-close:hover { filter: invert(1) opacity(1); }
        .modal-backdrop.show { opacity: 0.65; }

        /* Alerts */
        .alert-danger { background: rgba(244,63,94,0.1); border-color: rgba(244,63,94,0.28); color: #fb7185; border-radius: var(--radius-sm); }
        .alert-success { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.28); color: #34d399; border-radius: var(--radius-sm); }

        /* Page section header */
        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.6rem; flex-wrap: wrap; gap: 1rem;
        }
        .section-header .sh-title { font-size: 1.3rem; font-weight: 700; color: var(--text); margin: 0; }
        .section-header .sh-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }

        /* =============================================
           RESPONSIVE
        ============================================= */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); box-shadow: 20px 0 60px rgba(0,0,0,0.55); }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: flex !important; }
            .content-area { padding: 1.25rem; }
            .clock-pill { display: none; }
        }
        @media (max-width: 576px) {
            .top-bar { padding: 0 1rem; }
        }
    </style>
</head>
<body>

<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

$pageTitles = [
    'index'               => ['Dashboard',    'Overview of your studio'],
    'manage_weddings'     => ['Weddings',     'Manage wedding albums'],
    'manage_packages'     => ['Packages',     'Manage pricing & packages'],
    'manage_portfolio'    => ['Portfolio',    'Manage photo gallery'],
    'manage_testimonials' => ['Testimonials', 'Manage client reviews'],
    'messages'            => ['Messages',     'Client inquiries & inbox'],
];
$ctitle = $pageTitles[$currentPage] ?? ['Admin', 'Lumos Studio'];

if (isset($_SESSION['admin_logged_in'])): ?>
<div class="admin-wrapper">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
        <a href="dashboard" class="sidebar-brand">
            <div class="brand-icon"><i class="fa-solid fa-camera-retro"></i></div>
            <div class="brand-text">
                <span class="brand-name">Lumos</span>
                <span class="brand-sub">Studio Admin</span>
            </div>
        </a>

        <nav class="sidebar-nav">
            <span class="nav-label">Main</span>
            <a href="dashboard" class="sidebar-link <?= in_array($currentPage, ['index','dashboard']) ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-house-chimney"></i></span>
                Dashboard
            </a>

            <span class="nav-label">Content</span>
            <a href="weddings" class="sidebar-link <?= $currentPage == 'manage_weddings' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-ring"></i></span>
                Weddings
            </a>
            <a href="packages" class="sidebar-link <?= $currentPage == 'manage_packages' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-box-open"></i></span>
                Packages
            </a>
            <a href="portfolio" class="sidebar-link <?= $currentPage == 'manage_portfolio' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-images"></i></span>
                Portfolio
            </a>
            <a href="testimonials" class="sidebar-link <?= $currentPage == 'manage_testimonials' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-star"></i></span>
                Testimonials
            </a>

            <span class="nav-label">Inbox</span>
            <a href="messages" class="sidebar-link <?= $currentPage == 'messages' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-envelope"></i></span>
                Messages
            </a>
        </nav>

        <div class="sidebar-divider"></div>
        <div class="sidebar-footer">
            <a href="logout" class="sidebar-link logout-link">
                <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                Logout
            </a>
        </div>
    </aside>

    <!-- ===== MAIN ===== -->
    <main class="main-content">
        <header class="top-bar">
            <button class="mobile-toggle" id="mobileToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="page-info">
                <div class="page-title"><?= $ctitle[0] ?></div>
                <div class="breadcrumb-trail">Lumos Admin &rsaquo; <?= $ctitle[0] ?></div>
            </div>
            <div class="top-bar-actions">
                <div class="clock-pill" id="liveClock"></div>
                <div class="admin-pill">
                    <div class="avatar-circle"><?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
                    <span class="avatar-name"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
                </div>
            </div>
        </header>

        <div class="content-area container-fluid">

<?php else: ?>
    <div id="login-root">
<?php endif; ?>