<?php
require_once('./constants/constants.php');
$pageTitle = 'Get top-rated web design & development services in India. We craft stunning, fast, and user-friendly websites to grow your business. Contact us today!.';
include('header.php');
?>

<main class="dx-home">
    <section class="dx-about-hero">
        <div class="container">
            <div class="dx-about-hero-grid" style="min-height: 300px;">
                <div class="dx-about-hero-copy" style="padding-top: 25px; padding-bottom: 15px;">
                    <span class="dx-eyebrow"><i class="fa-regular fa-sparkles"></i> DiginexAI Blog</span>
                    <h1>Latest insights from our tech experts.</h1>
                    <p>
                        Explore our latest articles on SEO, web development, and digital marketing
                        strategies designed to scale your business and drive results.
                    </p>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                            <li class="breadcrumb-item"><a href="index.php"
                                    style="color: rgba(255,255,255,0.7); text-decoration: none;">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"
                                style="color: #fff; font-weight: 700;">Blog</li>
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

    <section class="dx-section">
        <div class="container">
            <div class="row g-4" id="blogs-container">
                <div class="col-12" id="loading-state">
                    <div style="text-align: center; padding: 60px 20px;">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                        <p style="margin-top: 20px; font-size: 18px;">Loading blogs...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include('footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const blogsContainer = document.getElementById('blogs-container');
        const loadingState = document.getElementById('loading-state');

        const apiBaseUrl = '<?php echo defined("API_BASE_URL") ? API_BASE_URL : "https://api.diginexai.com"; ?>';

        function getApiUrl(path) {
            const cleanPath = path.startsWith('/') ? path.substring(1) : path;
            return apiBaseUrl.endsWith('/') ? apiBaseUrl + cleanPath : apiBaseUrl + '/' + cleanPath;
        }

        function getBlogUrl(slug) {
            // Correct URL format for the modernized setup
            return 'blogdetailpage.php?slug=' + encodeURIComponent(slug);
        }

        function showError() {
            blogsContainer.innerHTML = `
            <div class="col-12">
                <div style="text-align: center; padding: 100px 20px;">
                    <h2 style="color: #1e293b; font-weight: 800;">Error Loading Blogs</h2>
                    <p style="color: #64748b;">Unable to load blog posts. Please try again later.</p>
                    <button onclick="location.reload()" class="theme-btn" style="margin-top: 20px;">Retry</button>
                </div>
            </div>
        `;
        }

        fetch(getApiUrl('blogs.php'))
            .then(response => response.json())
            .then(data => {
                loadingState.remove();

                if (data.success && data.data && data.data.length > 0) {
                    blogsContainer.innerHTML = '';

                    data.data.forEach((blog, index) => {
                        const date = new Date(blog.created_at);
                        const formattedDate = date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }).toUpperCase();

                        const categoryName = blog.category_name || 'Web Development';
                        const blogImage = blog.blog_image || 'assets/img/blog/blogThumb1_1.jpg';
                        const blogUrl = getBlogUrl(blog.slug);
                        const excerpt = blog.excerpt || '';
                        const shortExcerpt = excerpt.length > 100 ? excerpt.substring(0, 100) + '...' : excerpt;

                        const blogCol = document.createElement('div');
                        blogCol.className = 'col-xl-4 col-lg-6 col-md-6 wow fadeInUp';
                        blogCol.setAttribute('data-wow-delay', (0.1 * (index % 3)) + 's');

                        blogCol.innerHTML = `
                        <div class="blog-card style1" style="height: 100%; display: flex; flex-direction: column;">
                            <div class="blog-card-thumb" style="height: 240px; overflow: hidden;">
                                <img src="${blogImage}" alt="${blog.title.replace(/"/g, '&quot;')}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='assets/img/blog/blogThumb1_1.jpg'" />
                            </div>
                            <div class="blog-card-body" style="padding: 30px; flex-grow: 1; display: flex; flex-direction: column;">
                                <div class="blog-meta" style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                    <div class="tag" style="background: #2563eb; color: #fff; padding: 6px 16px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase;">${categoryName}</div>
                                    <div class="date" style="color: #10b981; font-size: 12px; font-weight: 700;">${formattedDate}</div>
                                </div>
                                <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 15px; line-height: 1.4;">
                                    <a href="${blogUrl}" style="color: #1e293b; text-decoration: none; transition: color 0.3s ease;">${blog.title}</a>
                                </h3>
                                <p class="blog-excerpt" style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 30px;">${shortExcerpt}</p>
                                
                                <div class="author-meta" style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                                    <div class="fancy-box style1" style="display: flex; align-items: center; gap: 12px;">
                                        <div class="item" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                            <img src="assets/img/blog/blogProfile1_1.png" alt="Author" style="width: 100%; height: 100%; object-fit: cover;" />
                                        </div>
                                        <div class="item">
                                            <h6 style="font-size: 13px; font-weight: 800; color: #1e293b; margin: 0;">${blog.author_name || 'Diginex Team'}</h6>
                                            <p style="font-size: 11px; color: #64748b; margin: 0; font-weight: 600;">Author</p>
                                        </div>
                                    </div>
                                    <a class="link-btn style1" href="${blogUrl}" style="width: 36px; height: 36px; background: #2563eb; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s ease;">
                                        <i class="fa-solid fa-arrow-right" style="font-size: 14px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                        blogsContainer.appendChild(blogCol);
                    });

                    if (typeof WOW !== 'undefined') {
                        new WOW().init();
                    }
                } else {
                    showError();
                }
            })
            .catch(error => {
                console.error('Error fetching blogs:', error);
                loadingState.remove();
                showError();
            });
    });
</script>