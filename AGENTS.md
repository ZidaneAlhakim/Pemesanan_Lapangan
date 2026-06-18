# AGENTS.md — SportVenue

Sistem Reservasi Lapangan Olahraga — PHP Native MVC + MySQL + Tailwind CSS

---

## 1. Ringkasan Sistem

| Atribut | Nilai |
|---------|-------|
| Nama | SportVenue |
| Arsitektur | MVC (Model-View-Controller) PHP Native |
| Database | MySQL via PDO |
| Frontend | Tailwind CSS (CDN) + Lucide Icons + Chart.js |
| URL Format | Clean URL (`.htaccess` rewrite) |
| Theme | Light / Dark mode (CSS class `dark`) |

---

## 2. Cara Menjalankan

### Apache (XAMPP / Laragon / cPanel)

1. Arahkan document root ke folder `public/`
2. Pastikan `mod_rewrite` aktif
3. Import `database/schema.sql` ke MySQL
4. Akses `http://localhost/`

### PHP Built-in Server (Development)

```bash
php -S localhost:8000 -t public/
```

### Wasmer

```bash
wasmer run php -- php -S 0.0.0.0:8080 -t public/
```

---

## 3. Struktur URL

Semua URL bersih via `.htaccess` rewrite ke `public/index.php`.

| Route | Method | Controller | View |
|-------|--------|------------|------|
| `/home` | GET | `FieldController@index` | `public/catalog` |
| `/schedule?id={id}&date={date}` | GET | `FieldController@schedule` | `public/schedule` |
| `/booking?id={id}&date={d}&time={t}` | GET | `BookingController@create` | `public/form` |
| `/booking/store` | POST | `BookingController@store` | redirect |
| `/summary` | GET | `BookingController@summary` | `public/summary` |
| `/payment?id={id}` | GET/POST | `PaymentController@upload` | `public/upload` |
| `/history?email={email}` | GET | `BookingController@history` | `public/history` |
| `/rating` | GET/POST | `RatingController@form/store` | `public/rating` |
| `/admin` | GET | `AdminController@dashboard` | `admin/dashboard` |
| `/admin/validate` | POST | `AdminController@validate` | redirect |

---

## 4. Routing

File: `src/core/Router.php`

Router menggunakan switch-case sederhana. Untuk menambah halaman baru:

```php
case 'about':
    (new AboutController())->index();
    break;
```

Buat controller baru di `src/controllers/` dan view di `src/views/`.

---

## 5. Design System

### 5.1 Brand Colors

```css
--sport-500: #f97316;   /* Primary — CTA, link, aktif */
--sport-600: #ea580c;   /* Hover */
--sport-50:  #fff7ed;   /* Background ringan */
--success:   #03ca77;   /* Tersedia / Lunas */
--danger:    #e31748;   /* Terisi / Batal */
--dark-navy: #001f3e;   /* Teks utama */
```

Full ramp (25–900) tersedia di Tailwind config header.

### 5.2 Typography

- Font: System fonts (`-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, ...`)
- Heading: `font-bold text-dark-navy dark:text-white`
- Body: `text-gray-600 dark:text-gray-400`

### 5.3 Component Patterns

| Component | Classes |
|-----------|---------|
| Card | `bg-white dark:bg-gray-900 rounded-zp-lg border border-gray-100 dark:border-gray-800 shadow-zp-sm hover:shadow-zp` |
| Button Primary | `px-4 py-2 bg-sport-500 text-white text-sm font-semibold rounded-zp-pill hover:bg-sport-600 transition-all shadow-zp-sm hover:shadow-zp` |
| Button Success | `bg-success text-white hover:bg-green-600` |
| Button Danger | `bg-danger/10 text-danger hover:bg-danger hover:text-white` |
| Input | `w-full px-3.5 py-2.5 text-sm border border-gray-200 dark:border-gray-700 rounded-zp bg-white dark:bg-gray-800 focus:ring-2 focus:ring-sport-500/50 focus:border-sport-500` |
| Badge | `inline-flex items-center gap-1 px-2.5 py-1 rounded-zp-pill text-xs font-semibold` |
| Table Cell | `px-6 py-4` |
| Status Badge Green | `bg-success/10 text-success` |
| Status Badge Red | `bg-danger/10 text-danger` |
| Status Badge Orange | `bg-sport-25 dark:bg-sport-900/20 text-sport-500` |

### 5.4 Icons

Menggunakan **Lucide** via CDN. Inisialisasi:

```html
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>lucide.createIcons();</script>
```

Contoh penggunaan:
```html
<i data-lucide="calendar"></i>
<i data-lucide="clock"></i>
<i data-lucide="user"></i>
<i data-lucide="search"></i>
<i data-lucide="upload"></i>
<i data-lucide="check-circle-2"></i>
<i data-lucide="alert-circle"></i>
<i data-lucide="star"></i>
<i data-lucide="shield"></i>
<i data-lucide="trophy"></i>
```

Setelah mengubah atribut `data-lucide` secara dinamis, panggil `lucide.createIcons()` untuk re-render.

### 5.5 Dark Mode

Mekanisme toggle:

```js
function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
}
```

Semua komponen harus menyertakan kelas dark variant: `dark:bg-gray-900`, `dark:text-white`, dll.

---

## 6. Layout Structure

### Public Pages (`viewFull`)

```
header.php          → Navbar (SportVenue logo, nav links, dark toggle, admin button)
  [page content]    → View spesifik
footer.php          → Footer + Lucide init + dark mode logic
```

