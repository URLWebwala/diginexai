<?php 
$pageTitle = 'Contact Us for Your Digital Marketing Needs  | DiginexAI';
$description = 'Get in touch today to discuss your digital marketing needs and discover how we can help grow your brand, increase visibility, and achieve your goals.';
include('header.php');
?>

<script>
(function() {
    if (window.dataLayer) {
        var originalPush = window.dataLayer.push;
        window.dataLayer.push = function() {
            var args = Array.prototype.slice.call(arguments);
            if (args[0] && typeof args[0] === 'object') {
                if ((args[0].event === 'form_start' || args[0].event === 'form_submit') && args[0]['ep.form_id'] === 'contact-form') {
                    return;
                }
            }
            return originalPush.apply(window.dataLayer, args);
        };
    }
    
    function setupForm() {
        var form = document.getElementById('contact-form');
        if (form) {
            form.setAttribute('data-gtm-ignore', 'true');
            form.setAttribute('data-ga-ignore', 'true');
            form.setAttribute('data-gtag-ignore', 'true');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }, true);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupForm);
    } else {
        setupForm();
    }
})();
</script>

<main class="dx-home">
    <!-- Sub-page Hero Section -->
    <section class="dx-about-hero">
        <div class="container">
            <div class="dx-about-hero-grid">
                <div class="dx-about-hero-copy">
                    <span class="dx-eyebrow"><i class="fa-regular fa-sparkles"></i> Get in touch</span>
                    <h1>Let's start a conversation about your growth.</h1>
                    <p>
                        Whether you're looking for a website audit, an SEO strategy, or a full brand 
                        redesign, our team is ready to help you navigate the digital landscape.
                    </p>
                    <nav aria-label="breadcrumb" class="mt-4">
                        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                            <li class="breadcrumb-item"><a href="/diginexai/index.php" style="color: rgba(255,255,255,0.7); text-decoration: none;">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 700;">Contact Us</li>
                        </ol>
                    </nav>
                </div>
                <div class="dx-about-hero-media">
                    <img src="assets/img/hero/hero-1.jpg" alt="Contact DiginexAI team">
                    <div class="dx-about-stat">
                        <strong>24/7</strong>
                        <span>Support desk active for all our global partners.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="dx-section" style="padding-bottom: 80px; padding-top: 80px;">
        <div class="container">
            <style>
                @media (min-width: 992px) {
                    .dx-contact-grid { grid-template-columns: repeat(3, 1fr) !important; }
                }
                @media (max-width: 991px) {
                    .dx-contact-grid { grid-template-columns: repeat(1, 1fr) !important; }
                }
            </style>
            <div class="dx-card-grid dx-contact-grid">
                <article class="dx-service-card">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <img src="assets/img/flag_10597864.png" alt="India Flag" style="width: 32px; height: auto;">
                        <h3 style="margin: 0; font-size: 22px;">India Office</h3>
                    </div>
                    <p style="font-weight: 700; color: #123f8c; margin-bottom: 8px;">+91 8140881396</p>
                    <p>4th, Vandemataram Arcade, 23, Ahmedabad, Gujarat 382481</p>
                </article>

                <article class="dx-service-card">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <img src="assets/img/flag_14539032.png" alt="UK Flag" style="width: 32px; height: auto;">
                        <h3 style="margin: 0; font-size: 22px;">UK Office</h3>
                    </div>
                    <p style="font-weight: 700; color: #123f8c; margin-bottom: 8px;">+44 7534866933</p>
                    <p>Norwich, Norfolk, United Kingdom NR2 1TF</p>
                </article>

                <article class="dx-service-card">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <i class="fa-regular fa-envelope" style="width: auto; height: auto; background: none; padding: 0; font-size: 32px;"></i>
                        <h3 style="margin: 0; font-size: 22px;">Email Us</h3>
                    </div>
                    <p style="font-weight: 700; color: #123f8c; margin-bottom: 8px;">info@diginexai.com</p>
                    <p>Send us your brief and we'll get back to you within 24 hours.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="dx-section dx-about">
        <div class="container">
            <div class="dx-about-grid">
                <div class="dx-about-media">
                    <img src="assets/img/contactus.jpg" alt="Our consulting space" style="box-shadow: 0 24px 64px rgba(18,63,140,0.12);">
                </div>
                <div class="dx-about-copy">
                    <span class="dx-kicker">Send a Message</span>
                    <h2>Ready to get started? Tell us about your project.</h2>
                    
                    <form id="contact-form" method="POST" class="mt-5" onsubmit="return false;" data-gtm-ignore="true" data-ga-ignore="true">
                        <style>
                            .dx-form-group { margin-bottom: 20px; }
                            .dx-input-wrapper { position: relative; }
                            .dx-input-wrapper i { 
                                position: absolute; 
                                left: 20px; 
                                top: 50%; 
                                transform: translateY(-50%); 
                                color: #123f8c; 
                                opacity: 0.6;
                            }
                            .dx-input-wrapper input, 
                            .dx-input-wrapper textarea {
                                width: 100%;
                                padding: 16px 20px 16px 52px;
                                border: 1px solid #dde7fb;
                                border-radius: 8px;
                                background: #fff;
                                color: #111322;
                                font-size: 16px;
                                transition: all 0.2s;
                            }
                            .dx-input-wrapper textarea { padding-top: 16px; min-height: 120px; }
                            .dx-input-wrapper input:focus, 
                            .dx-input-wrapper textarea:focus {
                                outline: none;
                                border-color: #123f8c;
                                box-shadow: 0 0 0 4px rgba(18, 63, 140, 0.05);
                            }
                        </style>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="dx-form-group">
                                    <div class="dx-input-wrapper">
                                        <i class="fa-regular fa-user"></i>
                                        <input type="text" name="name" id="name" placeholder="Your Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dx-form-group">
                                    <div class="dx-input-wrapper">
                                        <i class="fa-regular fa-envelope"></i>
                                        <input type="email" name="email" id="email" placeholder="Email Address" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dx-form-group">
                                    <div class="dx-input-wrapper">
                                        <i class="fa-regular fa-phone"></i>
                                        <input type="tel" name="phone" id="phone" placeholder="Phone Number" pattern="[0-9]{10}" maxlength="10" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dx-form-group">
                                    <div class="dx-input-wrapper">
                                        <i class="fa-regular fa-layer-group"></i>
                                        <input type="text" name="project" id="project" placeholder="Project Type / Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="dx-form-group">
                                    <div class="dx-input-wrapper">
                                        <i class="fa-regular fa-pen-to-square" style="top: 24px; transform: none;"></i>
                                        <textarea name="message" id="message" placeholder="How can we help?" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" id="contact-submit-btn" class="dx-btn dx-btn-primary" style="border: none; width: 100%; cursor: pointer;">
                                Send Message <i class="fa-regular fa-arrow-right-long"></i>
                            </button>
                        </div>
                        
                        <div class="form-message" style="margin-top: 20px; padding: 15px; border-radius: 8px; display: none; font-weight: 500;"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('footer.php');?>

