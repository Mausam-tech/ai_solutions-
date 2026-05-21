# AI-Solutions Website

**Module:** CET333 | **Student:** Mausam Adhikari  
A full-stack PHP/MySQL website for the AI-Solutions fictitious company, built as part of the CET333 portfolio assessment.

---

## Technology Stack

| Layer | Technology |
|---|---|
| Server-side | PHP 8.0+ with PDO |
| Database | MySQL 8.0 (via XAMPP) |
| Frontend | HTML5, CSS3, Bootstrap 5.3 |
| Icons | Bootstrap Icons 1.11 |
| Fonts | Google Fonts (Inter + Space Grotesk) |
| JavaScript | Vanilla JS (no frameworks) |
| Local server | XAMPP (Apache + MySQL) |

---

## Prerequisites

- XAMPP installed (version 8.x recommended) with Apache and MySQL running
- PHP 8.0 or higher
- A modern web browser (Chrome, Firefox, Edge)
- Internet connection (for CDN assets: Bootstrap, Google Fonts)

---

## Installation — Step by Step

### Step 1 — Copy project files

Copy the entire `ai_solutions` folder into your XAMPP `htdocs` directory:

```
C:\xampp\htdocs\ai_solutions\        (Windows)
/Applications/XAMPP/htdocs/ai_solutions/   (macOS)
/opt/lampp/htdocs/ai_solutions/      (Linux)
```

The folder must be named exactly **`ai_solutions`** for the URL paths to work correctly.

### Step 2 — Start XAMPP

Open the XAMPP Control Panel and start both:
- ✅ **Apache**
- ✅ **MySQL**

### Step 3 — Create the database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar
3. Type **`ai_solutions`** as the database name
4. Select **`utf8mb4_unicode_ci`** as the collation
5. Click **Create**

### Step 4 — Import the database schema and seed data

1. In phpMyAdmin, click on the **`ai_solutions`** database in the left sidebar
2. Click the **"Import"** tab at the top
3. Click **"Choose File"** and navigate to:
   ```
   C:\xampp\htdocs\ai_solutions\database\ai_solutions.sql
   ```
4. Click **"Go"** (Import button at the bottom)
5. You should see a success message — 8 tables will be created with seed data

### Step 5 — Create the admin account

1. Open your browser and visit:
   ```
   http://localhost/ai_solutions/setup.php
   ```
2. Enter a username (e.g. `admin`) and a strong password
   - Password requirements: minimum 8 characters, at least 1 uppercase letter, at least 1 number
3. Click **"Create Admin Account"**
4. Once you see the success message, **delete `setup.php`** from the project folder immediately

### Step 6 — Verify the website

| URL | What you should see |
|---|---|
| `http://localhost/ai_solutions/` | Homepage with all sections populated from seed data |
| `http://localhost/ai_solutions/services.php` | All 6 services listed |
| `http://localhost/ai_solutions/portfolio.php` | 6 portfolio projects with filter |
| `http://localhost/ai_solutions/admin/login.php` | Admin login page |

---

## Admin Panel Usage

### Login

Go to: `http://localhost/ai_solutions/admin/login.php`  
Use the credentials you created in setup.php.

### Dashboard

After login you will see:
- **Inquiry stats** — total, unread, today, this month
- **Content counters** — quick links to each content section
- **Recent inquiries** — last 5 submissions from the contact form

### Managing Content (CRUD)

| Section | List Page | What you can do |
|---|---|---|
| Gallery | `admin/gallery-list.php` | Upload images with title and category |
| Articles | `admin/articles-list.php` | Write and publish articles with thumbnail |
| Services | `admin/services-list.php` | Add/edit services with icon and features |
| Portfolio | `admin/portfolio-list.php` | Add projects with full case study detail |
| Testimonials | `admin/testimonials-list.php` | Add client reviews with star rating |
| Events | `admin/events-list.php` | Add upcoming and past events |

### Image Uploads

- Supported formats: **JPG, JPEG, PNG, GIF, WebP**
- Maximum file size: **5 MB per image**
- Images are stored in the `uploads/` subdirectories:
  - `uploads/gallery/` — Gallery photos
  - `uploads/articles/` — Article thumbnails
  - `uploads/portfolio/` — Portfolio cover images
  - `uploads/testimonials/` — Client avatars
  - `uploads/services/` — Optional service images
  - `uploads/events/` — Event images
- When you delete a record, the associated image file is automatically deleted from disk

### Inquiries (Contact Form Submissions)

- View all customer inquiry submissions at `admin/inquiries.php`
- Click **View** on any row to see the full inquiry details
- Inquiries are automatically marked as **Read** when you open them
- Use the search bar to find by name, email, or company
- Reply directly by clicking the email link on the view page

---

## File Structure

