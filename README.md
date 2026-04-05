# JSPS Accounting Solutions Pvt. Ltd. — Website

**Version:** 1.0.0  
**Stack:** PHP 8.1+ · MySQL 8.0+ · Apache/Nginx · Bootstrap 5

---

## Project Structure

```
jsps/
│
├── .htaccess                   # Apache rules: security, caching, routing
├── bootstrap.php               # Loaded by every entry point: config, DB, session, helpers
├── index.php                   # Home page
├── login.php                   # Login + Register (combined)
├── logout.php                  # Session destroy & redirect
├── 404.php                     # Custom error page
│
├── config/
│   ├── config.php              # App constants: DB, paths, env settings
│   └── services.php            # Services data (single source of truth)
│
├── database/
│   ├── Database.php            # Singleton PDO wrapper (query, fetchOne, fetchAll, insert, execute)
│   ├── schema.sql              # Run once to create tables
│   └── seed.php                # Run once to insert demo data (hashes passwords properly)
│
├── includes/                   # Reusable public-page partials
│   ├── head.php                # <html><head> block, CSS links
│   ├── navbar.php              # Sticky navigation bar + mobile menu
│   ├── footer.php              # Footer + JS includes + </body></html>
│   ├── breadcrumb.php          # Schema.org breadcrumb component
│   └── blog_card.php           # Reusable blog article card
│
├── pages/                      # Public pages
│   ├── about.php
│   ├── services.php
│   ├── blog.php                # Listing with category filter + search + pagination
│   ├── blog-detail.php         # Single article (SEO-friendly, schema.org markup)
│   ├── contact.php             # Contact form with CSRF + server-side validation
│   ├── privacy.php
│   └── terms.php
│
├── admin/                      # Admin panel (role = 'admin' required)
│   ├── dashboard.php           # Stats overview + recent data
│   ├── contacts.php            # View, mark-read, delete contact submissions
│   ├── articles.php            # List all articles (all users) + toggle status
│   ├── article-form.php        # Add / Edit article
│   ├── users.php               # Manage users: view, change role, delete
│   └── includes/
│       ├── sidebar.php         # Admin left nav
│       ├── panel_head.php      # Admin <head> + sidebar + topbar opener
│       └── panel_footer.php    # Admin scripts + closing tags
│
├── user/                       # User panel (any logged-in user)
│   ├── dashboard.php
│   ├── contacts.php            # Own submissions
│   ├── articles.php            # Own articles: view, edit, delete
│   ├── article-form.php        # Write / Edit own article
│   └── includes/
│       ├── sidebar.php
│       ├── panel_head.php
│       └── panel_footer.php
│
└── assets/
    ├── css/
    │   ├── style.css           # All public page styles
    │   └── panel.css           # Admin & user panel layout styles
    ├── js/
    │   ├── script.js           # Public page JS (navbar, auth tabs, smooth scroll)
    │   └── panel.js            # Panel JS (sidebar toggle, textarea auto-resize)
    └── images/                 # Place logo and other images here
        └── .gitkeep
```

---

## Local Setup (XAMPP / WAMP / MAMP)

### 1. Clone / copy files

```bash
# Place the project inside your web root
cp -r jsps/ /var/www/html/jsps/
# or for XAMPP:
# cp -r jsps/ C:/xampp/htdocs/jsps/
```

### 2. Create the database

```bash
mysql -u root -p < database/schema.sql
```

### 3. Configure the app

Open `config/config.php` and update:

```php
define('APP_URL',  'http://localhost/jsps');   // your local URL
define('DB_HOST',  'localhost');
define('DB_NAME',  'jsps_db');
define('DB_USER',  'root');
define('DB_PASS',  'your_password');
```

### 4. Seed demo data

```bash
php database/seed.php
```

This creates:
| Email | Password | Role |
|---|---|---|
| admin@jspsaccounting.com | Admin@123 | admin |
| raj@example.com | User@123 | user |

### 5. Enable mod_rewrite (Apache)

Ensure `AllowOverride All` is set for your vhost / htdocs directory.

```apache
<Directory "/var/www/html/jsps">
    AllowOverride All
    Require all granted
</Directory>
```

### 6. Visit the site

```
http://localhost/jsps/          → Home page
http://localhost/jsps/login.php → Login / Register
```

---

## Production Deployment

1. Set `define('ENV', 'production')` in `config/config.php`
2. Set the real `APP_URL` with HTTPS
3. Uncomment the HTTPS redirect in `.htaccess`
4. Set `DB_USER` and `DB_PASS` to production credentials
5. Ensure `config/` and `database/` directories are not web-accessible (`.htaccess` already blocks them)
6. Delete `database/seed.php` after initial setup
7. Create a real admin account via `seed.php` with a strong password, then delete the script

---

## Key Design Decisions

| Concern | Solution |
|---|---|
| Database access | Singleton PDO class (`Database.php`) — no raw queries outside this class |
| Authentication | PHP sessions with `session_regenerate_id()` on login |
| CSRF protection | Token stored in `$_SESSION['csrf_token']`, validated on every POST |
| XSS prevention | All output goes through `e()` helper (`htmlspecialchars`) |
| Password storage | `password_hash()` with BCRYPT cost 12 |
| URL slug | `slugify()` helper in `bootstrap.php` — unique checked before insert |
| Pagination | `paginate()` helper returns offset/total/hasNext for any query |
| Access control | `requireLogin()` and `requireAdmin()` called at top of protected pages |

---

## Extending the Project

### Add a new public page
1. Create `pages/yourpage.php`
2. Set `$pageTitle`, `$activePage`, `$breadcrumbs`
3. Include `head.php`, `navbar.php`, `breadcrumb.php`, your HTML, `footer.php`
4. Add link to `includes/navbar.php` `$navLinks` array

### Add a new admin section
1. Create `admin/yoursection.php`
2. Call `requireAdmin()` at the top
3. Add to `admin/includes/sidebar.php` `$navItems` array
4. Include `panel_head.php` and `panel_footer.php`

### Add a new database table
1. Add `CREATE TABLE` to `database/schema.sql`
2. Use `$db->fetchAll()`, `$db->fetchOne()`, `$db->insert()`, `$db->execute()` — never write `new PDO()` again

---

## PHP Requirements

- PHP >= 8.0
- Extensions: `pdo`, `pdo_mysql`, `mbstring`, `openssl`
- Apache with `mod_rewrite` enabled (or Nginx equivalent)

---

*Built for JSPS Accounting Solutions Pvt. Ltd. — professional, modular, maintainable.*
