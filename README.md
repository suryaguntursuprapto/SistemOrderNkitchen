# N-Kitchen - Sistem Pemesanan Makanan

Sistem manajemen pemesanan makanan berbasis web untuk N-Kitchen, dengan fitur lengkap untuk pelanggan dan admin.

## 📋 Persyaratan Sistem

Pastikan server Anda memenuhi persyaratan berikut:
*   **PHP**: ^8.2
*   **Database**: MySQL / MariaDB
*   **Composer**: Versi terbaru
*   **Node.js & NPM**: Untuk compile assets

## 🛠️ Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di local environment:

1.  **Clone Repository**
    ```bash
    git clone [url-repository]
    cd SistemPempek
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install & Compile Frontend Assets**
    ```bash
    npm install
    npm run build
    ```

4.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database serta API keys.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Migrasi Database**
    Jalankan migrasi untuk membuat tabel-tabel database.
    ```bash
    php artisan migrate
    ```
    *Opsional: Jalankan seeder untuk data dummy*
    ```bash
    php artisan db:seed
    ```

6.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

## 📦 Dependencies Utama

Project ini menggunakan library berikut:

| Package | Deskripsi |
| :--- | :--- |
| `laravel/framework` | Framework PHP utama (v12.x) |
| `laravel/ui` | Scaffolding untuk UI auth (Bootstrap/Tailwind) |
| `laravel/socialite` | Autentikasi via Social Media (Google Login) |
| `midtrans/midtrans-php` | Payment Gateway Intergration |
| `maatwebsite/excel` | Export/Import data Excel |

## 📑 Daftar Halaman & Fitur

### 👤 Halaman Pelanggan (Customer)

#### 1. Beranda & Menu
*   **Landing Page** (`/`): Menampilkan banner promo, kategori, dan produk unggulan.
*   **Menu** (`/menu`): Katalog lengkap produk dengan filter kategori dan pencarian.
*   **Detail Produk** (`/menu/{id}`): Informasi detail produk, harga, dan tombol tambah ke keranjang.

#### 2. Transaksi
*   **Keranjang** (`/cart`): Mengelola item yang akan dibeli.
*   **Checkout** (`/checkout`): Pengisian alamat pengiriman dan pemilihan metode pembayaran.
*   **Pembayaran** (`/payment/{order}`): Integrasi Midtrans untuk pembayaran aman.

#### 3. Akun & Riwayat
*   **Riwayat Pesanan** (`/customer/orders`): Daftar semua pesanan dengan status terkini.
*   **Detail Pesanan** (`/customer/orders/{id}`):
    *   Informasi status (Menunggu, Diproses, Dalam Perjalanan, Selesai).
    *   **Tracking Number**: Tampilan resi pengiriman dengan fitur copy.
    *   **Konfirmasi Terima**: Tombol untuk konfirmasi pesanan telah sampai.
*   **Profil** (`/profile`): Mengelola informasi akun.

#### 4. Layanan Pelanggan
*   **Live Chat** (`/customer/chat`):
    *   Chat real-time dengan Admin.
    *   **Chatbot Otomatis**: Menjawab pertanyaan umum (harga, menu, jam buka, dll).
    *   Indikator status admin (Online/Offline).

---

### 👑 Halaman Admin

#### 1. Dashboard
*   **Dashboard Utama** (`/admin/dashboard`): Statistik ringkas penjualan, pesanan baru, dan pendapatan.

#### 2. Manajemen Pesanan
*   **Kelola Pesanan** (`/admin/order`):
    *   **Filter Canggih**: Status, Periode Waktu (Harian/Mingguan/dll), Pencarian.
    *   **Update Status**: Mengubah status pesanan secara cepat.
    *   **Input Resi**: Memasukkan nomor resi yang otomatis mengubah status ke "Dalam Perjalanan".
*   **Detail Pesanan** (`/admin/order/{id}`): Informasi lengkap pemesan, produk, dan pembayaran.

#### 3. Manajemen Produk
*   **Kategori** (`/admin/category`): Tambah/Edit/Hapus kategori menu.
*   **Menu** (`/admin/menu`): Manajemen produk, harga, stok, dan foto.

#### 4. Layanan Pelanggan
*   **Pusat Pesan** (`/admin/message`):
    *   Daftar percakapan dari semua pelanggan.
    *   Indikator pesan belum dibaca.
    *   Balas pesan secara real-time.

#### 5. Laporan
*   **Laporan Penjualan**: Rekapitulasi transaksi yang bisa diexport ke Excel.

## 🔑 Akun Demo (Jika Menggunakan Seeder)

*   **Admin**: `admin@gmail.com` / `password`
*   **Customer**: `customer@gmail.com` / `password`
