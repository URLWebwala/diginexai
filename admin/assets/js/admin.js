// Admin Panel JavaScript

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Clear previews
            const previews = modal.querySelectorAll('.image-preview, .current-image');
            previews.forEach(preview => preview.innerHTML = '');
            // Reset file names
            const fileNames = modal.querySelectorAll('.file-name');
            fileNames.forEach(fn => fn.textContent = 'No file chosen');
        }
    }
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal.active');
        if (activeModal) {
            closeModal(activeModal.id);
        }
    }
});

// Sidebar Toggle
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.querySelector('.sidebar');

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
}

// Action Menu Toggle
function toggleMenu(button, e) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }
    
    const menu = button.closest('.action-menu');
    if (!menu) return;
    
    const dropdown = menu.querySelector('.action-dropdown');
    if (!dropdown) return;
    
    const allDropdowns = document.querySelectorAll('.action-dropdown');
    const allMenus = document.querySelectorAll('.action-menu');
    
    // Close all other dropdowns
    allDropdowns.forEach(dd => {
        if (dd !== dropdown) {
            dd.classList.remove('show');
            dd.style.display = 'none';
        }
    });
    
    // Remove active class from all menus
    allMenus.forEach(m => {
        if (m !== menu) {
            m.classList.remove('active');
        }
    });
    
    // Toggle current dropdown
    const isVisible = dropdown.style.display === 'block' || dropdown.classList.contains('show');
    if (isVisible) {
        dropdown.style.display = 'none';
        dropdown.classList.remove('show');
        menu.classList.remove('active');
    } else {
        dropdown.style.display = 'block';
        dropdown.classList.add('show');
        menu.classList.add('active');
    }
}

// Close dropdowns on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-menu')) {
        document.querySelectorAll('.action-dropdown').forEach(dd => {
            dd.style.display = 'none';
            dd.classList.remove('show');
        });
        document.querySelectorAll('.action-menu').forEach(m => {
            m.classList.remove('active');
        });
    }
});

// Auto-hide alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill all required fields');
            }
        });
    });
});

// Image preview function
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const fileName = input.id + 'FileName';
    const fileNameElement = document.getElementById(fileName);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">';
            }
        };
        reader.readAsDataURL(input.files[0]);
        
        if (fileNameElement) {
            fileNameElement.textContent = input.files[0].name;
        }
    }
}

// Search function
function handleSearch(e) {
    if (e.key === 'Enter') {
        const search = e.target.value;
        // Get current page name from script name or use base URL
        let currentPage = 'index.php';
        if (window.location.pathname) {
            const pathParts = window.location.pathname.split('/').filter(p => p);
            // Find the admin page name (should be after 'admin' in path)
            const adminIndex = pathParts.indexOf('admin');
            if (adminIndex >= 0 && pathParts[adminIndex + 1]) {
                currentPage = pathParts[adminIndex + 1];
            } else if (pathParts.length > 0) {
                // Fallback: get last part of path
                currentPage = pathParts[pathParts.length - 1];
            }
        }
        // Remove .php extension if present, then add it back to ensure consistency
        currentPage = currentPage.replace(/\.php$/, '') + '.php';
        // Use simple relative URL for search
        window.location.href = currentPage + '?search=' + encodeURIComponent(search);
    }
}

// Change rows per page (deprecated - pagination removed)
function changeRowsPerPage(rows, baseUrl) {
    // Pagination removed - function kept for compatibility but does nothing
    return;
}

// Custom Confirmation Modal
let confirmCallback = null;

function showConfirm(message, callback, type = 'delete') {
    confirmCallback = callback;
    const modal = document.getElementById('confirmModal');
    const messageEl = document.getElementById('confirmMessage');
    const iconEl = document.getElementById('confirmIcon');
    const titleEl = document.getElementById('confirmTitle');
    const actionBtn = document.getElementById('confirmActionBtn');
    const btnText = document.getElementById('confirmBtnText');
    
    if (messageEl) messageEl.textContent = message;
    
    // Set icon and title based on type
    const iconWrapper = document.querySelector('.confirm-icon-wrapper');
    if (type === 'delete') {
        if (iconEl) iconEl.className = 'fas fa-exclamation-triangle';
        if (titleEl) titleEl.textContent = 'Confirm Delete';
        if (iconWrapper) iconWrapper.classList.remove('info');
        if (iconWrapper) iconWrapper.style.background = 'var(--gradient-danger)';
        if (actionBtn) {
            actionBtn.className = 'btn btn-danger';
            if (btnText) btnText.textContent = 'Delete';
        }
    } else if (type === 'edit') {
        if (iconEl) iconEl.className = 'fas fa-edit';
        if (titleEl) titleEl.textContent = 'Confirm Edit';
        if (iconWrapper) iconWrapper.classList.add('info');
        if (iconWrapper) iconWrapper.style.background = 'var(--gradient-primary)';
        if (actionBtn) {
            actionBtn.className = 'btn btn-primary';
            if (btnText) btnText.textContent = 'Edit';
        }
    } else if (type === 'status') {
        if (iconEl) iconEl.className = 'fas fa-info-circle';
        if (titleEl) titleEl.textContent = 'Confirm Action';
        if (iconWrapper) iconWrapper.classList.add('info');
        if (actionBtn) {
            actionBtn.className = 'btn btn-primary';
            if (btnText) btnText.textContent = 'Confirm';
        }
    }
    
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeConfirm() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    confirmCallback = null;
}

function confirmYes() {
    if (confirmCallback) {
        confirmCallback();
    }
    closeConfirm();
}

// Replace all confirm() calls with custom modal
document.addEventListener('click', function(e) {
    const deleteLink = e.target.closest('a.delete-link, a.status-link');
    if (deleteLink) {
        e.preventDefault();
        const href = deleteLink.getAttribute('href');
        const message = deleteLink.getAttribute('data-message') || 
                       (href.includes('delete') ? 'Are you sure you want to delete this item?' : 'Are you sure you want to change the status?');
        const type = href.includes('delete') ? 'delete' : 'status';
        
        showConfirm(message, function() {
            window.location.href = href;
        }, type);
    }
});

// Initialize tooltips (if using a tooltip library)
// You can add tooltip initialization here if needed

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Table row click (optional - for viewing details)
document.querySelectorAll('.data-table tbody tr').forEach(row => {
    row.addEventListener('click', function(e) {
        // Don't trigger if clicking on action buttons
        if (!e.target.closest('.action-menu')) {
            // Add your row click logic here
        }
    });
});

// Auto-resize textareas
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});

// Format phone numbers (optional)
function formatPhoneNumber(input) {
    const value = input.value.replace(/\D/g, '');
    if (value.length > 0) {
        input.value = value;
    }
}

// Apply phone formatting to phone inputs
document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', function() {
        formatPhoneNumber(this);
    });
});

// Loading state for buttons
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }
    });
});

