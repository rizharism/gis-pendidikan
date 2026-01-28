<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p>

## About GIS Pendidikan Blitar

Projek GIS Pendidikan Blitar adalah sebuah aplikasi web untuk mencatat dan menampilkan data fasilitas pendidikan di Kota Blitar menggunakan kerangka Laravel dan Leaflet sebagai peta interaktif.

## Getting Started

### Prerequisites

Pastikan Anda telah menginstal:

- PHP
- Node.js
- Composer
- Git

### Installation

1. **Clone Repository**

    ```bash
    git clone https://github.com/your-repository/gis-pendidikan-blitar.git
    cd gis-pendidikan-blitar
    ```

2. **Install Dependencies**
   Install dependencies PHP menggunakan Composer:

    ```bash
    composer install
    ```

3. **Install JavaScript Dependencies**
   Install dependencies JavaScript menggunakan npm:

    ```bash
    npm install
    ```

4. **Environment Configuration (optional untuk saat ini)**
   Buat file `.env` dari template `.env.example` dan sesuaikan konfigurasi database:

    ```bash
    cp .env.example .env
    ```

    Kemudian, generate aplikasi key Laravel:

    ```bash
    php artisan key:generate
    ```

5. **Database Migration (optional untuk saat ini)**
   Jalankan migrasi database untuk membuat tabel yang diperlukan:

    ```bash
    php artisan migrate
    ```

6. **Run Development Server**
   Anda harus menjalankan dua server: satu untuk backend Laravel dan satu untuk frontend Vite.

    **Backend (Laravel)**

    ```bash
    php artisan serve
    ```

    **Frontend (Vite)**
    Buka terminal baru dan jalankan perintah berikut:

    ```bash
    npm run dev
    ```

    Setelah menjalankan kedua perintah di atas, aplikasi akan tersedia di `http://localhost:8000`.

## Features (belum tersedia)

- Menambahkan data fasilitas pendidikan.
- Memperbarui data fasilitas pendidikan.
- Menampilkan data fasilitas pendidikan pada peta interaktif menggunakan Leaflet.
- Fitur pencarian fasilitas pendidikan dengan tampilan sugesti otomatis saat mengetik.
- Menyimpan gambar/foto fasilitas pendidikan.
- Menyimpan deskripsi detail fasilitas pendidikan.
- Mengambil koordinat.latitude & longitude saat peta digeser.

## Learning Laravel

Laravel memiliki dokumentasi yang komprehensif dan perpustakaan video tutorial yang rinci. Anda dapat mengunjungi [Dokumentasi Laravel](https://laravel.com/docs) untuk lebih banyak informasi. Jika Anda lebih suka belajar secara visual, [Laracasts](https://laracasts.com) menyediakan ribuan video tutorial tentang berbagai topik termasuk Laravel, PHP, unit testing, dan JavaScript.

## Code of Conduct

Agar masyarakat Laravel tetap ramah dan inklusif, silakan baca dan patuhi [Kode Etik](https://laravel.com/docs/contributions#code-of-conduct).

## License

Projek GIS Pendidikan Blitar tersedia sebagai perangkat lunak open-source dengan lisensi [MIT](https://opensource.org/licenses/MIT).