### Admin Pages (`viewFull`)

Admin halaman menggunakan `viewFull()` yang tidak membungkus dengan header/footer publik.
Layout admin lengkap (sidebar + header + content) ada di dalam file view itu sendiri.

---

## 7. Storage Management

### Upload Directory

```
public/assets/uploads/
└── payments/
    └── YYYY/
        └── MM/
            └── payment_{uniqid}_{timestamp}.{ext}
```

### Helper Functions (`src/helpers/storage.php`)

| Fungsi | Deskripsi |
|--------|-----------|
| `storage_put($file, $subdir)` | Simpan file, return path relatif |
| `storage_delete($path)` | Hapus file dari disk |
| `storage_url($path)` | URL publik |
| `storage_exists($path)` | Cek file exists |

### Validasi Upload

| Aturan | Nilai |
|--------|-------|
| Max size | 2MB |
| MIME allowed | `image/jpeg`, `image/png`, `image/webp`, `application/pdf` |
| Rename | `payment_{uniqid}_{timestamp}.ext` |

### Cleanup Otomatis

- `cancel` booking → file bukti terhapus
- Re-upload bukti baru → file lama terhapus dulu

---

## 8. Database Schema

### Table: `fields`

| Column | Type | Notes |
|--------|------|-------|
| id | INT AUTO_INCREMENT PK | |
| name | VARCHAR(100) | Nama lapangan |
| sport | VARCHAR(50) | Futsal, Basket, dll |
| capacity | VARCHAR(50) | Kapasitas |
| price_per_hour | DECIMAL(10,2) | Harga per jam |
| description | TEXT | Deskripsi |
| created_at | TIMESTAMP | Default CURRENT_TIMESTAMP |

### Table: `bookings`

| Column | Type | Notes |
|--------|------|-------|
| id | INT AUTO_INCREMENT PK | |
| field_id | INT FK → fields.id | |
| customer_name | VARCHAR(120) | |
| customer_email | VARCHAR(150) | |
| customer_phone | VARCHAR(30) | |
| booking_date | DATE | |
| start_time | TIME | |
| duration_hours | INT | |
| total_price | DECIMAL(10,2) | |
| status | VARCHAR(30) | pending / confirmed / cancelled |
| payment_status | VARCHAR(30) | waiting / pending_validation / paid / cancelled |
| payment_proof | VARCHAR(255) | Path relatif file bukti |
| created_at | TIMESTAMP | |

### Table: `ratings`

| Column | Type | Notes |
|--------|------|-------|
| id | INT AUTO_INCREMENT PK | |
| booking_id | INT FK → bookings.id | |
| rating | INT | 1–5 |
| review | TEXT | |
| created_at | TIMESTAMP | |

---

## 9. Alur Data Lengkap

```
[Browser] → GET /schedule?id=1
    → .htaccess rewrite → public/index.php
    → Parse REQUEST_URI → url = "schedule"
    → Router::handle("schedule")
    → FieldController@schedule(1)
    → FieldModel::find(1) + getHourlyAvailability(1, date)
    → Model.php (Database::connect PDO)
    → Kembali array data ke Controller
    → Controller::view("public/schedule", ['field' => ..., 'slots' => ...])
    → Render header.php → schedule.php → footer.php
    → Return HTML ke browser
```

---

## 10. Checklist Kontribusi

Menambah fitur baru:

1. **Route** — Tambah case di `src/core/Router.php`
2. **Controller** — Buat method di controller terkait (atau controller baru)
3. **Model** — Buat query di model
4. **View** — Buat file `.php` di `src/views/` dengan Tailwind + Lucide
5. **Storage** — Jika ada upload, gunakan `src/helpers/storage.php`
6. **Layout** — Pastikan dark mode support (`dark:` variant)
7. **Icons** — Gunakan Lucide, jangan emoji
8. **Responsif** — Test di mobile (`sm:`, `md:` breakpoints)

---

## 11. File Tree

```
public/
├── .htaccess              # Clean URL rewrite
├── index.php              # Entry point
└── assets/
    ├── css/app.css        # Custom utilities
    ├── js/app.js          # Core JS (dark mode persist)
    └── uploads/payments/  # Uploaded payment proofs

src/
├── config/database.php    # PDO connection
├── core/
│   ├── Controller.php     # Base controller (view, redirect)
│   ├── Model.php          # Base model (DB connection)
│   └── Router.php         # URL routing
├── controllers/
│   ├── FieldController.php
│   ├── BookingController.php
│   ├── PaymentController.php
│   ├── AdminController.php
│   └── RatingController.php
├── models/
│   ├── FieldModel.php
│   ├── BookingModel.php
│   ├── UserModel.php
│   ├── PaymentModel.php
│   └── RatingModel.php
├── helpers/
│   ├── helper.php         # base_url, asset, flash, e, old
│   ├── storage.php        # File upload management
│   └── validation.php     # Booking validation
└── views/
    ├── layouts/
    │   ├── header.php     # Public layout (Tailwind)
    │   └── footer.php     # Public layout close
    ├── public/
    │   ├── catalog.php
    │   ├── schedule.php
    │   ├── form.php
    │   ├── summary.php
    │   ├── upload.php
    │   ├── history.php
    │   └── rating.php
    └── admin/
        └── dashboard.php  # Admin panel (Tailwind + Chart.js)

templates/
├── index.html             # Template public UI (tailwind)
├── admin.html             # Template admin UI (basic)
└── new.html               # Template admin UI (enhanced)

database/
└── schema.sql             # MySQL schema + seed data
```
