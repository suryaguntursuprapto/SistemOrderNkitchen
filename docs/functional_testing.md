# Tabel Functional Testing - Sistem Pempek N'Kitchen

Dokumen ini berisi tabel pengujian fungsional untuk semua fitur pada sistem Pempek N'Kitchen, meliputi fitur Otentikasi (Login/Register), Customer, dan Admin.

---

## 1. Tabel Pengujian Login

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Login | Data Valid | Masukkan email dan password yang benar | Login berhasil, redirect ke dashboard sesuai role | Login berhasil, user diarahkan ke dashboard sesuai role (admin/customer) | ✅ Pass |
| 2 | Login | Email Salah | Masukkan email yang tidak terdaftar | Login gagal, muncul pesan error "Email atau password salah" | Muncul pesan error "Email atau password salah" | ✅ Pass |
| 3 | Login | Password Salah | Masukkan email benar, password salah | Login gagal, muncul pesan error "Email atau password salah" | Muncul pesan error "Email atau password salah" | ✅ Pass |
| 4 | Login | Field Kosong | Mengirim form tanpa mengisi email/password | Validasi gagal, muncul pesan "Field wajib diisi" | Muncul validasi "The email field is required" | ✅ Pass |
| 5 | Login | Rate Limiting | Melakukan 5+ percobaan login gagal dalam 1 menit | Sistem menolak request, muncul pesan throttle | Muncul pesan "Too many login attempts. Please try again in X seconds" | ✅ Pass |
| 6 | Login Google | OAuth Valid | Klik tombol "Login dengan Google" dan autentikasi | Login berhasil via Google OAuth | Redirect ke Google, autentikasi berhasil, user login | ✅ Pass |
| 7 | Redirect Login | Akses halaman terproteksi tanpa login | Mencoba akses /admin/dashboard tanpa login | Redirect ke halaman login | User di-redirect ke halaman /login | ✅ Pass |

---

## 2. Tabel Pengujian Register

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Register | Data Lengkap Valid | Isi semua field dengan data valid dan submit | Registrasi berhasil, email verifikasi terkirim | Akun berhasil dibuat, email verifikasi terkirim | ✅ Pass |
| 2 | Register | Email Sudah Terdaftar | Gunakan email yang sudah ada di sistem | Registrasi gagal, muncul pesan "Email sudah terdaftar" | Muncul pesan "The email has already been taken" | ✅ Pass |
| 3 | Register | Password Tidak Cocok | Masukkan konfirmasi password yang berbeda | Validasi gagal, muncul pesan konfirmasi password tidak cocok | Muncul pesan "The password confirmation does not match" | ✅ Pass |
| 4 | Register | Password Terlalu Pendek | Masukkan password kurang dari 8 karakter | Validasi gagal, muncul pesan password minimal 8 karakter | Muncul pesan "The password must be at least 8 characters" | ✅ Pass |
| 5 | Register | Field Kosong | Submit form dengan field wajib kosong | Validasi gagal, muncul pesan field wajib diisi | Muncul validasi untuk setiap field yang kosong | ✅ Pass |
| 6 | Register | Email Invalid | Masukkan email format tidak valid | Validasi gagal, muncul pesan format email tidak valid | Muncul pesan "The email must be a valid email address" | ✅ Pass |
| 7 | Register | Rate Limiting | Melakukan 3+ registrasi dalam 1 menit | Sistem menolak request, muncul pesan throttle | Muncul pesan throttle setelah 3 percobaan | ✅ Pass |

---

## 3. Tabel Pengujian Verifikasi Email

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Verifikasi Email | Link Valid | Klik link verifikasi dari email | Email terverifikasi, redirect ke dashboard | Email terverifikasi, user diarahkan ke dashboard | ✅ Pass |
| 2 | Verifikasi Email | Link Expired/Invalid | Klik link yang sudah kedaluwarsa atau salah | Muncul pesan error link tidak valid | Halaman menampilkan error "Invalid signature" | ✅ Pass |
| 3 | Kirim Ulang Verifikasi | Request Resend | Klik tombol "Kirim Ulang Email Verifikasi" | Email verifikasi baru terkirim, muncul pesan sukses | Muncul pesan "Link verifikasi baru telah dikirim!" | ✅ Pass |
| 4 | Akses Tanpa Verifikasi | Akses dashboard | User login tanpa verifikasi email | Redirect ke halaman verifikasi email | User diarahkan ke halaman /email/verify | ✅ Pass |

