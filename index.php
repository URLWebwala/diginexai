<?php
$pageTitle = 'DiginexAI - Digital Marketing, Web Design and Growth Technology Services';
$description = 'DiginexAI builds fast websites, brand systems, SEO campaigns, and digital growth programs for ambitious teams.';
include('header.php');
 
// Fetch Data from API
function fetchApiData($endpoint) {
    $url = API_BASE_URL . $endpoint;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $res = curl_exec($ch);
    curl_close($ch);
    if ($res) {
        $json = json_decode($res, true);
        if ($json && isset($json['success']) && $json['success']) {
            return $json['data'];
        }
    }
    return [];
}
 
$testimonials_data = fetchApiData('/testimonials.php');
$blogs_data = fetchApiData('/blogs.php');
?>

<main class="dx-home">
    <section class="dx-hero">
        <div class="container">
            <div class="dx-hero-slider" data-dx-slider>
                <div class="dx-hero-track">
                    <article class="dx-hero-slide is-active" data-slide="0">
                        <div class="dx-hero-grid">
                            <div class="dx-hero-copy">
                                <span class="dx-eyebrow"><i class="fa-regular fa-chart-line-up"></i> Premium SEO & Growth</span>
                                <h1>Rank #1 with expert SEO and AI-driven growth.</h1>
                                <p>
                                    We combine technical SEO, content strategy, and AI-powered data to ensure your brand 
                                    leads the search results and captures high-intent traffic.
                                </p>
                                <div class="dx-hero-actions">
                                    <a class="dx-btn dx-btn-primary" href="contact.php">Start Ranking <i class="fa-regular fa-arrow-right-long"></i></a>
                                    <a class="dx-btn dx-btn-light" href="service.php">Our SEO Stack</a>
                                </div>
                                <div class="dx-proof-row" aria-label="Company results">
                                    <div><strong>#1</strong><span>Rankings achieved</span></div>
                                    <div><strong>250%</strong><span>Organic growth</span></div>
                                    <div><strong>ROI</strong><span>Focused strategy</span></div>
                                </div>
                            </div>
                            <div class="dx-hero-media dx-hero-photo">
                                <img src="assets/img/hero/hero-seo.png" alt="SEO expert reviewing growth charts">
                                <div class="dx-floating-note dx-note-one">AI Driven</div>
                                <div class="dx-floating-note dx-note-two">Data First</div>
                            </div>
                        </div>
                    </article>

                    <article class="dx-hero-slide" data-slide="1">
                        <div class="dx-hero-grid">
                            <div class="dx-hero-copy dx-hero-copy-alt">
                                <span class="dx-eyebrow"><i class="fa-regular fa-bullhorn"></i> Precision Marketing</span>
                                <h1>Dominate your market with expert Digital Marketing.</h1>
                                <p>
                                    From high-ROI Meta & Google Ads to social media dominance, we build marketing 
                                    funnels that turn casual browsers into loyal customers.
                                </p>
                                <div class="dx-hero-actions">
                                    <a class="dx-btn dx-btn-primary" href="contact.php">Launch Campaign <i class="fa-regular fa-arrow-right-long"></i></a>
                                    <a class="dx-btn dx-btn-light" href="service.php">Marketing Services</a>
                                </div>
                                <div class="dx-proof-row" aria-label="Marketing results">
                                    <div><strong>5X</strong><span>Average ROI</span></div>
                                    <div><strong>10M+</strong><span>Ad reach</span></div>
                                    <div><strong>Scale</strong><span>Ready systems</span></div>
                                </div>
                            </div>
                            <div class="dx-hero-media dx-hero-photo">
                                <img src="assets/img/hero/hero-marketing.png" alt="Digital marketing team collaborating">
                                <div class="dx-floating-note dx-note-one">Ads Optimized</div>
                                <div class="dx-floating-note dx-note-two">Lead Gen</div>
                            </div>
                        </div>
                    </article>

                    <article class="dx-hero-slide" data-slide="2">
                        <div class="dx-hero-grid">
                            <div class="dx-hero-copy dx-hero-copy-green">
                                <span class="dx-eyebrow"><i class="fa-regular fa-code"></i> Advanced Web Apps</span>
                                <h1>Custom Web App solutions built for modern scale.</h1>
                                <p>
                                    We build powerful, scalable, and secure web applications and SaaS products 
                                    designed to solve complex business problems with clean code.
                                </p>
                                <div class="dx-hero-actions">
                                    <a class="dx-btn dx-btn-primary" href="contact.php">Build My App <i class="fa-regular fa-arrow-right-long"></i></a>
                                    <a class="dx-btn dx-btn-light" href="service.php">Our Tech Stack</a>
                                </div>
                                <div class="dx-proof-row" aria-label="Development results">
                                    <div><strong>SaaS</strong><span>Specialists</span></div>
                                    <div><strong>Clean</strong><span>Architecture</span></div>
                                    <div><strong>Scale</strong><span>On-demand</span></div>
                                </div>
                            </div>
                            <div class="dx-hero-media dx-hero-photo">
                                <img src="assets/img/hero/hero-webapp.png" alt="Software architect reviewing app code">
                                <div class="dx-floating-note dx-note-one">SaaS Ready</div>
                                <div class="dx-floating-note dx-note-two">API Driven</div>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="dx-slider-controls" aria-label="Hero slider controls">
                    <button class="dx-slider-btn" type="button" data-dx-prev aria-label="Previous slide">
                        <i class="fa-regular fa-arrow-left"></i>
                    </button>
                    <div class="dx-slider-dots" role="tablist" aria-label="Hero slides">
                        <button class="is-active" type="button" data-dx-dot="0" aria-label="Show slide 1"></button>
                        <button type="button" data-dx-dot="1" aria-label="Show slide 2"></button>
                        <button type="button" data-dx-dot="2" aria-label="Show slide 3"></button>
                    </div>
                    <button class="dx-slider-btn" type="button" data-dx-next aria-label="Next slide">
                        <i class="fa-regular fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var slider = document.querySelector('[data-dx-slider]');
        if (!slider) return;

        var slides = Array.prototype.slice.call(slider.querySelectorAll('.dx-hero-slide'));
        var dots = Array.prototype.slice.call(slider.querySelectorAll('[data-dx-dot]'));
        var prev = slider.querySelector('[data-dx-prev]');
        var next = slider.querySelector('[data-dx-next]');
        var current = 0;
        var timer;

        function showSlide(index) {
            current = (index + slides.length) % slides.length;
            slides.forEach(function (slide, slideIndex) {
                slide.classList.toggle('is-active', slideIndex === current);
            });
            dots.forEach(function (dot, dotIndex) {
                dot.classList.toggle('is-active', dotIndex === current);
            });
        }

        function start() {
            stop();
            timer = window.setInterval(function () {
                showSlide(current + 1);
            }, 5200);
        }

        function stop() {
            if (timer) window.clearInterval(timer);
        }

        if (prev) {
            prev.addEventListener('click', function () {
                showSlide(current - 1);
                start();
            });
        }

        if (next) {
            next.addEventListener('click', function () {
                showSlide(current + 1);
                start();
            });
        }

        dots.forEach(function (dot, dotIndex) {
            dot.addEventListener('click', function () {
                showSlide(dotIndex);
                start();
            });
        });

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        slider.addEventListener('focusin', stop);
        slider.addEventListener('focusout', start);
        start();
 
        // Testimonial Swiper Initialization
        new Swiper('.dx-testimonial-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 3,
                }
            }
        });
    });
    </script>

    <section class="dx-logo-strip" aria-label="Trusted platform experience">
        <div class="container">
            <div class="dx-logo-grid">
                <span>Airtable</span>
                <span>Razorpay</span>
                <span>Contentful</span>
                <span>Culture Amp</span>
                <span>Dropbox</span>
            </div>
        </div>
    </section>

    <section class="dx-section dx-services">
        <div class="container">
            <div class="dx-section-head">
                <span class="dx-kicker">Services</span>
                <h2>Everything your digital presence needs, without the noise.</h2>
                <p>Pick a focused service or let our team build the full growth stack from strategy to launch.</p>
            </div>
            <div class="dx-card-grid">
                <article class="dx-service-card">
                    <i class="fa-regular fa-pen-nib"></i>
                    <h3>Branding and Design</h3>
                    <p>Identity systems, campaign creatives, and polished design that make your brand easier to trust.</p>
                    <a href="service.php">View service <i class="fa-regular fa-arrow-right"></i></a>
                </article>
                <article class="dx-service-card">
                    <i class="fa-regular fa-browser"></i>
                    <h3>Website Development</h3>
                    <p>Responsive websites with clean UX, fast pages, and content structures your team can manage.</p>
                    <a href="service.php">View service <i class="fa-regular fa-arrow-right"></i></a>
                </article>
                <article class="dx-service-card">
                    <i class="fa-regular fa-chart-line-up"></i>
                    <h3>SEO and Performance</h3>
                    <p>Technical SEO, content planning, and conversion fixes that turn search visibility into revenue.</p>
                    <a href="service.php">View service <i class="fa-regular fa-arrow-right"></i></a>
                </article>
                <article class="dx-service-card">
                    <i class="fa-regular fa-bullhorn"></i>
                    <h3>Social Growth</h3>
                    <p>Content calendars, paid campaigns, and reporting that keeps your marketing machine moving.</p>
                    <a href="service.php">View service <i class="fa-regular fa-arrow-right"></i></a>
                </article>
            </div>
        </div>
    </section>

    <section class="dx-about">
        <div class="container">
            <div class="dx-about-grid">
                <div class="dx-about-media">
                    <img src="assets/img/home-strategy.png" alt="DiginexAI strategy and growth session">
                </div>
                <div class="dx-about-copy">
                    <span class="dx-kicker">About DiginexAI</span>
                    <h2>Clear strategy, sharp execution, measurable growth.</h2>
                    <p>
                        Our team works like an extension of yours. We audit what exists, identify the highest-value
                        opportunities, and build digital assets that are useful from day one.
                    </p>
                    <div class="dx-feature-list">
                        <div><i class="fa-regular fa-circle-check"></i><span>Brand and website audits</span></div>
                        <div><i class="fa-regular fa-circle-check"></i><span>Conversion-first landing pages</span></div>
                        <div><i class="fa-regular fa-circle-check"></i><span>SEO and analytics setup</span></div>
                        <div><i class="fa-regular fa-circle-check"></i><span>Ongoing support and optimization</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="dx-process">
        <div class="container">
            <div class="dx-section-head">
                <span class="dx-kicker">Process</span>
                <h2>From first brief to measurable traction.</h2>
            </div>
            <div class="dx-process-grid">
                <div><span>01</span><h3>Discover</h3><p>We map your goals, audience, competitors, and current digital gaps.</p></div>
                <div><span>02</span><h3>Design</h3><p>We shape the messaging, user flows, visuals, and conversion paths.</p></div>
                <div><span>03</span><h3>Build</h3><p>We develop the website, campaign assets, tracking, and content structure.</p></div>
                <div><span>04</span><h3>Optimize</h3><p>We review performance and keep improving what drives results.</p></div>
            </div>
        </div>
    </section>

    <section class="dx-section dx-industries" style="padding-bottom: 40px;">
        <div class="container">
            <div class="dx-section-head dx-section-head-center">
                <span class="dx-kicker">Served more than 12+ industries</span>
                <h2>Industries We Have Served</h2>
                <p>We have delivered customized solutions for more than 50+ clients across more than 12+ industries.</p>
            </div>
            <div class="dx-industries-grid">
                <article><i class="fa-solid fa-cart-shopping"></i><h3>Fashion & E-Commerce</h3></article>
                <article><i class="fa-solid fa-industry"></i><h3>Manufacturing</h3></article>
                <article><i class="fa-solid fa-hotel"></i><h3>Hospitality</h3></article>
                <article><i class="fa-solid fa-heart-pulse"></i><h3>Healthcare & Fitness</h3></article>
                <article><i class="fa-solid fa-building-columns"></i><h3>Accounting & Finance</h3></article>
                <article><i class="fa-solid fa-users"></i><h3>News & Media</h3></article>
                <article><i class="fa-solid fa-utensils"></i><h3>Food & Restaurant</h3></article>
                <article><i class="fa-solid fa-building"></i><h3>Real Estate & Property</h3></article>
                <article><i class="fa-solid fa-mobile-screen-button"></i><h3>On-Demand Solutions</h3></article>
                <article><i class="fa-solid fa-truck"></i><h3>Logistics & Distribution</h3></article>
                <article><i class="fa-solid fa-book"></i><h3>Education & eLearning</h3></article>
                <article><i class="fa-solid fa-bus"></i><h3>Transport & Automotive</h3></article>
            </div>
        </div>
    </section>

    <section class="dx-section dx-partners" style="background: #ffffff; padding-top: 40px; padding-bottom: 60px;">
        <div class="container">
            <div class="dx-section-head dx-section-head-center">
                <span class="dx-kicker">Our Partners</span>
                <h2>Strategic Ecosystem Partners</h2>
                <p>We collaborate with specialized platforms to deliver end-to-end digital and organizational excellence.</p>
            </div>
            <div class="dx-card-grid" style="grid-template-columns: repeat(2, 1fr); max-width: 960px; margin: 0 auto; gap: 30px;">
                <article class="dx-service-card" style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 40px;">
                    <div style="height: 80px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                        <img src="assets/img/partners/hrmswala.png" alt="HRMSWala Logo" style="max-height: 100%; width: auto;">
                    </div>
                    <h3 style="font-size: 24px; margin-bottom: 12px;">HRMSWala</h3>
                    <p style="font-size: 15px; color: #445375;">Advanced human resource management systems designed to automate payroll, tracking, and team performance.</p>
                </article>
                <article class="dx-service-card" style="display: flex; flex-direction: column; align-items: center; text-align: center; padding: 40px;">
                    <div style="height: 80px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                        <img src="assets/img/partners/urlwebwala.png" alt="URLWebwala Logo" style="max-height: 100%; width: auto;">
                    </div>
                    <h3 style="font-size: 24px; margin-bottom: 12px;">URLWebwala</h3>
                    <p style="font-size: 15px; color: #445375;">Specialized web development partner focused on high-performance architecture and bespoke digital solutions.</p>
                </article>
            </div>
        </div>
    </section>
    <section class="dx-section dx-testimonials" style="background: #fff; padding: 60px 0 40px;">
        <div class="container">
            <div class="dx-section-head dx-section-head-center">
                <span class="dx-kicker">Testimonials</span>
                <h2>What Our Clients Say</h2>
                <p>Real feedback from businesses we've helped grow through digital innovation.</p>
            </div>
        </div>
        <div class="container" style="max-width: 1420px;">
            <div class="swiper testimonial-slider-2">
                <div class="swiper-wrapper">
                    <?php if (!empty($testimonials_data)): ?>
                        <?php foreach ($testimonials_data as $t): ?>
                            <div class="swiper-slide" style="padding-top: 50px;">
                                <article class="dx-modern-testimonial">
                                    <div class="dx-modern-avatar">
                                        <?php if (!empty($t['image'])): ?>
                                            <img src="<?php echo $t['image']; ?>" alt="<?php echo htmlspecialchars($t['client_name']); ?>">
                                        <?php else: ?>
                                            <div class="dx-avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="dx-modern-content">
                                        <h3><?php echo htmlspecialchars($t['client_name']); ?></h3>
                                        <span class="dx-company">Company Inc.</span>
                                        <p><?php echo htmlspecialchars($t['description']); ?></p>
                                        <div class="dx-modern-stars">
                                            <?php 
                                            $rating = round($t['rating']);
                                            for($i=1; $i<=5; $i++) echo '<i class="fa-solid fa-star"></i>';
                                            ?>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="swiper-pagination" style="margin-top: 50px; position: relative;"></div>
            </div>
        </div>
    </section>

    <section class="news-section-4 fix section-padding dx-work" style="background: #fff; padding: 40px 0 100px;">
        <div class="container">
            <div class="dx-section-head" style="margin-bottom: 40px;">
                <span class="dx-kicker">Latest Blog</span>
                <h2>Insights and updates from our team.</h2>
            </div>
            <div class="row g-4">
                <?php if (!empty($blogs_data)): ?>
                    <?php 
                    // Show only latest 3 blogs
                    $latest_blogs = array_slice($blogs_data, 0, 3);
                    foreach ($latest_blogs as $blog): 
                        $blog_img = !empty($blog['blog_image']) ? $blog['blog_image'] : 'assets/img/blog/blogThumb1_1.jpg';
                        $blog_date = !empty($blog['created_at']) ? date('M d, Y', strtotime($blog['created_at'])) : date('M d, Y');
                        $category_name = !empty($blog['category_name']) ? $blog['category_name'] : 'Web Development';
                    ?>
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="blog-card style1">
                                <div class="blog-card-thumb">
                                    <img src="<?php echo $blog_img; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" />
                                </div>
                                <div class="blog-card-body">
                                    <div class="blog-meta">
                                        <div class="tag"><?php echo htmlspecialchars($category_name); ?></div>
                                        <div class="date"><?php echo strtoupper($blog_date); ?></div>
                                    </div>
                                    <h3>
                                        <a href="blogdetailpage.php?slug=<?php echo $blog['slug']; ?>"><?php echo htmlspecialchars($blog['title']); ?></a>
                                    </h3>
                                    <p class="blog-excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                                    <div class="author-meta">
                                        <div class="fancy-box style1">
                                            <div class="item">
                                                <img src="assets/img/blog/blogProfile1_1.png" alt="Author" />
                                            </div>
                                            <div class="item">
                                                <h6><?php echo htmlspecialchars($blog['author_name'] ?? 'Admin'); ?></h6>
                                                <p>Author</p>
                                            </div>
                                        </div>
                                        <a class="link-btn style1" href="blogdetailpage.php?slug=<?php echo $blog['slug']; ?>">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

</main>


<?php include('footer.php'); ?>
