# Buku Kunjungan - Sistem Manajemen Kunjungan Tamu

Sistem web-based yang sederhana dan mudah digunakan untuk mengelola data kunjungan tamu dengan fitur autentikasi staff, pencarian, laporan, dan export data.

## Fitur Utama

### 1. **Halaman Publik untuk Tamu (Tamu)**
- Form pendaftaran tamu dengan field: nama, telepon, alamat, dan keperluan
- Validasi form di sisi server
- Data otomatis tersimpan dengan status "menunggu"
- Pesan konfirmasi setelah submit

### 2. **Autentikasi Staff (Petugas)**
- Login page untuk staff
- Proteksi halaman dashboard dengan middleware autentikasi
- Session management yang aman

### 3. **Dashboard (Petugas)**
- Menampilkan list semua tamu dalam format tabel
- Kolom: Nama, Telepon, Alamat, Keperluan, Status, Waktu
- Pagination untuk kemudahan navigasi
- Statistik real-time: Total Tamu, Menunggu, Dilayani, Selesai

### 4. **Manajemen Status**
- Update status tamu: "menunggu", "dilayani", "selesai"
- Update inline dari dashboard

### 5. **Pencarian**
- Search berdasarkan nama atau nomor telepon
- Filter berdasarkan status
- Real-time search hasil

### 6. **Laporan**
- Filter laporan berdasarkan range tanggal
- Menampilkan statistik periode
- Export ke CSV dan Excel

### 7. **Export Data**
- Export ke format CSV dengan delimiter semicolon
- Export ke format Excel (.csv kompatibel)
- Include: ID, Nama, Telepon, Alamat, Keperluan, Status, Tanggal

## Teknologi yang Digunakan

- **Framework**: Laravel 13.7
- **Database**: MySQL / SQLite
- **ORM**: Eloquent
- **Frontend**: Blade Template Engine
- **Styling**: Tailwind CSS
- **Authentication**: Laravel Built-in Auth

## Struktur Project

```
buku-kunjungan/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── GuestController.php          # Menangani form tamu
│   │       ├── DashboardController.php      # Dashboard & management
│   │       ├── ReportController.php         # Laporan & export
│   │       └── Auth/AuthController.php      # Login/Logout
│   ├── Models/
│   │   └── Guest.php                        # Model tamu
│   └── Providers/
├── database/
│   ├── migrations/
│   │   └── *_create_guests_table.php        # Schema tamu
│   ├── factories/
│   │   └── GuestFactory.php                 # Test data factory
│   └── seeders/
│       └── DatabaseSeeder.php               # Seeder dengan test data
├── resources/
│   └── views/
│       ├── layout.blade.php                 # Template utama
│       ├── welcome.blade.php                # Halaman awal
│       ├── guests/
│       │   └── form.blade.php              # Form tamu (publik)
│       ├── auth/
│       │   └── login.blade.php             # Login staff
│       ├── dashboard/
│       │   └── index.blade.php             # Dashboard utama
│       └── reports/
│           └── index.blade.php             # Halaman laporan
├── routes/
│   └── web.php                              # Routes RESTful
├── config/
│   └── app.php                              # Konfigurasi locale
├── .env                                     # Konfigurasi lingkungan
└── README.md                                # Dokumentasi ini
```

## Instalasi dan Setup

### Prerequisites
- PHP 8.3+
- Composer
- MySQL Server (atau SQLite)
- Node.js & npm (opsional, untuk asset compilation)

### Langkah-langkah Instalasi

1. **Clone atau Extract Project**
```bash
cd buku-kunjungan
```

2. **Install Dependencies**
```bash
composer install
```

3. **Generate App Key**
```bash
php artisan key:generate
```

4. **Setup Database**

#### Opsi A: Menggunakan MySQL (Recommended)

a. Buat database baru:
```bash
mysql -u root -p
CREATE DATABASE buku_kunjungan;
EXIT;
```

b. Update .env file:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=buku_kunjungan
DB_USERNAME=root
DB_PASSWORD=your_password
```

c. Run migrations dan seeders:
```bash
php artisan migrate --seed
```

#### Opsi B: Menggunakan SQLite

a. Pastikan PHP SQLite extension terinstall:
```bash
php -m | grep sqlite
```

b. Update .env file:
```bash
DB_CONNECTION=sqlite
DB_DATABASE=/full/path/to/database.sqlite
```

c. Create dan migrate database:
```bash
touch database/database.sqlite
php artisan migrate --seed
```

5. **Run Laravel Development Server**
```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

