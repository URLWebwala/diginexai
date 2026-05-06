<?php 
require_once('./constants/constants.php');
$pageTitle = 'Get top-rated web design & development services in India. We craft stunning, fast, and user-friendly websites to grow your business. Contact us today!.';
include('header.php');
?>

<div style="padding-top: 0px">
    <div class="breadcrumb-wrapper bg-cover" style="background-image: url('assets/img/breadcrumb.jpg');">
        <div class="border-shape"><img src="assets/img/element.png" alt="shape-img"></div>
        <div class="line-shape"><img src="assets/img/line-element.png" alt="shape-img"></div>
        <div class="container">
            <div class="page-heading">
                <h1 class="wow fadeInUp" data-wow-delay=".3s">Blog Grid</h1>
                <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".5s">
                    <li><a href="index.php">Home</a></li>
                    <li><i class="fas fa-chevron-right"></i></li>
                    <li>Blog</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="news-section-4 fix section-padding">
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

<?php include('footer.php');?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const blogsContainer = document.getElementById('blogs-container');
    const loadingState = document.getElementById('loading-state');
    
    // Use API base URL from constants
    const apiBaseUrl = '<?php echo defined("API_BASE_URL") ? API_BASE_URL : "https://api.diginexai.com"; ?>';
    console.log('📡 Blog API Base URL:', apiBaseUrl);
    function getApiUrl(path) {
        // Remove leading slash from path if present, then add it
        const cleanPath = path.startsWith('/') ? path.substring(1) : path;
        const url = apiBaseUrl.endsWith('/') ? apiBaseUrl + cleanPath : apiBaseUrl + '/' + cleanPath;
        console.log('📡 Blog API URL:', url);
        return url;
    }
    
    function getBlogUrl(slug) {
        // Use clean URL format from .htaccess: /blog/slug
        return '/public_html/blog/' + encodeURIComponent(slug);
    }
    
    function showError() {
        blogsContainer.innerHTML = `
            <div class="col-12">
                <div style="text-align: center; padding: 60px 20px;">
                    <h2>Error Loading Blogs</h2>
                    <p>Unable to load blog posts. Please try again later.</p>
                    <button onclick="location.reload()" class="theme-btn" style="margin-top: 20px;">Retry</button>
                </div>
            </div>
        `;
    }
    
    fetch(getApiUrl('blogs.php'))
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok: ' + response.status);
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                return response.text().then(text => {
                    throw new Error('Server returned non-JSON response');
                });
            }
            return response.json();
        })
        .then(data => {
            loadingState.remove();
            
            if (data.success && data.data && data.data.length > 0) {
                blogsContainer.innerHTML = '';
                const delays = [0.3, 0.5, 0.7];
                
                data.data.forEach((blog, index) => {
                    const date = new Date(blog.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }).toUpperCase();
                    const categoryName = blog.category_name || 'Uncategorized';
                    const blogImage = blog.blog_image || 'assets/img/blog/blogThumb1_1.jpg';
                    const blogUrl = getBlogUrl(blog.slug);
                    const excerpt = blog.excerpt || '';
                    const shortExcerpt = excerpt.length > 120 ? excerpt.substring(0, 120) + '...' : excerpt;
                    const delay = delays[index % 3];
                    
                    const blogCard = document.createElement('div');
                    blogCard.className = 'col-xl-4 col-lg-6 col-md-6 wow fadeInUp';
                    blogCard.setAttribute('data-wow-delay', delay + 's');
                    blogCard.innerHTML = `
                        <div class="blog-card style1">
                            <div class="blog-card-thumb">
                                <img src="${blogImage}" alt="${blog.title.replace(/"/g, '&quot;')}" onerror="this.src='assets/img/blog/blogThumb1_1.jpg'" />
                            </div>
                            <div class="blog-card-body">
                                <div class="blog-meta">
                                    <div class="tag">${categoryName}</div>
                                    <div class="date">${formattedDate}</div>
                                </div>
                                <h3>
                                    <a href="${blogUrl}">${blog.title}</a>
                                </h3>
                                ${shortExcerpt ? `<p class="blog-excerpt">${shortExcerpt}</p>` : ''}
                                <div class="author-meta">
                                    <div class="fancy-box style1">
                                        <div class="item">
                                            <img src="assets/img/blog/blogProfile1_1.png" alt="${(blog.author_name || 'Admin').replace(/"/g, '&quot;')}" onerror="this.src='assets/img/blog/blogProfile1_1.png'" />
                                        </div>
                                        <div class="item">
                                            <h6>${blog.author_name || 'Admin'}</h6>
                                            <p>Author</p>
                                        </div>
                                    </div>
                                    <a class="link-btn style1" href="${blogUrl}">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    blogsContainer.appendChild(blogCard);
                });
                
                if (typeof WOW !== 'undefined') {
                    new WOW().init();
                }
            } else {
                blogsContainer.innerHTML = `
                    <div class="col-12">
                        <div style="text-align: center; padding: 60px 20px;">
                            <h2>No Blogs Available</h2>
                            <p>There are no blog posts available at the moment. Please check back later.</p>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching blogs:', error);
            loadingState.remove();
            showError();
        });
});
</script>

<style>
#loading-state .spinner-border {
    border-width: 4px;
    border-color: #007bff;
    border-right-color: transparent;
}

/* Ensure blog cards have proper spacing in grid */
#blogs-container .blog-card.style1 {
    margin-bottom: 0;
    height: 100%;
}

/* Grid spacing adjustments */
#blogs-container .col-xl-4,
#blogs-container .col-lg-6,
#blogs-container .col-md-6 {
    margin-bottom: 30px;
}

@media (max-width: 991px) {
    #blogs-container .col-lg-6,
    #blogs-container .col-md-6 {
        margin-bottom: 25px;
    }
}

@media (max-width: 767px) {
    #blogs-container .col-md-6 {
        margin-bottom: 20px;
    }
}
</style>
