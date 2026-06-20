-- 1. Database එක නිර්මාණය කිරීම සහ තෝරා ගැනීම
CREATE DATABASE IF NOT EXISTS wedding_portfolio_db;
USE wedding_portfolio_db;

-- 2. Admin Login සඳහා Table එක
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 3. Home Page එකේ Slideshow පින්තූර සඳහා Table එක
CREATE TABLE IF NOT EXISTS slideshow_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0
);

-- 4. Weddings (ප්‍රධාන ඇල්බම් කවරය සහ FB Embeds) සඳහා Table එක
CREATE TABLE IF NOT EXISTS weddings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category ENUM('Wedding', 'Bridal Shoot', 'Engagement', 'Pre Shoot','Baby Shoot') NOT NULL,
    cover_image VARCHAR(255) NULL,
    folder_name VARCHAR(255) NULL,
    fb_embed_code TEXT NULL,
    is_embed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Upload කරන Wedding පින්තූර සඳහා Table එක (Multiple Images Uploads සඳහා)
CREATE TABLE IF NOT EXISTS wedding_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    wedding_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (wedding_id) REFERENCES weddings(id) ON DELETE CASCADE
);

-- 6. Packages සඳහා Table එක
CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_name VARCHAR(100) NOT NULL,
    price VARCHAR(50) NOT NULL,
    description TEXT,
    package_image VARCHAR(255)
);

-- 7. Portfolio (වෙනත් තනි පින්තූර එකතුව) සඳහා Table එක
CREATE TABLE IF NOT EXISTS portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    image_path VARCHAR(255) NOT NULL,
    category VARCHAR(50) DEFAULT 'General'
);

-- 8. Testimonials (Facebook රිවිව්ස් සහ පින්තූර) සඳහා Table එක
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    image_path VARCHAR(255) NULL, -- Couple එකේ පින්තූරය සඳහා
    source VARCHAR(50) DEFAULT 'Facebook'
);

-- 9. Contact Us පණිවිඩ සඳහා Table එක
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    service ENUM('Wedding', 'Engagement', 'Casual Session', 'Other') NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(255) NOT NULL,
    message TEXT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. Default Admin කෙනෙක් සාදා දත්ත ඇතුළත් කිරීම 
-- Username: admin | Password: admin123 (මෙහි පාස්වර්ඩ් එක ආරක්ෂිතව Bcrypt මගින් hash කර ඇත)
INSERT INTO admin_users (username, password) 
VALUES ('admin', '$2y$10$T1a/hHp6v5eKUis1k3J7oObB2LRTAZtVZE1ZJ5cpo2qYC.E5VWXXy')
ON DUPLICATE KEY UPDATE username=username;



ALTER TABLE weddings 
MODIFY COLUMN category ENUM(
    'Wedding', 
    'Bridal Shoot', 
    'Pre Shoot', 
    'Baby Shoot', 
    'Casual Shoot', 
    'Couple Shoot', 
    'Birthday Shoot'
) NOT NULL;