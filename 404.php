<?php
// 404.php
require_once 'layout/header.php';
?>

<style>
    body {
        background-color: #ffffff;
        color: #333;
    }
    .error-container {
        padding: 100px 0;
        min-height: 60vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .error-code {
        font-size: 8rem;
        font-weight: 200;
        font-family: 'Times New Roman', serif;
        letter-spacing: 5px;
        line-height: 1;
        margin-bottom: 20px;
        color: #111;
    }
    .error-title {
        font-weight: 300;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    .error-text {
        color: #777;
        font-size: 1rem;
        max-width: 500px;
        margin-bottom: 35px;
        line-height: 1.6;
    }
    .btn-home {
        border: 1px solid #000;
        color: #000;
        background-color: transparent;
        border-radius: 0;
        padding: 12px 35px;
        font-weight: 500;
        transition: 0.3s ease;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .btn-home:hover {
        background-color: #000;
        color: #fff;
    }
</style>

<div class="container error-container">
    <!-- Error Code -->
    <div class="error-code">404</div>
    
    <!-- Error Title -->
    <h2 class="error-title">Page Not Found</h2>
    
    <!-- Error Description -->
    <p class="error-text">
        The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>
    
    <!-- Back to Home CTA -->
    <a href="index.php" class="btn btn-home">Return to Home</a>
</div>

<?php 
require_once 'layout/footer.php'; 
?>