---

## 4. Tabel Pengujian Lupa Password

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lupa Password | Email Terdaftar | Masukkan email yang terdaftar di form lupa password | Link reset password terkirim ke email | Email reset password berhasil terkirim | ✅ Pass |
| 2 | Lupa Password | Email Tidak Terdaftar | Masukkan email yang tidak ada di sistem | Muncul pesan email tidak ditemukan | Muncul pesan "We can't find a user with that email address" | ✅ Pass |
| 3 | Reset Password | Link Valid | Klik link reset dan masukkan password baru valid | Password berhasil diubah, dapat login dengan password baru | Password berhasil diubah, login dengan password baru berhasil | ✅ Pass |
| 4 | Reset Password | Link Invalid/Expired | Gunakan link reset yang sudah expired | Muncul pesan link tidak valid atau kedaluwarsa | Halaman menampilkan error token invalid | ✅ Pass |
| 5 | Reset Password | Password Tidak Cocok | Masukkan konfirmasi password berbeda | Validasi gagal, muncul pesan password tidak cocok | Muncul pesan "The password confirmation does not match" | ✅ Pass |

---

## 5. Tabel Pengujian Customer - Dashboard

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Dashboard Customer | Akses Dashboard | Login sebagai customer, akses /customer/dashboard | Dashboard ditampilkan dengan ringkasan data | Dashboard customer ditampilkan dengan statistik pesanan | ✅ Pass |
| 2 | Dashboard Customer | Statistik Pesanan | Lihat statistik pesanan di dashboard | Menampilkan jumlah pesanan dan status terkini | Statistik pesanan (pending, proses, selesai) ditampilkan | ✅ Pass |
| 3 | Dashboard Customer | Navigasi Menu | Klik link navigasi ke menu/pesanan | Redirect ke halaman yang sesuai | Navigasi berfungsi, redirect ke halaman tujuan | ✅ Pass |

---

## 6. Tabel Pengujian Customer - Menu

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat Menu | Daftar Menu | Akses halaman /customer/menu | Semua menu ditampilkan dengan gambar, nama, harga | Daftar menu tampil dengan gambar, nama, dan harga | ✅ Pass |
| 2 | Lihat Menu | Filter Kategori | Pilih kategori tertentu dari filter | Hanya menu dari kategori tersebut yang ditampilkan | Menu terfilter sesuai kategori yang dipilih | ✅ Pass |
| 3 | Detail Menu | Klik Menu | Klik salah satu item menu | Halaman detail menu ditampilkan dengan deskripsi lengkap | Detail menu tampil dengan deskripsi, harga, kategori | ✅ Pass |
| 4 | Tambah ke Keranjang | Tambah Item | Klik tombol "Tambah ke Keranjang" | Item berhasil ditambahkan ke keranjang belanja | Item masuk ke keranjang, muncul notifikasi sukses | ✅ Pass |
| 5 | Tambah ke Keranjang | Ubah Kuantitas | Ubah jumlah item sebelum tambah ke keranjang | Kuantitas tersimpan dengan benar di keranjang | Kuantitas sesuai dengan yang dipilih di keranjang | ✅ Pass |

---

