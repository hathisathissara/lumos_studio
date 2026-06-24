# Lumos Studio - Wedding Photography Portfolio

Lumos Studio is a professional wedding photography studio based in Sri Lanka, founded by Dinith Nishan. This project is a dynamic, responsive portfolio website built with PHP and MySQL to showcase wedding albums, photography packages, and a timeless portrait portfolio.

**Live Website:** [https://lumos.unaux.com/](https://lumos.unaux.com/)

## Features

*   **Dynamic Portfolio & Albums:** Showcase categorized wedding shoots (Wedding, Bridal Shoot, Pre Shoot, etc.) with cover images and multiple photo uploads.
*   **Admin Panel:** A secure backend to manage albums, upload images, update packages, and manage testimonials.
*   **Testimonials:** Display client reviews fetched directly from the database.
*   **Responsive Design:** Mobile-friendly UI built with modern web technologies, Bootstrap, and Swiper for image carousels.
*   **Contact Form:** Allow users to send inquiries directly via the website.

## Tech Stack

*   **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, Swiper JS, fsLightbox
*   **Backend:** PHP
*   **Database:** MySQL
*   **Server Environment:** WAMP/XAMPP (Apache, MySQL)

## Database Setup

1. Ensure your local server environment (like WAMP or XAMPP) is running.
2. Open phpMyAdmin or your preferred MySQL client.
3. Import the `document/db.sql` file to automatically create the `wedding_portfolio_db` database and the following tables:
    * `admin_users`
    * `slideshow_images`
    * `weddings`
    * `wedding_images`
    * `packages`
    * `portfolio`
    * `testimonials`
    * `contact_messages`
4. Make sure your database connection settings in the `config/` directory are correct.

## Installation & Setup

1. Place the project folder into your local server's web directory (e.g., `c:\wamp64\www\lumosStudio`).
2. Complete the database setup as described above.
3. Access the website via your browser (e.g., `http://localhost/lumosStudio`).
4. You can manage the site's content by navigating to the admin panel at `http://localhost/lumosStudio/admin` and logging in.

## About the Photographer
**Dinith Nishan** is the Founder & Lead Photographer at Lumos Studio. With a style blending fine-art photography and photojournalism, Lumos Studio captures timeless, unscripted moments.
