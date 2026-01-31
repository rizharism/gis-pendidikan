<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

# GIS Pendidikan Kota Blitar 📍🎓

GIS Pendidikan Blitar adalah sebuah aplikasi Sistem Informasi Geografis (SIG) berbasis web yang dirancang untuk memetakan, mengelola, dan menampilkan sebaran fasilitas pendidikan di wilayah Kota Blitar. Aplikasi ini dibangun untuk memberikan visualisasi data spasial yang informatif bagi masyarakat dan manajemen data yang efisien bagi administrator.

---

## 🚀 Fitur Utama

-   **Dashboard Statistik Interaktif**: Ringkasan data (Total Fasilitas, SD, SMP, SMA, Universitas) dilengkapi dengan visualisasi Donut Chart dari **ApexCharts**.
-   **Manajemen Fasilitas (CRUD)**: Kelola data sekolah lengkap dengan foto, deskripsi, dan lokasi geografis.
-   **Integrasi Peta Spasial**: Menggunakan **Leaflet.js** untuk pemilihan titik koordinat (latitude & longitude) dengan antarmuka peta yang responsif.
-   **Auto-Jenjang Seeder**: Sistem impor data pintar yang otomatis mendeteksi jenjang pendidikan dari nama fasilitas.
-   **Sistem Autentikasi**: Area admin yang aman menggunakan **Laravel Breeze**.
-   **Penanganan Media**: Upload foto fasilitas dengan optimasi penamaan dan placeholder gambar default jika foto tidak tersedia.

---

## 🛠️ Stack Teknologi

-   **Core Framework**: [Laravel 11](https://laravel.com)
-   **Frontend Interactivity**: [Alpine.js](https://alpinejs.dev)
-   **UI Styling**: [Tailwind CSS](https://tailwindcss.com)
-   **Peta & GIS**: [Leaflet.js](https://leafletjs.com)
-   **Visualisasi Data**: [ApexCharts](https://apexcharts.com)
-   **Database**: MySQL / MariaDB

---

## 📦 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan projek di lingkungan lokal Anda:

### 1. Persiapan Awal
Pastikan Anda sudah menginstal:
-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MySQL

### 2. Clone & Install
```bash
# Clone repository
git clone https://github.com/rizHarism/gis-pendidikan.git
cd gis-pendidikan

# Install dependencies PHP
composer install

# Install dependencies JS
npm install
```

### 3. Konfigurasi Environment
Buat file `.env` dan sesuaikan pengaturan database Anda:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database & Storage
Jalankan migrasi beserta seeder untuk mengisi data awal (termasuk user admin default):
```bash
# Migrasi dan Seed Data
php artisan migrate:fresh --seed

# Hubungkan Storage untuk Foto
php artisan storage:link
```

> [!NOTE]
> User Admin Default: `admin@example.com` | Password: `password`

### 5. Menjalankan Aplikasi
Buka dua terminal dan jalankan perintah berikut:

**Terminal 1 (Backend):**
```bash
php artisan serve
```

**Terminal 2 (Frontend/Assets):**
```bash
npm run dev
```

Aplikasi dapat diakses di: `http://localhost:8000`

---

## 🤝 Kontribusi

Projek ini dikembangkan sebagai bagian dari tugas Praktek Kerja Lapangan (PKL) Kelompok 2. Kontribusi sangat dihargai untuk pengembangan fitur analisis spasial tingkat lanjut kedepannya.

## 📄 Lisensi

Tersedia di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