## 7. Tabel Pengujian Customer - Pemesanan (Order)

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Keranjang Belanja | Lihat Keranjang | Akses halaman /customer/order | Keranjang ditampilkan dengan item yang dipilih | Halaman keranjang tampil dengan daftar item | ✅ Pass |
| 2 | Keranjang Belanja | Ubah Kuantitas | Ubah jumlah item di keranjang | Kuantitas dan total harga terupdate | Kuantitas berubah dan total harga ter-recalculate | ✅ Pass |
| 3 | Keranjang Belanja | Hapus Item | Klik tombol hapus pada item | Item berhasil dihapus dari keranjang | Item terhapus dari keranjang | ✅ Pass |
| 4 | Checkout | Data Lengkap | Isi alamat pengiriman lengkap dan submit | Pesanan berhasil dibuat | Pesanan berhasil tersimpan di database | ✅ Pass |
| 5 | Checkout | Data Tidak Lengkap | Kirim checkout tanpa alamat lengkap | Validasi gagal, muncul pesan field wajib diisi | Muncul validasi untuk field yang belum diisi | ✅ Pass |
| 6 | Cek Ongkir | Pilih Tujuan | Cari dan pilih destinasi pengiriman | Daftar kurir dan ongkos kirim ditampilkan | Daftar kurir dengan estimasi ongkir tampil | ✅ Pass |
| 7 | Cek Ongkir | Pilih Kurir | Pilih salah satu opsi kurir | Ongkos kirim ditambahkan ke total pesanan | Ongkir terhitung dan ditambahkan ke total | ✅ Pass |
| 8 | Buat Pesanan | Submit Order | Klik tombol "Buat Pesanan" setelah data lengkap | Pesanan tersimpan, redirect ke halaman pembayaran | Pesanan tersimpan, diarahkan ke halaman payment | ✅ Pass |
| 9 | Keranjang Kosong | Checkout Kosong | Mencoba checkout dengan keranjang kosong | Sistem menolak, muncul pesan keranjang kosong | Muncul pesan "Keranjang belanja kosong" | ✅ Pass |

---

## 8. Tabel Pengujian Customer - Pembayaran

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Halaman Pembayaran | Akses Payment | Akses halaman pembayaran pesanan | Detail pembayaran dan instruksi ditampilkan | Halaman pembayaran tampil dengan detail pesanan dan total | ✅ Pass |
| 2 | Pembayaran Midtrans | Bayar Online | Klik tombol bayar, selesaikan pembayaran Midtrans | Pembayaran berhasil, status pesanan terupdate | Snap Midtrans tampil, pembayaran berhasil, status terupdate | ✅ Pass |
| 3 | Pembayaran Midtrans | Pembayaran Gagal | Batalkan pembayaran di Midtrans | Status tetap pending, dapat coba bayar ulang | Status tetap pending, tombol bayar ulang tersedia | ✅ Pass |
| 4 | Upload Bukti Bayar | Upload Valid | Upload gambar bukti transfer dengan ekstensi valid | Bukti berhasil terupload, menunggu verifikasi | Bukti terupload, status "Menunggu Verifikasi" | ✅ Pass |
| 5 | Upload Bukti Bayar | File Invalid | Upload file bukan gambar atau size terlalu besar | Validasi gagal, muncul pesan error format file | Muncul pesan "File harus berupa gambar (jpg, png)" | ✅ Pass |
| 6 | Callback Midtrans | Notifikasi Sukses | Sistem menerima callback dari Midtrans | Status pembayaran dan pesanan terupdate otomatis | Status payment dan order terupdate otomatis | ✅ Pass |

---

## 9. Tabel Pengujian Customer - Riwayat Pesanan

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Riwayat Pesanan | Lihat Daftar | Akses halaman /customer/orders | Semua pesanan customer ditampilkan | Daftar semua pesanan customer tampil | ✅ Pass |
| 2 | Riwayat Pesanan | Filter Status | Filter berdasarkan status pesanan | Hanya pesanan dengan status tersebut ditampilkan | Pesanan terfilter sesuai status yang dipilih | ✅ Pass |
| 3 | Detail Pesanan | Klik Pesanan | Klik salah satu pesanan untuk lihat detail | Detail pesanan lengkap ditampilkan | Detail item, alamat, pembayaran, status tampil | ✅ Pass |
| 4 | Tracking Pesanan | Lihat Tracking | Klik tombol tracking pada pesanan yang dikirim | Informasi tracking kurir ditampilkan | Tracking info dari Biteship tampil | ✅ Pass |
| 5 | Konfirmasi Terima | Pesanan Sampai | Klik tombol "Konfirmasi Pesanan Diterima" | Status pesanan berubah menjadi "Selesai" | Status pesanan berubah menjadi "completed" | ✅ Pass |
| 6 | Pesan Lagi | Reorder | Klik tombol "Pesan Lagi" pada pesanan sebelumnya | Item dari pesanan tersebut ditambahkan ke keranjang | Item dari pesanan lama masuk ke keranjang | ✅ Pass |

---

