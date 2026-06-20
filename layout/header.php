
<?php require_once './config/config.php';?>

<?php
// Per-page SEO defaults (individual pages can override these before including header)
if (!isset($page_title))       $page_title       = "Lumos Studio - Wedding Photography in Sri Lanka";
if (!isset($page_description)) $page_description = "Lumos Studio - Professional Wedding Photography in Sri Lanka. Specializing in wedding photography, portraits, and capturing your most beautiful moments.";
if (!isset($page_keywords))    $page_keywords    = "Lumos Studio, Wedding Photography, Photo Studio, Professional Photography, Sri Lanka, Wedding Photographer, Portrait Photography, Lumos Studio Sri Lanka";
if (!isset($page_canonical))   $page_canonical   = "https://lumos.unaux.com/";
if (!isset($page_og_image))    $page_og_image    = "https://lumos.unaux.com/assets/lumos.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="author" content="Lumos Studio">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#000000">

    <!-- Open Graph Meta Tags (Facebook, WhatsApp, etc.) -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($page_canonical); ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo htmlspecialchars($page_og_image); ?>">
    <meta property="og:site_name" content="Lumos Studio">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($page_og_image); ?>">
    <meta name="twitter:site" content="@lumosstudio">

    <meta name="google-site-verification" content="KrJVNOQBGtAEWfU1vPROURf0R31dI2ExYzITXmZN8X0" />
    <link rel="canonical" href="<?php echo htmlspecialchars($page_canonical); ?>"/>

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "PhotographyStore",
  "name": "Lumos Studio",
  "url": "https://lumos.unaux.com/",
  "logo": "https://lumos.unaux.com/assets/lumos.jpg",
  "description": "Lumos Studio is a professional wedding photography studio based in Sri Lanka, specializing in timeless wedding photography and portraits.",
  "telephone": "+94758385027",
  "email": "lumosstudio.lk@gmail.com",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Mahiyanganaya",
    "addressCountry": "LK"
  },
  "sameAs": [
    "https://www.facebook.com/profile.php?id=61550491520210",
    "https://www.tiktok.com/@lumosstudio.lk"
  ]
}
</script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/lumos.jpg" type="image/x-icon">
    <style>
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background-color: rgba(255, 255, 255, 0.95);
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Public Navbar -->
<nav class="navbar navbar-expand-lg navbar-light position-absolute w-100 z-3" style="background: transparent;">
    <div class="container mt-3">
        <a class="navbar-brand" href="home" aria-label="Lumos Studio Home">
            <img src="assets/lumos.png" alt="Lumos Studio Logo" style="height: 80px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="albums">Albums</a></li>
                <li class="nav-item"><a class="nav-link" href="packages">Packages</a></li>
                <li class="nav-item"><a class="nav-link" href="portfolio">Portfolio</a></li>
                <li class="nav-item"><a class="nav-link" href="about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact">Contact Us</a></li>
            </ul>
        </div>
    </div>
</nav>