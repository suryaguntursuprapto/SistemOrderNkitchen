# UML Diagrams - Sistem Order Pempek N'Kitchen

Dokumentasi ini berisi diagram-diagram UML untuk Sistem Order Pempek N'Kitchen yang dibuat menggunakan PlantUML.

---

## Daftar Diagram

### 📊 Diagram Struktural

| No | Diagram | File | Deskripsi |
|----|---------|------|-----------|
| 1 | Use Case Diagram | [use_case.puml](./use_case.puml) | Interaksi aktor dengan sistem |
| 2 | Class Diagram | [class_diagram.puml](./class_diagram.puml) | Struktur model dan relasinya |
| 3 | ERD | [erd.puml](./erd.puml) | Entity Relationship Diagram |

---

### 🔐 Diagram Autentikasi

| No | Diagram | File | Deskripsi |
|----|---------|------|-----------|
| 1 | Login | [activity_login.puml](./activity_login.puml) | Proses login (Email & Google OAuth) |
| 2 | Register | [activity_register.puml](./activity_register.puml) | Proses registrasi & verifikasi email |

---

### 👤 Diagram Customer

| No | Diagram | File | Deskripsi |
|----|---------|------|-----------|
| 1 | Dashboard | [activity_customer_dashboard.puml](./activity_customer_dashboard.puml) | Customer melihat dashboard |
| 2 | Melihat Menu | [activity_customer_menu.puml](./activity_customer_menu.puml) | Browsing & lihat detail menu |
| 3 | Pemesanan | [activity_customer_pemesanan.puml](./activity_customer_pemesanan.puml) | Proses checkout & pembayaran |
| 4 | Riwayat Pesanan | [activity_customer_riwayat_pesanan.puml](./activity_customer_riwayat_pesanan.puml) | Lihat riwayat & tracking |
| 5 | Chat | [activity_customer_chat.puml](./activity_customer_chat.puml) | Chat dengan admin |

---

### 👨‍💼 Diagram Admin

| No | Diagram | File | Deskripsi |
|----|---------|------|-----------|
| 1 | Dashboard | [activity_admin_dashboard.puml](./activity_admin_dashboard.puml) | Admin melihat dashboard |
| 2 | Kelola Menu | [activity_admin_menu.puml](./activity_admin_menu.puml) | CRUD menu |
| 3 | Kelola Kategori | [activity_admin_kategori.puml](./activity_admin_kategori.puml) | CRUD kategori |
| 4 | Kelola Pesanan | [activity_admin_pesanan.puml](./activity_admin_pesanan.puml) | Manajemen pesanan |
| 5 | Kelola Chat | [activity_admin_chat.puml](./activity_admin_chat.puml) | Balas pesan customer |

---

### 📈 Diagram Laporan Admin

| No | Diagram | File | Deskripsi |
|----|---------|------|-----------|
| 1 | Laporan Penjualan | [activity_admin_laporan_penjualan.puml](./activity_admin_laporan_penjualan.puml) | Laporan penjualan |
| 2 | Jurnal Umum | [activity_admin_jurnal_umum.puml](./activity_admin_jurnal_umum.puml) | General Journal |
| 3 | Buku Besar | [activity_admin_buku_besar.puml](./activity_admin_buku_besar.puml) | General Ledger |
| 4 | Neraca Saldo | [activity_admin_neraca_saldo.puml](./activity_admin_neraca_saldo.puml) | Trial Balance |
| 5 | Laporan Laba Rugi | [activity_admin_laba_rugi.puml](./activity_admin_laba_rugi.puml) | Income Statement |

---

### 📦 Diagram Lainnya

| No | Diagram | File | Deskripsi |
|----|---------|------|-----------|
| 1 | Proses Order Lengkap | [activity_order.puml](./activity_order.puml) | Flow order end-to-end |
| 2 | Proses Pembayaran | [activity_payment.puml](./activity_payment.puml) | Transfer & Midtrans |
| 3 | Admin Order | [activity_admin_order.puml](./activity_admin_order.puml) | Admin kelola order |

---

## Cara Generate Diagram

### 🌐 Menggunakan PlantUML Online
1. Kunjungi https://www.plantuml.com/plantuml/uml
2. Copy-paste isi file `.puml`
3. Klik submit untuk generate gambar

### 💻 Menggunakan VS Code
1. Install extension "PlantUML"
2. Buka file `.puml`
3. Tekan `Alt+D` untuk preview

### 🖥️ Menggunakan Command Line (macOS)
```bash
# Install plantuml via homebrew
brew install plantuml

# Generate semua diagram ke PNG
plantuml docs/diagrams/*.puml

# Generate ke SVG (kualitas lebih baik)
plantuml -tsvg docs/diagrams/*.puml

# Generate ke PDF
plantuml -tpdf docs/diagrams/*.puml
```

---

## Struktur Folder

```
docs/diagrams/
├── README.md                              # Dokumentasi ini
│
├── # STRUKTURAL
├── use_case.puml                          # Use Case Diagram
├── class_diagram.puml                     # Class Diagram
├── erd.puml                               # ERD
│
├── # AUTENTIKASI
├── activity_login.puml                    # Login
├── activity_register.puml                 # Register
│
├── # CUSTOMER
├── activity_customer_dashboard.puml       # Dashboard Customer
├── activity_customer_menu.puml            # Melihat Menu
├── activity_customer_pemesanan.puml       # Pemesanan/Checkout
├── activity_customer_riwayat_pesanan.puml # Riwayat Pesanan
├── activity_customer_chat.puml            # Chat
│
├── # ADMIN
├── activity_admin_dashboard.puml          # Dashboard Admin
├── activity_admin_menu.puml               # Kelola Menu
├── activity_admin_kategori.puml           # Kelola Kategori
├── activity_admin_pesanan.puml            # Kelola Pesanan
├── activity_admin_chat.puml               # Kelola Chat
│
├── # LAPORAN ADMIN
├── activity_admin_laporan_penjualan.puml  # Laporan Penjualan
├── activity_admin_jurnal_umum.puml        # Jurnal Umum
├── activity_admin_buku_besar.puml         # Buku Besar
├── activity_admin_neraca_saldo.puml       # Neraca Saldo
├── activity_admin_laba_rugi.puml          # Laba Rugi
│
├── # LAINNYA
├── activity_order.puml                    # Proses Order Lengkap
├── activity_payment.puml                  # Proses Pembayaran
└── activity_admin_order.puml              # Admin Order
```

---

## Legenda Warna

| Warna | Keterangan |
|-------|------------|
| 🟢 Hijau (#E8F5E9) | User & Authentication / Customer |
| 🔵 Biru Muda (#87CEEB) | Admin |
| 🔵 Biru (#E3F2FD) | Sistem |
| 🟠 Orange (#FFF3E0) | Menu Management / External Service |
| 🔵 Light Blue (#E1F5FE) | Order Management |
| 🟣 Ungu (#F3E5F5) | Payment / Biteship / Midtrans |
| 🟡 Kuning (#FFF8E1 / #FFF9C4) | Communication / Decision |
| 🔴 Pink (#FCE4EC) | Purchase Management |
| 🟢 Teal (#E0F2F1) | Accounting |

---

## Total Diagram: 21 Files
