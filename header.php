<?php 
if (!defined('API_BASE_URL')) {
    require_once('./constants/constants.php');
}

// Get current page name for SEO
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$page_name_map = [
    'index' => 'Home',
    'about' => 'About Us',
    'service' => 'Services',
    'blog' => 'Blog',
    'contact' => 'Contact',
    'contac' => 'Contact',
    '404' => '404'
];

$seo_page_name = $page_name_map[$current_page] ?? 'Home';

// Fetch SEO data from API
$seo_data = null;
require_once('./constants/constants.php');
$seo_api_url = API_BASE_URL . '/seo.php?page_name=' . urlencode($seo_page_name);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $seo_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$seo_response = @curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200 && $seo_response) {
    $seo_result = json_decode($seo_response, true);
    if ($seo_result && isset($seo_result['success']) && $seo_result['success'] && isset($seo_result['data'])) {
        $seo_data = $seo_result['data'];
    }
}

// Use SEO data if available, otherwise use defaults
$page_title = $seo_data['title'] ?? ($pageTitle ?? '');
$meta_description = $seo_data['description'] ?? ($description ?? '');
$meta_keywords = $seo_data['keywords'] ?? '';
$canonical_url = $seo_data['canonical_url'] ?? '';
$robots = $seo_data['robots'] ?? 'index, follow';
$author_name = $seo_data['author_name'] ?? '';

