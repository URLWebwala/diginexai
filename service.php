<?php 
$pageTitle = 'Full-Service Digital Marketing Solutions | DiginexAI';
$description ='Transform your business with our digital marketing expertise from strategy to execution, we help brands grow, engage audiences, and drive measurable results.';
include('header.php');
?>

<main class="dx-home">
    <!-- Sub-page Hero Section -->
    <section class="dx-about-hero">
        <div class="container">
            <div class="dx-about-hero-grid">
                <div class="dx-about-hero-copy">
                    <span class="dx-eyebrow"><i class="fa-regular fa-sparkles"></i> DiginexAI Services</span>
                    <h1>Strategic growth solutions for modern teams.</h1>
                    <p>
                        We combine technical SEO, conversion-focused design, and performance 
                        marketing to help your brand reach the right audience and stay there.
                    </p>
                    <nav aria-label="breadcrumb" class="mt-4">
                        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                            <li class="breadcrumb-item"><a href="/diginexai/index.php" style="color: rgba(255,255,255,0.7); text-decoration: none;">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 700;">Services</li>
                        </ol>
                    </nav>
                </div>
                <div class="dx-about-hero-media">
                    <img src="assets/img/hero/hero-2.jpg" alt="Our services overview">
                    <div class="dx-about-stat">
                        <strong>100%</strong>
                        <span>Performance driven execution for every project.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Grid Section -->
    <section class="dx-section dx-services">
        <div class="container">
            <div class="dx-section-head dx-section-head-center">
                <span class="dx-kicker">Our Expertise</span>
                <h2>Everything your digital presence needs to thrive.</h2>
                <p>Pick a focused service or let our team build the full growth stack from strategy to launch.</p>
            </div>
            
            <div class="dx-card-grid">
                <!-- Graphic Design & Branding -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-pen-nib"></i>
                    <h3>Graphic Design & Branding</h3>
                    <p>Logos, brochures, UI/UX layouts, and social media designs that bring your brand’s story to life.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>

                <!-- SEO -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-chart-line-up"></i>
                    <h3>SEO Optimization</h3>
                    <p>Rank higher on Google with expert SEO strategies that boost visibility, traffic, and measurable growth.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>

                <!-- Social Media Marketing -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-bullhorn"></i>
                    <h3>Social Media Marketing</h3>
                    <p>Engaging campaigns for Instagram, Facebook & LinkedIn to grow reach and build engagement.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>

                <!-- Paid Ads -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-rectangle-ad"></i>
                    <h3>Paid Ads (Google & Meta)</h3>
                    <p>ROI-driven Google Ads and Meta Ads campaigns designed to generate high-quality leads.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>

                <!-- Content Marketing -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-pen-nib"></i>
                    <h3>Content Marketing</h3>
                    <p>Creative blogs, posts, and campaigns that connect with your audience and inspire action.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>

                <!-- Website Development -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-browser"></i>
                    <h3>Website Development</h3>
                    <p>Responsive, SEO-friendly websites with UI/UX design that ensures high conversions.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>

                <!-- Product Shoot -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-camera"></i>
                    <h3>Product Shoot & Videos</h3>
                    <p>High-quality product photography and creative video reels that grab attention fast.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>

                <!-- App Development -->
                <article class="dx-service-card">
                    <i class="fa-regular fa-mobile"></i>
                    <h3>App Development</h3>
                    <p>Custom Android & iOS app development with modern UI/UX and powerful performance.</p>
                    <a href="contact.php">Learn more <i class="fa-regular fa-arrow-right"></i></a>
                </article>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="dx-section dx-about" style="background: #f7f9ff;">
        <div class="container">
            <div class="dx-about-grid">
                <div class="dx-about-media">
                    <img src="assets/img/faq/faq_premium_support.png" alt="DiginexAI Support & Strategy" style="box-shadow: 0 24px 64px rgba(18,63,140,0.12); border-radius: 20px;">
                </div>
                <div class="dx-about-copy">
                    <span class="dx-kicker">Common Questions</span>
                    <h2>Everything you need to know about our approach.</h2>
                    
                    <div class="dx-faq-wrapper mt-5">
                        <style>
                            .dx-faq-item {
                                background: #fff;
                                border: 1px solid #dde7fb;
                                border-radius: 8px;
                                margin-bottom: 16px;
                                overflow: hidden;
                            }
                            .dx-faq-btn {
                                width: 100%;
                                padding: 22px 28px;
                                text-align: left;
                                background: none;
                                border: none;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                font-weight: 700;
                                color: #111322;
                                font-size: 18px;
                                cursor: pointer;
                                transition: background 0.2s;
                            }
                            .dx-faq-btn:hover { background: #f8fbff; }
                            .dx-faq-btn i { color: #123f8c; transition: transform 0.3s; }
                            .dx-faq-btn[aria-expanded="true"] i { transform: rotate(180deg); }
                            .dx-faq-body { padding: 0 28px 22px; color: #586179; display: none; line-height: 1.7; }
                            .dx-faq-item.active .dx-faq-body { display: block; }
                        </style>

                        <div class="dx-faq-item">
                            <button class="dx-faq-btn" type="button">
                                What does DiginexAI specialize in? <i class="fa-regular fa-chevron-down"></i>
                            </button>
                            <div class="dx-faq-body">
                                We help businesses grow online through SEO, paid ads, social media, content and analytics boosting visibility, engagement and conversions.
                            </div>
                        </div>

                        <div class="dx-faq-item">
                            <button class="dx-faq-btn" type="button">
                                How long before I see real results? <i class="fa-regular fa-chevron-down"></i>
                            </button>
                            <div class="dx-faq-body">
                                Most campaigns show visible traction within 4–8 weeks, while SEO and brand-building efforts deliver stronger, lasting growth over 3–6 months.
                            </div>
                        </div>

                        <div class="dx-faq-item">
                            <button class="dx-faq-btn" type="button">
                                How do you fit my business goals? <i class="fa-regular fa-chevron-down"></i>
                            </button>
                            <div class="dx-faq-body">
                                We begin with a deep audit of your business, audience, and competitors, then tailor data-backed strategies that align perfectly with your growth goals.
                            </div>
                        </div>

                        <div class="dx-faq-item">
                            <button class="dx-faq-btn" type="button">
                                How can I track the success and ROI? <i class="fa-regular fa-chevron-down"></i>
                            </button>
                            <div class="dx-faq-body">
                                We provide detailed reports and live dashboards showing performance metrics like clicks, conversions, engagement, and return on ad spend (ROAS).
                            </div>
                        </div>
                    </div>

                    <script>
                        document.querySelectorAll('.dx-faq-btn').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const item = btn.parentElement;
                                const isActive = item.classList.contains('active');
                                
                                // Close all others
                                document.querySelectorAll('.dx-faq-item').forEach(i => i.classList.remove('active'));
                                
                                if (!isActive) {
                                    item.classList.add('active');
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include('footer.php');?>