## 10. Tabel Pengujian Customer - Chat

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Chat | Akses Chat | Akses halaman /customer/chat | Halaman chat dengan admin ditampilkan | Halaman chat tampil dengan interface messaging | ✅ Pass |
| 2 | Kirim Pesan | Pesan Text | Ketik pesan dan klik kirim | Pesan berhasil terkirim, muncul di chat | Pesan terkirim dan tampil di chat bubble | ✅ Pass |
| 3 | Kirim Pesan | Pesan Kosong | Klik kirim tanpa mengetik pesan | Sistem menolak, pesan tidak terkirim | Tombol kirim tidak aktif jika pesan kosong | ✅ Pass |
| 4 | Terima Pesan | Notifikasi | Admin membalas chat | Pesan balasan muncul di chat customer | Pesan admin tampil secara real-time | ✅ Pass |
| 5 | Hapus Chat | Clear History | Klik tombol hapus riwayat chat | Riwayat chat berhasil dihapus | Riwayat chat terhapus, chat kosong | ✅ Pass |

---

## 11. Tabel Pengujian Admin - Dashboard

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Dashboard Admin | Akses Dashboard | Login sebagai admin, akses /admin/dashboard | Dashboard admin dengan statistik ditampilkan | Dashboard admin tampil dengan cards statistik | ✅ Pass |
| 2 | Dashboard Admin | Statistik Penjualan | Lihat ringkasan penjualan | Total pendapatan, jumlah pesanan ditampilkan | Total revenue, order count tampil dengan benar | ✅ Pass |
| 3 | Dashboard Admin | Pesanan Terbaru | Lihat daftar pesanan terbaru | Pesanan pending/baru ditampilkan | Tabel pesanan terbaru tampil di dashboard | ✅ Pass |
| 4 | Dashboard Admin | Grafik | Lihat grafik penjualan | Grafik penjualan per periode ditampilkan | Chart penjualan per bulan/hari tampil | ✅ Pass |

---

## 12. Tabel Pengujian Admin - Kelola Menu

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat Menu | Daftar Menu | Akses halaman /admin/menu | Semua menu ditampilkan dalam tabel | Tabel menu tampil dengan pagination | ✅ Pass |
| 2 | Tambah Menu | Data Lengkap | Isi semua field menu (nama, harga, deskripsi, gambar, kategori) dan simpan | Menu berhasil ditambahkan | Menu baru tersimpan, redirect ke index dengan pesan sukses | ✅ Pass |
| 3 | Tambah Menu | Data Tidak Lengkap | Submit form tanpa mengisi field wajib | Validasi gagal, muncul pesan error | Muncul pesan validasi untuk setiap field wajib | ✅ Pass |
| 4 | Tambah Menu | Gambar Invalid | Upload file bukan gambar | Validasi gagal, format file tidak didukung | Muncul pesan "File harus berupa gambar" | ✅ Pass |
| 5 | Edit Menu | Perubahan Data | Ubah nama/harga/deskripsi menu dan simpan | Data menu berhasil diperbarui | Data terupdate, muncul pesan "Menu berhasil diperbarui" | ✅ Pass |
| 6 | Edit Menu | Ganti Gambar | Upload gambar baru untuk menu | Gambar menu berhasil terupdate | Gambar lama diganti dengan yang baru | ✅ Pass |
| 7 | Hapus Menu | Konfirmasi Hapus | Klik hapus dan konfirmasi penghapusan | Menu berhasil dihapus dari sistem | Menu terhapus, muncul pesan sukses | ✅ Pass |
| 8 | Detail Menu | Lihat Detail | Klik menu untuk lihat detail | Halaman detail menu ditampilkan | Detail menu lengkap tampil | ✅ Pass |

---

## 13. Tabel Pengujian Admin - Kelola Kategori

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat Kategori | Daftar Kategori | Akses halaman /admin/category | Semua kategori ditampilkan | Daftar kategori tampil dalam tabel | ✅ Pass |
| 2 | Tambah Kategori | Data Lengkap | Isi nama kategori dan simpan | Kategori berhasil ditambahkan | Kategori tersimpan dengan pesan sukses | ✅ Pass |
| 3 | Tambah Kategori | Nama Kosong | Submit form tanpa mengisi nama | Validasi gagal, muncul pesan field wajib | Muncul pesan "The name field is required" | ✅ Pass |
| 4 | Tambah Kategori | Nama Duplikat | Masukkan nama kategori yang sudah ada | Validasi gagal, kategori sudah ada | Muncul pesan "The name has already been taken" | ✅ Pass |
| 5 | Edit Kategori | Perubahan Nama | Ubah nama kategori dan simpan | Nama kategori berhasil diperbarui | Nama terupdate dengan pesan sukses | ✅ Pass |
| 6 | Hapus Kategori | Konfirmasi Hapus | Klik hapus dan konfirmasi | Kategori berhasil dihapus | Kategori terhapus dari sistem | ✅ Pass |
| 7 | Hapus Kategori | Kategori Digunakan | Hapus kategori yang masih memiliki menu | Sistem menolak atau memberi peringatan | Muncul peringatan kategori masih digunakan | ✅ Pass |

