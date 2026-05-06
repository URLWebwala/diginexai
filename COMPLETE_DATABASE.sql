-- ============================================
-- DIGINEXAI COMPLETE DATABASE SCHEMA
-- Production Ready - Import this file
-- ============================================

CREATE DATABASE IF NOT EXISTS u841045341_diginexai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE u841045341_diginexai;

-- ============================================
-- ADMIN USERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS admins (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TESTIMONIALS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS testimonials (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    rating DECIMAL(2,1) DEFAULT 5.0,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BLOG CATEGORIES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS blog_categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BLOGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS blogs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    slug VARCHAR(500) NOT NULL UNIQUE,
    category_id INT(11) NOT NULL,
    content LONGTEXT NOT NULL,
    author_name VARCHAR(255) NOT NULL,
    blog_image VARCHAR(255) DEFAULT NULL,
    canonical_url VARCHAR(500) DEFAULT NULL,
    seo_url VARCHAR(500) DEFAULT NULL,
    robots VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_category (category_id),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CLIENTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS clients (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website_url VARCHAR(500) DEFAULT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SEO DATA TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS seo_data (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    page_name VARCHAR(255) NOT NULL UNIQUE,
    title VARCHAR(500) NOT NULL,
    page_title VARCHAR(500) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    keywords TEXT DEFAULT NULL,
    author_name VARCHAR(255) DEFAULT NULL,
    seo_url VARCHAR(500) NOT NULL UNIQUE,
    canonical_url VARCHAR(500) DEFAULT NULL,
    robots VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_page_name (page_name),
    INDEX idx_seo_url (seo_url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CONTACT US TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS contact_us (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    service VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DEFAULT DATA
-- ============================================

-- Insert default blog categories
INSERT INTO blog_categories (name, slug) VALUES
('Web Development', 'web-development'),
('App Solutions', 'app-solutions'),
('Android App', 'android-app'),
('UI/UX Design', 'ui-ux-design')
ON DUPLICATE KEY UPDATE name=name;

-- Insert SEO data for all pages
INSERT INTO seo_data (page_name, title, page_title, description, keywords, author_name, seo_url, canonical_url, robots) VALUES
-- Home Page
('Home', 
 'Top Digital Marketing & Branding Agency UK | DiginexAI', 
 'Top Digital Marketing & Branding Agency UK | DiginexAI',
 'Boost your business with expert digital marketing services, SEO, PPC, social media, web design, graphic design, and branding solutions that drive growth.',
 'digital marketing, SEO, PPC, social media marketing, web design, graphic design, branding, UK, DiginexAI',
 'DiginexAI Team',
 'home',
 'https://www.diginexai.com/',
 'index, follow'),

-- About Us Page
('About Us',
 'About Us - Digital Marketing Agency | DiginexAI',
 'About Us - Digital Marketing Agency | DiginexAI',
 'Learn about DiginexAI, a results-driven branding and digital marketing agency blending creativity with strategy to build strong brands and drive measurable growth.',
 'about us, digital marketing agency, branding agency, DiginexAI, company history',
 'DiginexAI Team',
 'about-us',
 'https://www.diginexai.com/about',
 'index, follow'),

-- Services Page
('Services',
 'Our Services - Digital Marketing & Branding Services | DiginexAI',
 'Our Services - Digital Marketing & Branding Services | DiginexAI',
 'Explore our comprehensive digital marketing and branding services including SEO, PPC, social media marketing, web design, graphic design, and more.',
 'digital marketing services, SEO services, PPC services, social media marketing, web design, graphic design, branding services',
 'DiginexAI Team',
 'services',
 'https://www.diginexai.com/services',
 'index, follow'),

-- Blog Page
('Blog',
 'Blog - Digital Marketing Insights & News | DiginexAI',
 'Blog - Digital Marketing Insights & News | DiginexAI',
 'Read the latest digital marketing insights, tips, news, and strategies from DiginexAI experts. Stay updated with industry trends and best practices.',
 'blog, digital marketing blog, SEO blog, marketing tips, marketing news, marketing insights',
 'DiginexAI Team',
 'blog',
 'https://www.diginexai.com/blog',
 'index, follow'),

-- Contact Page
('Contact',
 'Contact Us - Get in Touch | DiginexAI',
 'Contact Us - Get in Touch | DiginexAI',
 'Get in touch with DiginexAI for expert digital marketing and branding services. Contact us today for a free consultation and quote.',
 'contact us, get in touch, digital marketing consultation, free quote, DiginexAI contact',
 'DiginexAI Team',
 'contact',
 'https://www.diginexai.com/contact',
 'index, follow'),

-- 404 Error Page
('404',
 'Page Not Found - 404 Error | DiginexAI',
 'Page Not Found - 404 Error | DiginexAI',
 'The page you are looking for could not be found. Return to our homepage or browse our services and blog.',
 '404, page not found, error page',
 'DiginexAI Team',
 '404',
 'https://www.diginexai.com/404',
 'noindex, follow')

ON DUPLICATE KEY UPDATE 
    title = VALUES(title),
    page_title = VALUES(page_title),
    description = VALUES(description),
    keywords = VALUES(keywords),
    author_name = VALUES(author_name),
    seo_url = VALUES(seo_url),
    canonical_url = VALUES(canonical_url),
    robots = VALUES(robots),
    updated_at = CURRENT_TIMESTAMP;

-- ============================================
-- ADMIN USER SETUP
-- ============================================
-- IMPORTANT: Use setup_diginex_admin.php to create admin user
-- This will generate proper password hash
-- Access: https://admin.diginexai.com/setup_diginex_admin.php
-- ============================================

-- Delete existing admin users (optional)
-- DELETE FROM admins WHERE username = 'admin' OR username = 'diginex';

-- ============================================
-- DATABASE SETUP COMPLETE
-- ============================================
-- Next Steps:
-- 1. Update database credentials in config files
-- 2. Create admin user using setup_diginex_admin.php
-- 3. Test all functionality
-- ============================================

