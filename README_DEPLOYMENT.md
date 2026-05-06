# 🚀 Production Deployment - Complete Guide

## 📍 3 Subdomains Setup

1. **Frontend**: `diginexai.com` → `/public_html/`
2. **Admin**: `admin.diginexai.com` → `/public_html/admin/`
3. **API**: `api.diginexai.com` → `/public_html/api/`

---

## 📦 Files Upload Structure

### ✅ **FRONTEND** (diginexai.com)
**Upload Location**: `/public_html/`

**Files**:
- All PHP files (index.php, blog.php, about.php, contact.php, service.php, etc.)
- `config/` folder
- `constants/` folder
- `assets/` folder
- `.htaccess`

### ✅ **ADMIN** (admin.diginexai.com)
**Upload Location**: `/public_html/admin/`

**Files**:
- All admin PHP files
- `admin/config/` folder
- `admin/actions/` folder
- `admin/includes/` folder
- `admin/assets/` folder
- `admin/.htaccess`

### ✅ **API** (api.diginexai.com)
**Upload Location**: `/public_html/api/`

**Files**:
- All API PHP files (blogs.php, seo.php, testimonials.php, clients.php, contact.php)
- `api/config/` folder
- `api/.htaccess`

---

## ⚙️ Configuration Updates (4 Files Only!)

### 1. Frontend Database
**File**: `/public_html/config/database.php`
```php
define('DB_HOST', 'your_production_host');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'diginexai_db');
```

### 2. Frontend API URL
**File**: `/public_html/constants/constants.php` (Line 6)
```php
define('API_BASE_URL', 'https://api.diginexai.com');
```

### 3. Admin Database
**File**: `/public_html/admin/config/database.php`
```php
define('DB_HOST', 'your_production_host');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'diginexai_db');
```

### 4. API Database
**File**: `/public_html/api/config/database.php`
```php
define('DB_HOST', 'your_production_host');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'diginexai_db');
```

---

## 🗄️ Database Setup

### Import SQL File
**File**: `COMPLETE_DATABASE.sql` ✅ (एक ही file - सभी tables + SEO data)

Import करें production database में।

### Create Admin User
Visit: `https://admin.diginexai.com/setup_diginex_admin.php`

---

## ✅ Checklist

- [ ] Upload frontend files to `/public_html/`
- [ ] Upload admin files to `/public_html/admin/`
- [ ] Upload API files to `/public_html/api/`
- [ ] Update 4 config files (database credentials)
- [ ] Update `constants/constants.php` - API_BASE_URL
- [ ] Import `COMPLETE_DATABASE.sql`
- [ ] Create admin user
- [ ] Test all 3 subdomains

---

## 📝 Summary

**Total Files to Update**: **4 files**
1. `config/database.php`
2. `constants/constants.php` (Line 6)
3. `admin/config/database.php`
4. `api/config/database.php`

**Database**: `COMPLETE_DATABASE.sql` (एक ही file)

**That's it! 🎉**