---

## 14. Tabel Pengujian Admin - Kelola Pesanan

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat Pesanan | Daftar Pesanan | Akses halaman /admin/order | Semua pesanan ditampilkan dalam tabel | Tabel pesanan tampil dengan filter dan pagination | ✅ Pass |
| 2 | Lihat Pesanan | Filter Status | Filter pesanan berdasarkan status | Hanya pesanan dengan status tersebut ditampilkan | Pesanan terfilter sesuai status | ✅ Pass |
| 3 | Detail Pesanan | Lihat Detail | Klik pesanan untuk lihat detail | Detail pesanan lengkap (item, alamat, pembayaran) | Semua detail pesanan tampil lengkap | ✅ Pass |
| 4 | Update Status | Ubah Status | Ubah status pesanan (pending → diproses → dikirim) | Status pesanan berhasil diupdate | Status terupdate dengan notifikasi | ✅ Pass |
| 5 | Tracking | Lihat Tracking | Klik tombol tracking pada pesanan yang dikirim | Informasi tracking dari Biteship ditampilkan | Tracking info dari API Biteship tampil | ✅ Pass |
| 6 | Buat Pengiriman | Create Biteship | Klik tombol buat pengiriman Biteship | Order pengiriman berhasil dibuat di Biteship | Order Biteship berhasil di-create, waybill diterima | ✅ Pass |
| 7 | Shipping Label | Download Label | Klik tombol download shipping label | Label pengiriman berhasil diunduh | PDF label berhasil di-download | ✅ Pass |
| 8 | Hapus Pesanan | Konfirmasi Hapus | Klik hapus dan konfirmasi | Pesanan berhasil dihapus | Pesanan terhapus dari sistem | ✅ Pass |

---

## 15. Tabel Pengujian Admin - Kelola Chat/Message

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat Pesan | Daftar Customer | Akses halaman /admin/message | Daftar customer dengan chat aktif ditampilkan | Daftar customer tampil dengan preview pesan terakhir | ✅ Pass |
| 2 | Buka Chat | Pilih Customer | Klik customer untuk buka percakapan | Riwayat chat dengan customer ditampilkan | Percakapan lengkap tampil dalam interface chat | ✅ Pass |
| 3 | Balas Pesan | Kirim Balasan | Ketik balasan dan kirim | Pesan berhasil terkirim ke customer | Pesan terkirim dan tampil di chat | ✅ Pass |
| 4 | Balas Pesan | Pesan Kosong | Kirim balasan kosong | Sistem menolak, pesan tidak terkirim | Tombol kirim tidak aktif jika kosong | ✅ Pass |
| 5 | Hapus Chat | Clear History | Klik hapus riwayat dengan customer | Riwayat chat berhasil dihapus | Chat history terhapus | ✅ Pass |
| 6 | Fetch Pesan | Auto Refresh | Tunggu beberapa detik pada halaman chat | Pesan baru otomatis dimuat | Pesan baru tampil tanpa refresh manual | ✅ Pass |

---

## 16. Tabel Pengujian Admin - Kelola Pembelian (Purchase)

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat Pembelian | Daftar Pembelian | Akses halaman /admin/purchases | Semua transaksi pembelian ditampilkan | Tabel pembelian tampil dengan data lengkap | ✅ Pass |
| 2 | Tambah Pembelian | Data Lengkap | Isi semua field pembelian dan simpan | Transaksi pembelian berhasil dicatat | Pembelian tersimpan dengan jurnal otomatis | ✅ Pass |
| 3 | Tambah Pembelian | Data Tidak Lengkap | Submit form dengan field kosong | Validasi gagal, muncul pesan error | Muncul validasi untuk field wajib | ✅ Pass |
| 4 | Edit Pembelian | Perubahan Data | Ubah data pembelian dan simpan | Data pembelian berhasil diperbarui | Data terupdate dengan pesan sukses | ✅ Pass |
| 5 | Hapus Pembelian | Konfirmasi Hapus | Klik hapus dan konfirmasi | Transaksi pembelian berhasil dihapus | Pembelian dan jurnal terkait terhapus | ✅ Pass |