```
ai_solutions/
│
├── index.php                  Homepage
├── services.php               Services page (reads DB)
├── portfolio.php              Portfolio with filter + modals (reads DB)
├── testimonials.php           Testimonials with rating filter (reads DB)
├── articles.php               Articles with featured + modal (reads DB)
├── gallery.php                Gallery with filter + lightbox (reads DB)
├── events.php                 Events upcoming/past tabs (reads DB)
├── contact.php                Contact Us form
├── submit-contact.php         Form POST handler
├── 404.php                    Custom 404 error page
├── setup.php                  One-time admin setup (DELETE AFTER USE)
├── .htaccess                  Apache config
│
├── admin/
│   ├── login.php              Admin login
│   ├── logout.php             Session destroy
│   ├── dashboard.php          Stats + recent inquiries
│   ├── inquiries.php          All inquiries (paginated, searchable)
│   ├── view-inquiry.php       Single inquiry detail
│   ├── gallery-list.php       Gallery management
│   ├── gallery-add.php
│   ├── gallery-edit.php
│   ├── gallery-delete.php
│   ├── articles-list.php      Article management
│   ├── articles-add.php
│   ├── articles-edit.php
│   ├── articles-delete.php
│   ├── services-list.php      Services management
│   ├── services-add.php
│   ├── services-edit.php
│   ├── services-delete.php
│   ├── portfolio-list.php     Portfolio management
│   ├── portfolio-add.php
│   ├── portfolio-edit.php
│   ├── portfolio-delete.php
│   ├── testimonials-list.php  Testimonials management
│   ├── testimonials-add.php
│   ├── testimonials-edit.php
│   ├── testimonials-delete.php
│   ├── events-list.php        Events management
│   ├── events-add.php
│   ├── events-edit.php
│   └── events-delete.php
│
├── includes/
│   ├── db.php                 Database connection + helper functions
│   ├── auth.php               Admin session guard
│   ├── upload-helper.php      Image upload validation and handling
│   ├── header.php             HTML <head> for public pages
│   ├── navbar.php             Public navigation bar
│   ├── footer.php             Footer + chatbot widget HTML
│   ├── admin-head.php         HTML <head> for admin pages
│   ├── admin-foot.php         Closing tags for admin pages
│   └── admin-sidebar.php      Admin navigation sidebar
│
├── assets/
│   ├── css/
│   │   ├── style.css          Public site stylesheet
│   │   └── admin.css          Admin panel stylesheet
│   └── js/
│       ├── main.js            Navbar, counters, filters, form validation
│       ├── chatbot.js         AI chatbot widget logic
│       └── gallery.js         Gallery filter + lightbox
│
├── uploads/                   User-uploaded images (auto-created)
│   ├── gallery/
│   ├── articles/
│   ├── portfolio/
│   ├── testimonials/
│   ├── services/
│   └── events/
│
└── database/
    └── ai_solutions.sql       Full schema + seed data
```

---

## Database Tables

| Table | Purpose |
|---|---|
| `admin_users` | Admin login credentials (hashed passwords) |
| `contact_inquiries` | Customer contact form submissions |
| `services` | Company services (CRUD via admin) |
| `portfolio_items` | Past project case studies (CRUD via admin) |
| `testimonials` | Client testimonials with star ratings (CRUD via admin) |
| `articles` | Blog/insight articles (CRUD via admin) |
| `gallery_images` | Photo gallery images (CRUD via admin) |
| `events` | Company events — upcoming/past auto-detected by date (CRUD via admin) |

---

## Troubleshooting

### "Database Connection Failed"
- Make sure MySQL is running in XAMPP
- Check `includes/db.php` — `DB_NAME` should be `ai_solutions`, `DB_USER` should be `root`, `DB_PASS` should be empty (XAMPP default)
- Confirm the database was created in phpMyAdmin

### Pages load but show no content
- Ensure the SQL file was imported correctly
- Go to phpMyAdmin → `ai_solutions` database → check that all 8 tables exist and contain data

### Images not displaying after upload
- Check that the `uploads/` subdirectories exist and are writable
- Right-click the `uploads` folder → Properties → ensure write permissions are enabled
- On Linux/macOS, run: `chmod -R 755 uploads/`

### Admin login not working
- Confirm you ran `setup.php` successfully and saw the success message
- Try running `setup.php` again to reset the password

### Folder name issues
- The project folder **must** be named `ai_solutions` (with underscore, not hyphen)
- If you rename the folder, update `APP_PATH` in `includes/db.php` accordingly

### .htaccess errors (500 Internal Server Error)
- In XAMPP, open `httpd.conf`, find the `AllowOverride None` line for your htdocs directory, and change it to `AllowOverride All`
- Restart Apache after making this change

---

## Security Notes

- All database queries use **PDO prepared statements** — SQL injection is prevented
- All output is escaped with `htmlspecialchars()` — XSS is prevented
- Admin passwords are hashed with PHP's `password_hash()` (bcrypt)
- Sessions are regenerated on login to prevent session fixation
- The `includes/` directory is blocked from direct web access via `.htaccess`
- PHP files in `uploads/` are blocked from execution via `.htaccess`
- **Delete `setup.php` after use** — it must not remain accessible in production

---

## Key Features Summary

**Public Website:**
- Responsive design (mobile-first, Bootstrap 5)
- All content sections populated dynamically from the database
- Contact form with client-side and server-side validation
- AI chatbot widget with rule-based responses
- Gallery with category filter and full lightbox
- Portfolio with industry filter and case study modal
- Testimonials with star rating filter and overall score
- Events auto-categorised as upcoming or past by date
- Animated stat counters on homepage
- Back to top button, smooth scrolling

**Admin Panel:**
- Secure login with session management
- Dashboard with inquiry stats and content counters
- Full CRUD for: Gallery, Articles, Services, Portfolio, Testimonials, Events
- Image upload with type/size validation and automatic deletion
- Inquiry management with pagination, search, and mark-as-read
- Flash messages for all user actions

---

*AI-Solutions Website — CET333 Assessment | University of Sunderland*