<style>
.form-message {
    margin-top: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: 500;
    display: none;
}
.form-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
.form-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.form-message i {
    margin-right: 8px;
}
</style>

<script>
(function() {
    function initContactForm() {
        if (typeof jQuery === 'undefined' || typeof $ === 'undefined') {
            setTimeout(initContactForm, 100);
            return;
        }
        
        jQuery(document).ready(function($) {
            var form = $('#contact-form');
            var formMessages = $('.form-message');
            var submitButton = $('#contact-submit-btn');
            var originalButtonText = submitButton.html() || 'Send Message <i class="fa-solid fa-arrow-right-long"></i>';
            
            if (form.length === 0) return;
            
            if (formMessages.length === 0) {
                form.after('<div class="form-message" style="margin-top: 20px; padding: 15px; border-radius: 8px; display: none; font-weight: 500;"></div>');
                formMessages = $('.form-message');
            }
            
            function showMessage(message, isSuccess) {
                formMessages.removeClass('success error');
                formMessages.addClass(isSuccess ? 'success' : 'error');
                if (formMessages[0]) {
                    formMessages[0].innerHTML = message;
                    formMessages[0].style.display = 'block';
                } else {
                    formMessages.html(message).show();
                }
            }
            
            function handleFormSubmit() {
                showMessage('<i class="fa fa-spinner fa-spin"></i> Processing...', false);
                
                submitButton.prop('disabled', true);
                if (submitButton[0]) {
                    submitButton[0].innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
                } else {
                    submitButton.html('<i class="fa fa-spinner fa-spin"></i> Sending...');
                }
                
                var name = $('#name').val().trim();
                var email = $('#email').val().trim();
                var phone = $('#phone').val().trim();
                var project = $('#project').val().trim();
                var message = $('#message').val().trim();
                
                if (!name || !email || !message) {
                    showMessage('<i class="fa fa-exclamation-circle"></i> Please fill in all required fields (Name, Email, Message).', false);
                    submitButton.prop('disabled', false);
                    if (submitButton[0]) {
                        submitButton[0].innerHTML = originalButtonText;
                    } else {
                        submitButton.html(originalButtonText);
                    }
                    return;
                }
                
                var nameParts = name.split(' ');
                var apiData = {
                    first_name: nameParts[0] || '',
                    last_name: nameParts.slice(1).join(' ') || '',
                    email: email,
                    phone: phone,
                    service: project,
                    message: message
                };
                
                // Use API base URL from constants
                var apiUrl = '<?php echo defined("API_BASE_URL") ? API_BASE_URL : "https://api.diginexai.com"; ?>/contact.php';
                
                $.ajax({
                    type: 'POST',
                    url: apiUrl,
                    contentType: 'application/json; charset=utf-8',
                    data: JSON.stringify(apiData),
                    dataType: 'json',
                    timeout: 15000,
                    processData: false,
                    cache: false
                })
                .done(function(response) {
                    if (response && response.success) {
                        showMessage('<i class="fa fa-check-circle"></i> ' + (response.message || 'Thank you! Your message has been sent successfully.'), true);
                        if (form[0]) form[0].reset();
                        if (formMessages[0]) {
                            formMessages[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        showMessage('<i class="fa fa-exclamation-circle"></i> ' + (response.message || 'Something went wrong. Please try again.'), false);
                    }
                })
                .fail(function(xhr, status, error) {
                    var errorMessage = 'Oops! An error occurred and your message could not be sent.';
                    if (status === 'timeout') {
                        errorMessage = 'Request timeout. Please check your internet connection and try again.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            var errorData = JSON.parse(xhr.responseText);
                            if (errorData.message) errorMessage = errorData.message;
                        } catch(e) {
                            if (xhr.status === 0) {
                                errorMessage = 'Network error. Please check your connection.';
                            } else if (xhr.status === 404) {
                                errorMessage = 'API endpoint not found. Please contact administrator.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'Server error. Please try again later.';
                            }
                        }
                    }
                    showMessage('<i class="fa fa-exclamation-circle"></i> ' + errorMessage, false);
                })
                .always(function() {
                    submitButton.prop('disabled', false);
                    if (submitButton[0]) {
                        submitButton[0].innerHTML = originalButtonText;
                    } else {
                        submitButton.html(originalButtonText);
                    }
                });
            }
            
            submitButton.off('click');
            if (submitButton[0]) {
                var newButton = submitButton[0].cloneNode(true);
                submitButton[0].parentNode.replaceChild(newButton, submitButton[0]);
                submitButton = $(newButton);
                submitButton.attr('data-gtm-ignore', 'true');
                submitButton.attr('data-ga-ignore', 'true');
            }
            
            if (submitButton[0]) {
                submitButton[0].addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    handleFormSubmit();
                    return false;
                }, true);
            }
            
            submitButton.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                handleFormSubmit();
                return false;
            });
            
            form.off('submit');
            form.on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                handleFormSubmit();
                return false;
            });
            
            if (form[0]) {
                form[0].addEventListener('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }, true);
            }
        });
    }
    
    if (typeof jQuery !== 'undefined') {
        initContactForm();
    } else {
        var checkJQuery = setInterval(function() {
            if (typeof jQuery !== 'undefined') {
                clearInterval(checkJQuery);
                initContactForm();
            }
        }, 50);
        setTimeout(function() {
            clearInterval(checkJQuery);
        }, 5000);
    }
})();
</script>