6. **(Opsional) Compile Frontend Assets**
```bash
npm install
npm run dev
```

## Menggunakan Aplikasi

### Login Awal (Test Credentials)

**Email**: `petugas@example.com`  
**Password**: `password123`

### User Flow

#### 1. Sebagai Tamu
- Buka `http://localhost:8000/`
- Klik "Masuk sebagai Tamu" atau langsung isi formulir
- Isi: Nama, Telepon, Alamat, Keperluan
- Klik "Simpan"
- Data tersimpan dengan status "menunggu"

#### 2. Sebagai Petugas/Staff
- Buka `http://localhost:8000/login`
- Login dengan credentials di atas
- Dashboard menampilkan semua tamu
- Gunakan search untuk mencari nama/telepon
- Update status tamu dari dropdown
- Akses Laporan untuk filter tanggal dan export

#### 3. Export Data
- Buka "Laporan"
- Pilih tanggal mulai dan akhir
- Klik "Export CSV" atau "Export Excel"
- File akan didownload otomatis

## Routes (API & Web)

```
GET     /                           → Halaman form tamu (publik)
POST    /guests                      → Simpan data tamu
GET     /login                       → Halaman login
POST    /login                       → Proses login

GET     /dashboard                   → Dashboard (auth required)
PATCH   /guests/{id}/status         → Update status tamu
DELETE  /guests/{id}                 → Hapus data tamu
GET     /reports                     → Laporan (auth required)
GET     /reports/export-csv         → Export CSV
GET     /reports/export-excel       → Export Excel
POST    /logout                      → Logout
```

## Database Schema

### Guests Table
```sql
CREATE TABLE guests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    purpose TEXT NOT NULL,
    status ENUM('menunggu', 'dilayani', 'selesai') DEFAULT 'menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Validasi Input

### Form Tamu
- Nama: Required, max 255 karakter
- Telepon: Required, max 20 karakter
- Alamat: Required, max 500 karakter
- Keperluan: Required, max 500 karakter

### Status Update
- Status: Required, harus salah satu dari (menunggu, dilayani, selesai)

## Fitur Keamanan

- CSRF Protection pada semua form
- Server-side validation
- Password hashing menggunakan Bcrypt
- Session-based authentication
- SQL Injection prevention (Eloquent ORM)
- XSS Protection (Blade Template Escaping)

## Troubleshooting

### 1. Error: "could not find driver"
**Solusi**: Gunakan MySQL atau install PHP SQLite extension
```bash
# Ubuntu/Debian
sudo apt-get install php-sqlite3

# CentOS/RHEL
sudo yum install php-pdo php-sqlite
```

### 2. Error: "Access denied for user 'root'@'localhost'"
**Solusi**: 
- Verifikasi MySQL running: `mysql -u root -p`
- Update .env dengan password yang benar
- Atau buat user baru:
```sql
CREATE USER 'laravel'@'127.0.0.1' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON buku_kunjungan.* TO 'laravel'@'127.0.0.1';
FLUSH PRIVILEGES;
```

### 3. Error: "Base table or view not found"
**Solusi**: Run migrations
```bash
php artisan migrate
```

### 4. Style tidak loading
**Solusi**: Compile Tailwind CSS
```bash
npm run dev
```

## Development Commands

```bash
# Generate model dengan migration
php artisan make:model Guest -m

# Generate controller
php artisan make:controller GuestController

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (drop all tables)
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Customization

### Mengubah Lokalisasi
Edit file `.env`:
```
APP_LOCALE=en          # Ubah ke id untuk Indonesian
```

### Menambah Petugas Baru
```bash
php artisan tinker
>>> $user = User::create(['name' => 'Nama', 'email' => 'email@example.com', 'password' => Hash::make('password')]);
>>> exit
```

### Mengubah Kolom Form Tamu
Edit file `app/Models/Guest.php` dan migration file

## Support & Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/)
- [Blade Templates](https://laravel.com/docs/blade)

## License

MIT License

## Author

Built with ❤️ for easy guest management

---

**Version**: 1.0.0  
**Last Updated**: 2024  
**Status**: Production Ready
