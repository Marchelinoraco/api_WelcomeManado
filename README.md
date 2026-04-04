# Welcome Manado - Tourism Platform

Selamat datang di project **Welcome Manado**, sebuah platform pariwisata untuk kota Manado. Workspace ini terdiri dari tiga bagian utama: Backend API, Client (Customer-facing), dan Admin Dashboard.

## 🏗️ Struktur Proyek

Project ini menggunakan arsitektur monorepo sederhana dengan pembagian sebagai berikut:

- **`api_wm/`**: Backend API yang dibangun menggunakan Laravel 13.
- **`client_wm/`**: Frontend untuk pengunjung (Customer) menggunakan Vue 3 dan Vite.
- **`admin_wm/`**: Dashboard administrasi untuk pengelola menggunakan Vue 3 dan Vite.

---

## 🚀 Teknologi yang Digunakan

### 🔹 Backend (`api_wm`)

- **Framework**: Laravel 13 (PHP 8.3+)
- **Autentikasi**: Laravel Sanctum
- **Database**: SQLite (Default)
- **Fitur Utama**:
    - Manajemen Tour (Wisata)
    - Manajemen Kategori
    - Galeri Foto
    - Itinerary (Rencana Perjalanan)

### 🔹 Client & Admin (`client_wm` & `admin_wm`)

- **Framework**: Vue 3 (Composition API)
- **Build Tool**: Vite
- **Styling**: Tailwind CSS 4
- **Icons**: Lucide Vue Next
- **HTTP Client**: Axios (untuk komunikasi dengan API)
- **Routing**: Vue Router

---

## 🛠️ Cara Menjalankan Project

### 1. Menjalankan Backend (`api_wm`)

Pastikan Anda memiliki PHP 8.3+ dan Composer terinstal.

```bash
cd api_wm
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

_API akan berjalan di: `http://127.0.0.1:8000`_

### 2. Menjalankan Client (`client_wm`)

```bash
cd client_wm
npm install
npm run dev
```

_Client akan berjalan di: `http://127.0.0.1:5173`_

### 3. Menjalankan Admin (`admin_wm`)

```bash
cd admin_wm
npm install
npm run dev
```

_Admin akan berjalan di: `http://127.0.0.1:5174`_

---

## 📡 Endpoint API Utama (Public)

- `GET /api/categories` - Mendapatkan daftar kategori wisata.
- `GET /api/categories/{slug}` - Detail kategori berdasarkan slug.
- `GET /api/tours` - Mendapatkan daftar paket wisata.
- `GET /api/tours/{slug}` - Detail paket wisata lengkap dengan itinerary dan galeri.

---

## 📝 Catatan Pengembangan

- Konfigurasi Tailwind CSS menggunakan versi 4 yang lebih modern dan performant.
- Komunikasi antara Frontend dan Backend menggunakan Axios yang terkonfigurasi di `client_wm/src/services/api.js`.
- Gunakan `php artisan migrate --seed` untuk mendapatkan data awal (dummy) untuk pengembangan.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
