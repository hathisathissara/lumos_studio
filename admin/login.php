<?php
// admin/login.php — POST processing MUST happen before any HTML output
require_once '../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = $user['username'];
                    header("Location: dashboard");
                    exit();
                } else {
                    $error = "Incorrect password. Please try again.";
                }
            } else {
                $error = "No admin account found with that username.";
            }
        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
// Include layout AFTER processing (so redirects work)
require_once 'layout/header.php';
?>

<style>
/* Login page overrides */
#login-root {
    min-height: 100vh;
    display: flex;
    align-items: stretch;
    background: var(--bg);
}

/* Left hero panel */
.login-hero {
    display: flex;
    position: absolute;
    inset: 0;
    overflow: hidden;
    background: linear-gradient(145deg, #0a0f1e 0%, #12183a 50%, #0e1730 100%);
    align-items: center;
    justify-content: center;
    padding: 3rem;
    z-index: 0;
}
@media (min-width: 900px) { .login-hero { position: relative; flex: 1; } }

.hero-glow-1 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.22), transparent 70%);
    top: -80px; left: -80px; pointer-events: none;
}
.hero-glow-2 {
    position: absolute; width: 300px; height: 300px; border-radius: 50%;
    background: radial-gradient(circle, rgba(245,158,11,0.12), transparent 70%);
    bottom: -60px; right: -60px; pointer-events: none;
}
.hero-content { 
    position: relative; z-index: 1; text-align: center; max-width: 380px; 
    display: none; 
}
@media (min-width: 900px) { .hero-content { display: block; } }
.hero-icon {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    border-radius: 22px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 2rem; color: white;
    box-shadow: 0 12px 40px var(--accent-glow);
    margin-bottom: 1.75rem;
    animation: float 3.5s ease-in-out infinite;
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem; font-weight: 700;
    color: var(--text); line-height: 1.2;
    margin-bottom: 1rem;
}
.hero-title span { color: var(--accent-2); }
.hero-desc { color: var(--text-muted); font-size: 0.92rem; line-height: 1.7; margin-bottom: 2rem; }
.hero-features { display: flex; flex-direction: column; gap: 0.7rem; text-align: left; }
.hero-feature {
    display: flex; align-items: center; gap: 10px;
    color: var(--text-soft); font-size: 0.85rem;
}
.hero-feature i { color: var(--accent-2); width: 16px; }

/* Right form panel */
.login-form-panel {
    width: 100%;
    display: flex; align-items: center; justify-content: center;
    padding: 2rem 1.5rem;
    position: relative;
    z-index: 1;
    min-height: 100vh;
}
@media (min-width: 900px) { 
    .login-form-panel { 
        width: 440px; flex-shrink: 0; 
        min-height: auto;
        background: var(--surface); 
    } 
}

.login-form-panel::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--accent), var(--accent-2), var(--amber));
    display: none;
}
@media (min-width: 900px) { .login-form-panel::before { display: block; } }

.login-box { 
    width: 100%; max-width: 360px; 
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(12px);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius);
    padding: 2.5rem 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}
@media (min-width: 900px) {
    .login-box {
        background: transparent;
        backdrop-filter: none;
        border: none;
        box-shadow: none;
        padding: 0;
    }
}

.login-logo {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 2.25rem;
}
.login-logo-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: white;
    box-shadow: 0 6px 18px var(--accent-glow);
}
.login-logo-text { display: flex; flex-direction: column; }
.login-logo-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; font-weight: 700; color: var(--text);
}
.login-logo-sub { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; }

.login-heading { font-size: 1.55rem; font-weight: 700; color: var(--text); margin-bottom: 0.4rem; }
.login-subheading { font-size: 0.88rem; color: var(--text-muted); margin-bottom: 2rem; }

.form-group { margin-bottom: 1.1rem; }
.input-icon-wrap { position: relative; }
.input-icon-wrap .field-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 0.88rem; pointer-events: none;
    transition: color 0.17s ease;
}
.input-icon-wrap .form-control { padding-left: 2.5rem; }
.input-icon-wrap .form-control:focus ~ .field-icon,
.input-icon-wrap:focus-within .field-icon { color: var(--accent-2); }

.btn-login {
    width: 100%; padding: 0.72rem;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    border: none; border-radius: var(--radius-sm);
    color: white; font-size: 0.92rem; font-weight: 600;
    box-shadow: 0 4px 16px var(--accent-glow);
    transition: all 0.2s ease; cursor: pointer; letter-spacing: 0.02em;
}
.btn-login:hover {
    filter: brightness(1.1); transform: translateY(-1px);
    box-shadow: 0 8px 24px var(--accent-glow);
}
.btn-login:active { transform: translateY(0); }

.login-footer-note { text-align: center; margin-top: 1.75rem; color: var(--text-muted); font-size: 0.78rem; }
</style>

<div id="login-root">
    <!-- Left Hero -->
    <div class="login-hero">
        <div class="hero-glow-1"></div>
        <div class="hero-glow-2"></div>
        <div class="hero-content">
            <div class="hero-icon"><i class="fa-solid fa-camera-retro"></i></div>
            <h1 class="hero-title">Lumos <span>Studio</span></h1>
            <p class="hero-desc">Your complete wedding photography management platform. Curate albums, manage packages, and track client inquiries — all in one place.</p>
            <div class="hero-features">
                <div class="hero-feature"><i class="fa-solid fa-check-circle"></i> Manage wedding albums & galleries</div>
                <div class="hero-feature"><i class="fa-solid fa-check-circle"></i> Update packages & pricing</div>
                <div class="hero-feature"><i class="fa-solid fa-check-circle"></i> View client messages & inquiries</div>
                <div class="hero-feature"><i class="fa-solid fa-check-circle"></i> Publish testimonials & portfolio</div>
            </div>
        </div>
    </div>

    <!-- Right Form -->
    <div class="login-form-panel">
        <div class="login-box">
            <div class="login-logo">
                <div class="login-logo-icon"><i class="fa-solid fa-camera-retro"></i></div>
                <div class="login-logo-text">
                    <span class="login-logo-name">Lumos Studio</span>
                    <span class="login-logo-sub">Admin Portal</span>
                </div>
            </div>

            <h2 class="login-heading">Welcome back</h2>
            <p class="login-subheading">Sign in to manage your studio content.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 mb-3" style="font-size:0.85rem;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i><?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" autocomplete="off">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-icon-wrap">
                        <i class="fa-solid fa-user field-icon"></i>
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-icon-wrap">
                        <i class="fa-solid fa-lock field-icon"></i>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>
                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Sign In
                </button>
            </form>

            <div class="login-footer-note">
                &copy; <?= date('Y') ?> Lumos Studio. All rights reserved.
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>