// Set default title if empty
if (empty($page_title)) {
    $page_title = COMPANY_TITLT;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Base URL - only for blog detail pages to fix asset paths -->
    <?php 
    // Define variables early for use throughout header
    $isBlogDetail = (basename($_SERVER['PHP_SELF']) == 'blogdetailpage.php');
    
    // Absolute base path detection
    $hostname = $_SERVER['HTTP_HOST'] ?? '';
    $isProduction = (strpos($hostname, 'diginexai.com') !== false);
    
    if ($isProduction) {
        $baseDir = '/';
    } else {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = dirname($scriptName);
        if ($dir === '/' || $dir === '\\') {
            $baseDir = '/';
        } else {
            $baseDir = rtrim($dir, '/\\') . '/';
        }
    }

    // Asset base path logic
    $assetBase = ''; 
    if ($isBlogDetail) {
        // For blog detail, we use the detected base directory
        echo '<base href="' . $baseDir . '">';
    }
    ?>
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>" />
    <?php if (!empty($meta_keywords)): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>" />
    <?php endif; ?>
    <?php if (!empty($author_name)): ?>
    <meta name="author" content="<?php echo htmlspecialchars($author_name); ?>" />
    <?php endif; ?>
    <meta name="robots" content="<?php echo htmlspecialchars($robots); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.diginexai.com" />
    <meta property="og:site_name" content="<?php echo (!empty(COMPANY_TITLT) ? COMPANY_TITLT : 'Diginex AI'); ?>" />
    <meta property="og:image" content="https://diginexai.com/assets/metalog.png" />
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>" />
    <?php if (!empty($canonical_url)): ?>
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>" />
    <?php else: ?>
    <link rel="canonical" href="https://www.diginexai.com/<?php echo(isset($_SERVER['REQUEST_URI']) ? basename($_SERVER['REQUEST_URI']) : ''); ?>" />
    <?php endif; ?>
    <link rel="icon" type="image/x-icon" href="<?php echo $assetBase; ?>assets/img/favicon.jpg" />
    
    <!-- Fix malformed URLs IMMEDIATELY - runs before any other scripts -->
    <script>
    (function() {
        var url = window.location.href;
        if (url.indexOf('/C:/') !== -1 || url.indexOf('/xampp/') !== -1 || url.indexOf('/htdocs/') !== -1 || url.indexOf('/public_html/public_html/') !== -1) {
            var slugMatch = url.match(/[?&]slug=([^&]+)/);
            var idMatch = url.match(/[?&]id=([^&]+)/);
            var slug = slugMatch ? decodeURIComponent(slugMatch[1]) : '';
            var id = idMatch ? decodeURIComponent(idMatch[1]) : '';
            
            // Use clean URLs - PREVENTS file path issues
            if (slug && slug !== '0' && slug !== '') {
                window.location.replace('<?php echo $basePath; ?>/blog/' + encodeURIComponent(slug));
            } else if (id && id !== '0' && id !== '') {
                // If ID is present but slug is missing, redirect to blog listing
                // The PHP will handle fetching slug from ID
                window.location.replace('<?php echo $basePath; ?>/blogdetailpage.php?id=' + encodeURIComponent(id));
            } else {
                window.location.replace('<?php echo $basePath; ?>/blog');
            }
        }
    })();
    </script>
    
    <!-- Google Console Property Verification -->
    <meta name="google-site-verification" content="Qwr4SJWiwpwCepWOY0K8LGr56M7pYisJOca3EUCK8SU" />

    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Urlwebwala LLP",
        "logo": "https://diginexai.com/assets/metalog.png",
        "image": "https://diginexai.com/assets/metalog.png",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Ahmedabad, Gujarat",
            "addressLocality": "Ahmedabad",
            "postalCode": "382481",
            "addressCountry": "IN"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+91 6359433164",
            "contactType": "customer service"
        },
        "sameAs": [
            "https://www.facebook.com/urlwebwala",
            "https://www.linkedin.com/company/urlwebwala",
            "https://twitter.com/urlwebwala"
        ]
    }
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-K44KM432');</script>
    <!-- End Google Tag Manager -->
    
    <!-- Disable GTM form tracking for contact form -->
    <script>
    // Override GTM form tracking IMMEDIATELY
    (function() {
        window.dataLayer = window.dataLayer || [];
        var originalPush = window.dataLayer.push;
        window.dataLayer.push = function() {
            var args = Array.prototype.slice.call(arguments);
            // Block ALL form events for contact-form
            if (args[0] && typeof args[0] === 'object') {
                var eventData = args[0];
                // Block form_start
                if (eventData.event === 'form_start' && eventData['ep.form_id'] === 'contact-form') {
                    console.log('🚫 Blocked GTM form_start event');
                    return;
                }
                // Block form_submit
                if (eventData.event === 'form_submit' && eventData['ep.form_id'] === 'contact-form') {
                    console.log('🚫 Blocked GTM form_submit event');
                    return;
                }
                // Block any event with form_id = contact-form
                if (eventData['ep.form_id'] === 'contact-form') {
                    console.log('🚫 Blocked GTM event for contact-form:', eventData.event);
                    return;
                }
            }
            return originalPush.apply(window.dataLayer, args);
        };
    })();
    </script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-462PXTM4SX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-462PXTM4SX');
      
      // Disable form tracking for contact form
      document.addEventListener('DOMContentLoaded', function() {
          var contactForm = document.getElementById('contact-form');
          if (contactForm) {
              // Prevent GA from tracking this form
              contactForm.addEventListener('submit', function(e) {
                  e.stopImmediatePropagation();
              }, true);
          }
      });
    </script>

    <!-- CSS Files -->
    <link rel="shortcut icon" href="<?php echo $assetBase; ?>assets/img/favicon.jpg" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/all.min.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/animate.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/magnific-popup.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/meanmenu.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/nice-select.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/main.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/new.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/testinomial.css" />
    <link rel="stylesheet" href="<?php echo $assetBase; ?>assets/css/home-redesign.css" />
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K44KM432"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner"></div>
            <div class="txt-loading">
                <?php foreach (str_split("DIGINEXAI") as $letter): ?>
                <span data-text-preloader="<?php echo $letter; ?>" class="letters-loading"><?php echo $letter; ?></span>
                <?php endforeach; ?>
            </div>
            <p class="text-center">Loading</p>
        </div>
        <div class="loader">
            <div class="row">
                <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="col-3 loader-section <?php echo $i < 2 ? 'section-left' : 'section-right'; ?>">
                    <div class="bg"></div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Mouse Cursor -->
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    <!-- Offcanvas Menu -->
    <div class="fix-area">
        <div class="offcanvas__info">
            <div class="offcanvas__wrapper">
                <div class="offcanvas__content">
                    <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                        <div class="offcanvas__logo">
                            <a href="<?php echo $baseDir; ?>index.php"><img src="assets/img/diginexAilogo.png" alt="logo-img" /></a>
                        </div>
                        <div class="offcanvas__close">
                            <button><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <p class="text d-none d-lg-block">
                        At Diginex, we are a results-driven branding and digital marketing
                        agency blending creativity with strategy to build strong brands
                        and drive measurable growth.
                    </p>
                    <div class="mobile-menu mb-4">
                        <ul>
                            <li><a href="<?php echo $baseDir; ?>index.php">Home</a></li>
                            <li><a href="<?php echo $baseDir; ?>about.php">About Us</a></li>
                            <li><a href="<?php echo $baseDir; ?>service.php">Services</a></li>
                            <li><a href="<?php echo $baseDir; ?>blog.php">Blog</a></li>
                            <li><a href="<?php echo $baseDir; ?>contact.php">Contact</a></li>
                        </ul>
                    </div>
                    <div class="offcanvas__contact">
                        <h4>Contact Info</h4>
                        <ul>
                            <li>
                                <i class="fal fa-map-marker-alt"></i> Ahmedabad, Gujarat
                                (382481)
                            </li>
                            <li><i class="fal fa-envelope"></i> info@diginexai.com</li>
                            <li><i class="far fa-phone"></i> +91 8140881396</li>
                        </ul>
                        <div class="header-button">
                            <a href="<?php echo $baseDir; ?>contact.php" class="theme-btn bg-white"><span>Book A Demo
                                    <i class="fa-solid fa-arrow-right-long"></i></span></a>
                        </div>
                        <div class="social-icon d-flex align-items-center">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas__overlay"></div>

    <!-- Header -->
    <header>
        <div id="header-sticky" class="header-3">
            <div class="container">
                <div class="mega-menu-wrapper">
                    <div class="header-main">
                        <div class="header-left">
                            <div class="logo">
                                <a href="<?php echo $baseDir; ?>index.php" class="header-logo">
                                    <img src="assets/img/diginexAilogo.png" alt="logo" style="width: 160px" />
                                </a>
                            </div>
                        </div>
                        <div class="header-right d-flex justify-content-end align-items-center">
                            <div class="mean__menu-wrapper">
                                <div class="main-menu">
                                    <ul>
                                        <li><a href="<?php echo $baseDir; ?>index.php">Home</a></li>
                                        <li><a href="<?php echo $baseDir; ?>about.php">About Us</a></li>
                                        <li><a href="<?php echo $baseDir; ?>service.php">Services</a></li>
                                        <li><a href="<?php echo $baseDir; ?>blog.php">Blog</a></li>
                                        <li><a href="<?php echo $baseDir; ?>contact.php">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="header-button">
                                <a href="<?php echo $baseDir; ?>contact.php" class="theme-btn bg-white">
                                    <span>Book A Demo <i class="fa-solid fa-arrow-right-long"></i></span>
                                </a>
                            </div>
                            <div class="header__hamburger d-lg-none my-auto">
                                <div class="sidebar__toggle">
                                    <i class="fas fa-bars"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>

</html>
