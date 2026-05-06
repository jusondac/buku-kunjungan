# Buku Kunjungan - Quick Reference

## Installation (One-time setup)

```bash
# 1. Navigate to project
cd /home/rejka/Desktop/code/laravel/buku-kunjungan

# 2. Install packages
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate app key (if not already done)
php artisan key:generate

# 5. Update .env database credentials
# Edit .env and set:
# - DB_CONNECTION=mysql (or sqlite)
# - DB_HOST=127.0.0.1
# - DB_PORT=3306
# - DB_DATABASE=buku_kunjungan
# - DB_USERNAME=root
# - DB_PASSWORD=(your password)

# 6. Create database (MySQL only)
mysql -u root -p
CREATE DATABASE buku_kunjungan;
EXIT;

# 7. Run migrations with test data
php artisan migrate --seed
```

## Running the Application

```bash
# Start Laravel development server
php artisan serve

# Open in browser
# http://localhost:8000

# Press Ctrl+C to stop server
```

## Login Credentials (after migration)

```
Email: petugas@example.com
Password: password123
```

## Common Commands

### Database
```bash
php artisan migrate              # Run pending migrations
php artisan migrate:fresh --seed # Reset database with test data
php artisan migrate:rollback     # Undo migrations
php artisan db:seed              # Run seeders only
php artisan tinker               # Interactive shell
```

### Cache Clearing
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Testing
```bash
php artisan test
./vendor/bin/phpunit
```

### Code Quality
```bash
./vendor/bin/pint              # Code formatter
php artisan lint               # Lint all PHP files
```

## Project Routes

| Method | Route | Purpose | Auth |
|--------|-------|---------|------|
| GET | / | Guest form | ✗ |
| POST | /guests | Submit guest | ✗ |
| GET | /login | Login page | ✗ |
| POST | /login | Process login | ✗ |
| GET | /dashboard | Dashboard | ✓ |
| PATCH | /guests/{id}/status | Update status | ✓ |
| DELETE | /guests/{id} | Delete guest | ✓ |
| GET | /reports | Reports | ✓ |
| GET | /reports/export-csv | Export CSV | ✓ |
| GET | /reports/export-excel | Export Excel | ✓ |
| POST | /logout | Logout | ✓ |

## File Locations

| Purpose | Location |
|---------|----------|
| Controllers | app/Http/Controllers/ |
| Models | app/Models/ |
| Views | resources/views/ |
| Routes | routes/web.php |
| Migrations | database/migrations/ |
| Seeders | database/seeders/ |
| Config | config/ |
| Environment | .env |
| Documentation | README.md, SETUP.md |

## Troubleshooting

### Database Connection Error
```
Error: could not find driver (sqlite)
Solution: Use MySQL or install php-sqlite3 extension
```

### Access Denied Error
```
Error: Access denied for user 'root'@'localhost'
Solution: Update .env DB_PASSWORD with correct password
```

### Table Not Found
```
Error: Base table or view not found: guests
Solution: Run php artisan migrate --seed
```

### CSRF Token Error
```
Error: Token mismatch
Solution: Clear cache (php artisan cache:clear)
```

## Performance Tips

1. **Enable Query Logging** (development only)
```php
// Add to AppServiceProvider boot()
\DB::listen(function($query) {
    \Log::info($query->sql);
});
```

2. **Use Lazy Eager Loading**
```php
$guests = Guest::with('user')->paginate();
```

3. **Add Database Indexes**
```php
// In migration
Schema::create('guests', function (Blueprint $table) {
    $table->string('name')->index();
    $table->string('phone')->index();
});
```

## Development Workflow

```bash
# 1. Terminal 1 - Start server
php artisan serve

# 2. Terminal 2 (optional) - Watch for CSS changes
npm run dev

# 3. Open browser
# http://localhost:8000

# 4. Make code changes
# Server auto-reloads on file changes

# 5. Test features
# - Submit guest form
# - Login as staff
# - Search guests
# - Change status
# - Export data

# 6. Stop with Ctrl+C
```

## Directory Structure at a Glance

```
buku-kunjungan/
├── app/
│   ├── Http/Controllers/        # API/Web controllers
│   └── Models/                  # Eloquent models
├── database/
│   ├── migrations/              # Database schemas
│   ├── factories/               # Fake data generators
│   └── seeders/                 # Initial data seeders
├── resources/
│   └── views/                   # Blade templates
├── routes/                      # URL routing
├── config/                      # Application config
├── public/                      # Assets, index.php
├── storage/                     # Logs, temp files
├── vendor/                      # Dependencies
├── .env                         # Environment variables
├── artisan                      # CLI tool
├── composer.json               # PHP dependencies
└── README.md                   # This file
```

## Key Files to Edit

| Task | File |
|------|------|
| Change validation rules | app/Http/Controllers/GuestController.php |
| Modify form fields | resources/views/guests/form.blade.php |
| Update statistics logic | app/Http/Controllers/DashboardController.php |
| Add new fields to guests | database/migrations/*_create_guests_table.php |
| Change app locale | config/app.php or .env |
| Modify export format | app/Http/Controllers/ReportController.php |

## Environment Variables (.env)

```env
# App Settings
APP_NAME="Buku Kunjungan"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Locale
APP_LOCALE=id
APP_FALLBACK_LOCALE=id

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=buku_kunjungan
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database
```

## Resources

- **Laravel Documentation**: https://laravel.com/docs
- **Blade Template Guide**: https://laravel.com/docs/blade
- **Eloquent ORM**: https://laravel.com/docs/eloquent
- **Tailwind CSS**: https://tailwindcss.com/

## Contact & Support

For issues, check:
1. SETUP.md - Installation help
2. README.md - Feature documentation
3. PROJECT_SUMMARY.md - Complete overview

---

**Quick Links:**
- 📖 [SETUP.md](SETUP.md) - Installation guide
- 📕 [README.md](README.md) - Full documentation
- 📊 [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - Project overview