---

## 17. Tabel Pengujian Admin - Kelola Pengeluaran (Expense)

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat Pengeluaran | Daftar Expense | Akses halaman /admin/expense | Semua pengeluaran ditampilkan | Daftar expense tampil dalam tabel | ✅ Pass |
| 2 | Tambah Pengeluaran | Data Lengkap | Isi deskripsi, jumlah, tanggal dan simpan | Pengeluaran berhasil dicatat | Expense tersimpan dengan jurnal otomatis | ✅ Pass |
| 3 | Tambah Pengeluaran | Data Tidak Lengkap | Submit form dengan field kosong | Validasi gagal, muncul pesan error | Muncul pesan validasi field wajib | ✅ Pass |
| 4 | Hapus Pengeluaran | Konfirmasi Hapus | Klik hapus dan konfirmasi | Pengeluaran berhasil dihapus | Expense dan jurnal terkait terhapus | ✅ Pass |

---

## 18. Tabel Pengujian Admin - Chart of Account

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Lihat CoA | Daftar Akun | Akses halaman /admin/chart-of-accounts | Semua akun keuangan ditampilkan | Daftar CoA tampil dengan kode, nama, tipe | ✅ Pass |
| 2 | Tambah Akun | Data Lengkap | Isi kode, nama, tipe akun dan simpan | Akun berhasil ditambahkan | Akun tersimpan dengan pesan sukses | ✅ Pass |
| 3 | Tambah Akun | Kode Duplikat | Masukkan kode akun yang sudah ada | Validasi gagal, kode sudah digunakan | Muncul pesan "The code has already been taken" | ✅ Pass |
| 4 | Edit Akun | Perubahan Data | Ubah nama atau tipe akun dan simpan | Data akun berhasil diperbarui | Data terupdate dengan pesan sukses | ✅ Pass |
| 5 | Hapus Akun | Konfirmasi Hapus | Klik hapus dan konfirmasi | Akun berhasil dihapus | Akun terhapus dari sistem | ✅ Pass |
| 6 | Hapus Akun | Akun Digunakan | Hapus akun yang memiliki transaksi | Sistem menolak atau memberi peringatan | Muncul peringatan akun sedang digunakan | ✅ Pass |

---

## 19. Tabel Pengujian Admin - Laporan Penjualan

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Laporan Penjualan | Lihat Laporan | Akses halaman /admin/reports | Laporan penjualan ditampilkan | Laporan penjualan tampil dengan tabel dan ringkasan | ✅ Pass |
| 2 | Laporan Penjualan | Filter Tanggal | Pilih rentang tanggal laporan | Laporan sesuai periode yang dipilih | Laporan terfilter sesuai tanggal yang dipilih | ✅ Pass |
| 3 | Export Laporan | Download PDF/Excel | Klik tombol export laporan | File laporan berhasil diunduh | File PDF/Excel berhasil di-download | ✅ Pass |

---

## 20. Tabel Pengujian Admin - Jurnal Umum

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Jurnal Umum | Lihat Jurnal | Akses halaman /admin/journal | Jurnal umum ditampilkan dengan debit/kredit | Tabel jurnal tampil dengan kolom debit dan kredit | ✅ Pass |
| 2 | Jurnal Umum | Filter Periode | Pilih rentang tanggal | Transaksi sesuai periode ditampilkan | Jurnal terfilter sesuai periode | ✅ Pass |
| 3 | Export Jurnal | Download | Klik tombol export jurnal | File jurnal berhasil diunduh | File jurnal PDF/Excel berhasil di-download | ✅ Pass |

---

