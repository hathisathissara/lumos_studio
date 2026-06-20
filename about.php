<?php
// about.php
$page_title       = "About Lumos Studio | Wedding Photographers in Sri Lanka";
$page_description = "Learn about Lumos Studio — a professional wedding photography studio in Sri Lanka founded by Dinith Nishan. We capture timeless emotions with a fine-art photojournalism style.";
$page_keywords    = "About Lumos Studio, Dinith Nishan photographer, wedding photography Sri Lanka, Lumos Studio story, professional photographer Sri Lanka";
$page_canonical   = "https://lumos.unaux.com/about";
require_once 'layout/header.php';
?>

<style>
    body {
        background-color: #ffffff;
        color: #333;
    }
    .page-title {
        font-weight: 300;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin-bottom: 60px;
        text-align: center;
        font-family: 'Times New Roman', serif;
    }
    
    /* Section Block Styling */
    .about-section {
        margin-bottom: 80px;
    }
    .about-title {
        font-family: 'Times New Roman', serif;
        font-weight: 400;
        letter-spacing: 1px;
        margin-bottom: 20px;
        text-transform: uppercase;
    }
    .about-text {
        color: #555;
        line-height: 1.8;
        font-size: 0.98rem;
    }
    
    /* Minimalist Image Settings */
    .about-img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 0; /* Square edges for modern look */
        filter: grayscale(10%); /* Subtle grayscale for elegant look */
        transition: transform 0.5s ease;
    }
    .about-img:hover {
        transform: scale(1.02);
    }
    
    /* Quote Block Styling */
    .quote-block {
        border-top: 1px solid #eaeaea;
        border-bottom: 1px solid #eaeaea;
        padding: 40px 0;
        text-align: center;
        margin-top: 50px;
    }
    .quote-text {
        font-family: 'Times New Roman', serif;
        font-size: 1.5rem;
        font-style: italic;
        color: #111;
        letter-spacing: 1px;
    }
</style>

<div class="container pb-5" style="padding-top: 140px;">
    <h1 class="page-title">ABOUT LUMOS STUDIO</h1>
    

    <div class="row align-items-center about-section">
        <div class="col-md-6 mb-4 mb-md-0">
            
            <img src="assets/lumos.png" class="img-fluid about-img shadow-sm" alt="Our Studio">
        </div>
        <div class="col-md-6 px-lg-5">
            <h2 class="about-title">OUR JOURNEY & PHILOSOPHY</h2>
            <p class="about-text">
                At Lumos Studio, we believe that photography is not just about taking pictures; it is about preserving feelings, warmth, and timeless connections. Founded with a vision to redefine wedding photojournalism in Sri Lanka, we have spent years crafting a signature style that blends artistic fine-art with real, unscripted emotions.
            </p>
            <p class="about-text">
                Every wedding we capture is treated as a unique canvas. We do not believe in forced poses or standard templates. Instead, we silently observe and document the raw, authentic moments of your special day—the quiet tears, the loud laughter, and the subtle glances.
            </p>
        </div>
    </div>


    <div class="row align-items-center about-section flex-column-reverse flex-md-row">
        <div class="col-md-6 px-lg-5">
            <h2 class="about-title">MEET THE ARTIST</h2>
            <p class="about-text">
                Hello, I am the lead storyteller behind Lumos Studio. My fascination with visual storytelling began when I held my first manual camera years ago. To me, a wedding is a beautiful celebration of two souls and their families coming together, and having the privilege to document it is something I hold close to my heart.
            </p>
            <p class="about-text">
                Our approach is deeply personal. From our initial meeting to the final hand-delivery of your beautifully crafted album, we walk beside you as friends. Our ultimate goal is to create visual legacies that will be cherished across generations.
            </p>
        </div>
        <div class="col-md-6 mb-4 mb-md-0">
      
            <img src="assets/about/dinithabout.webp" class="img-fluid about-img shadow-sm" alt="The Photographer">
        </div>
    </div>

    <!-- 3. Quote Block (Minimalist Signature) -->
    <div class="quote-block">
        <div class="container col-md-8">
            <p class="quote-text">"Capturing today's moments that will melt your heart tomorrow."</p>
            <small class="text-uppercase text-muted" style="letter-spacing: 3px;">— Lumos Studio</small>
        </div>
    </div>
</div>

<?php 
require_once 'layout/footer.php'; 
?>