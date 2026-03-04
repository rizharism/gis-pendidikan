# GIS Pendidikan Kota Blitar 📍🎓

GIS Pendidikan Blitar adalah aplikasi Sistem Informasi Geografis (SIG) modern untuk memetakan dan mengelola sebaran fasilitas pendidikan di Kota Blitar. Dirancang dengan antarmuka premium, performa responsif, dan sistem manajemen data yang tangguh.

---

## 🚀 Fitur Utama

### 📡 Peta & GIS (Leaflet UI Custom)
- **Custom Layer Control**: Panel kendali layer peta dengan ikon hamburger dan toggle interaktif.
- **Detail Modal via AJAX**: Informasi rinci fasilitas pendidikan (tipe sekolah, akreditasi, video, dan galeri) yang dimuat secara dinamis tanpa refresh halaman.
- **Visualisasi Map Premium**: Layout peta yang dioptimalkan dengan palet warna brand yang terkurasi.

### 👥 Admin & Manajemen Pengguna
- **Simple RBAC**: Manajemen akun pengguna dengan peran **Super Admin** dan **Admin**.
- **Pembaruan Profil & Keamanan**: Fitur update informasi akun dan ubah kata sandi dengan verifikasi kata sandi saat ini (`DB::transaction` aman).
- **Manajemen Fasilitas (CRUD)**: Kelola data sekolah lengkap dengan foto, deskripsi, video, jam buka, dan galeri multimedia.

### ⚙️ Pengaturan Global (Branding)
- **Konfigurasi Branding**: Ubah Nama Aplikasi dan Logo secara dinamis dari dashboard.
- **Kontrol Tampilan**: Dukungan penuh **Dark Mode** dan toggle **Dev Mode**.
- **Default Basemap**: Pilihan basemap default untuk tampilan peta utama.

### 📊 Dashboard & Integritas Data
- **Statistik Interaktif**: Ringkasan data pendidikan menggunakan **ApexCharts** (Donut Chart).
- **Integritas Database**: Penggunaan `DB::transaction` pada setiap proses penyimpanan data kritis untuk menjamin keandalan data.

---

## 🛠️ Stack Teknologi

- **Core Framework**: [Laravel 12](https://laravel.com)
- **Frontend Interactivity**: [Alpine.js](https://alpinejs.dev)
- **UI Styling**: [Tailwind CSS](https://tailwindcss.com)
- **Peta & GIS**: [Leaflet.js](https://leafletjs.com)
- **Visualisasi Data**: [ApexCharts](https://apexcharts.com)

---

## 📦 Panduan Instalasi Lokal

1. **Clone & Install**:
   ```bash
   git clone https://github.com/rizHarism/gis-pendidikan.git
   cd gis-pendidikan
   # Pindah ke branch utama pengembangan jika perlu
   git checkout develop
   composer install && npm install
   ```

2. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Migration & Seeding**:
   ```bash
   # Inisialisasi tabel, data awal, settings, dan admin user
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

4. **Running**:
   ```bash
   # Terminal 1 (PHP)
   php artisan serve

   # Terminal 2 (Vite)
   npm run dev
   ```

> [!NOTE]
> User Admin Default: `admin@example.com` | Password: `password`

---

## 🤝 Kontribusi
Dikembangkan oleh **Kelompok 2 - PKL Semester 5**. Kami terbuka untuk masukan pengembangan fitur analisis spasial yang lebih lanjut.

## 📄 Lisensi
Tersedia di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