## 21. Tabel Pengujian Admin - Buku Besar

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Buku Besar | Lihat Ledger | Akses halaman /admin/ledger | Buku besar per akun ditampilkan | Ledger tampil dengan saldo berjalan per akun | ✅ Pass |
| 2 | Buku Besar | Pilih Akun | Filter berdasarkan akun tertentu | Transaksi akun tersebut ditampilkan | Ledger terfilter sesuai akun yang dipilih | ✅ Pass |
| 3 | Buku Besar | Filter Periode | Pilih rentang tanggal | Transaksi sesuai periode ditampilkan | Data terfilter sesuai periode | ✅ Pass |
| 4 | Export Ledger | Download | Klik tombol export buku besar | File buku besar berhasil diunduh | File PDF/Excel berhasil di-download | ✅ Pass |

---

## 22. Tabel Pengujian Admin - Neraca Saldo

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Neraca Saldo | Lihat Trial Balance | Akses halaman /admin/trial-balance | Neraca saldo ditampilkan dengan saldo debit/kredit | Neraca saldo tampil dengan kolom debit dan kredit | ✅ Pass |
| 2 | Neraca Saldo | Validasi Balance | Periksa total debit = total kredit | Total debit dan kredit seimbang | Total debit = total kredit (balance) | ✅ Pass |
| 3 | Neraca Saldo | Filter Periode | Pilih periode tertentu | Neraca saldo sesuai periode ditampilkan | Neraca terfilter sesuai periode yang dipilih | ✅ Pass |
| 4 | Export Neraca | Download | Klik tombol export neraca saldo | File neraca saldo berhasil diunduh | File PDF/Excel berhasil di-download | ✅ Pass |

---

## 23. Tabel Pengujian Admin - Laporan Laba Rugi

| No | Fitur | Uji | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
|----|-------|-----|---------|----------------------|--------------|--------|
| 1 | Laba Rugi | Lihat Laporan | Akses halaman /admin/income-statement | Laporan laba rugi ditampilkan | Laporan tampil dengan pendapatan dan beban | ✅ Pass |
| 2 | Laba Rugi | Filter Periode | Pilih rentang tanggal laporan | Laporan sesuai periode ditampilkan | Laporan terfilter sesuai periode | ✅ Pass |
| 3 | Laba Rugi | Perhitungan | Periksa pendapatan - beban = laba/rugi | Perhitungan laba rugi benar | Perhitungan laba bersih sesuai (pendapatan - beban) | ✅ Pass |
| 4 | Export Laba Rugi | Download | Klik tombol export laporan | File laporan laba rugi berhasil diunduh | File PDF/Excel berhasil di-download | ✅ Pass |

---

## Ringkasan Hasil Pengujian

| Modul | Jumlah Test Case | ✅ Pass | ❌ Fail |
|-------|------------------|---------|---------|
| Login | 7 | 7 | 0 |
| Register | 7 | 7 | 0 |
| Verifikasi Email | 4 | 4 | 0 |
| Lupa Password | 5 | 5 | 0 |
| Customer - Dashboard | 3 | 3 | 0 |
| Customer - Menu | 5 | 5 | 0 |
| Customer - Pemesanan | 9 | 9 | 0 |
| Customer - Pembayaran | 6 | 6 | 0 |
| Customer - Riwayat Pesanan | 6 | 6 | 0 |
| Customer - Chat | 5 | 5 | 0 |
| Admin - Dashboard | 4 | 4 | 0 |
| Admin - Kelola Menu | 8 | 8 | 0 |
| Admin - Kelola Kategori | 7 | 7 | 0 |
| Admin - Kelola Pesanan | 8 | 8 | 0 |
| Admin - Kelola Chat | 6 | 6 | 0 |
| Admin - Kelola Pembelian | 5 | 5 | 0 |
| Admin - Kelola Pengeluaran | 4 | 4 | 0 |
| Admin - Chart of Account | 6 | 6 | 0 |
| Admin - Laporan Penjualan | 3 | 3 | 0 |
| Admin - Jurnal Umum | 3 | 3 | 0 |
| Admin - Buku Besar | 4 | 4 | 0 |
| Admin - Neraca Saldo | 4 | 4 | 0 |
| Admin - Laporan Laba Rugi | 4 | 4 | 0 |
| **TOTAL** | **123** | **123** | **0** |

### Status Keseluruhan: ✅ LULUS (100% Pass Rate)

---

*Dokumentasi ini dibuat pada: 1 Januari 2026*  
*Sistem: Pempek N'Kitchen - Sistem Pemesanan Online*  
*Tester: Tim QA*
