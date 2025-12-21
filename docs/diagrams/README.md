# UML Diagrams - Sistem Order Pempek N'Kitchen

Dokumentasi diagram UML untuk Sistem Order Pempek N'Kitchen menggunakan PlantUML.

---

## 📊 Diagram Struktural

| Diagram | File | Deskripsi |
|---------|------|-----------|
| Use Case | [use_case.puml](./use_case.puml) | Interaksi aktor dengan sistem |
| Class Diagram | [class_diagram.puml](./class_diagram.puml) | Struktur model dan relasi |
| ERD | [erd.puml](./erd.puml) | Entity Relationship Diagram |

---

## 🔐 Activity - Autentikasi

| Diagram | File | Swimlanes |
|---------|------|-----------|
| Login | [activity_login.puml](./activity_login.puml) | User ↔ Sistem |
| Register | [activity_register.puml](./activity_register.puml) | User ↔ Sistem |

---

## 👤 Activity - Customer

| Diagram | File | Swimlanes |
|---------|------|-----------|
| Dashboard | [activity_customer_dashboard.puml](./activity_customer_dashboard.puml) | Customer ↔ Sistem |
| Melihat Menu | [activity_customer_menu.puml](./activity_customer_menu.puml) | Customer ↔ Sistem |
| Pemesanan | [activity_customer_pemesanan.puml](./activity_customer_pemesanan.puml) | Customer ↔ Sistem ↔ Biteship |
| Riwayat Pesanan | [activity_customer_riwayat_pesanan.puml](./activity_customer_riwayat_pesanan.puml) | Customer ↔ Sistem |
| Chat | [activity_customer_chat.puml](./activity_customer_chat.puml) | Customer ↔ Sistem ↔ Admin |

---

## 👨‍💼 Activity - Admin

| Diagram | File | Swimlanes |
|---------|------|-----------|
| Dashboard | [activity_admin_dashboard.puml](./activity_admin_dashboard.puml) | Admin ↔ Sistem |
| Kelola Menu | [activity_admin_menu.puml](./activity_admin_menu.puml) | Admin ↔ Sistem |
| Kelola Kategori | [activity_admin_kategori.puml](./activity_admin_kategori.puml) | Admin ↔ Sistem |
| Kelola Pesanan | [activity_admin_pesanan.puml](./activity_admin_pesanan.puml) | Admin ↔ Sistem ↔ Biteship |
| Kelola Chat | [activity_admin_chat.puml](./activity_admin_chat.puml) | Admin ↔ Sistem ↔ Customer |

---

## 📈 Activity - Laporan Admin

| Diagram | File | Swimlanes |
|---------|------|-----------|
| Laporan Penjualan | [activity_admin_laporan_penjualan.puml](./activity_admin_laporan_penjualan.puml) | Admin ↔ Sistem |
| Jurnal Umum | [activity_admin_jurnal_umum.puml](./activity_admin_jurnal_umum.puml) | Admin ↔ Sistem |
| Buku Besar | [activity_admin_buku_besar.puml](./activity_admin_buku_besar.puml) | Admin ↔ Sistem |
| Neraca Saldo | [activity_admin_neraca_saldo.puml](./activity_admin_neraca_saldo.puml) | Admin ↔ Sistem |
| Laba Rugi | [activity_admin_laba_rugi.puml](./activity_admin_laba_rugi.puml) | Admin ↔ Sistem |

---

## 🔧 Cara Generate Diagram

### Online
Buka https://www.plantuml.com/plantuml/uml dan paste isi file `.puml`

### VS Code
1. Install extension "PlantUML"
2. Buka file `.puml`
3. Tekan `Alt+D` untuk preview

### Terminal (macOS)
```bash
# Install
brew install plantuml

# Generate semua ke PNG
plantuml docs/diagrams/*.puml

# Generate ke SVG
plantuml -tsvg docs/diagrams/*.puml
```

---

## 📁 Struktur Folder

```
docs/diagrams/
├── README.md
├── use_case.puml
├── class_diagram.puml
├── erd.puml
├── activity_login.puml
├── activity_register.puml
├── activity_customer_dashboard.puml
├── activity_customer_menu.puml
├── activity_customer_pemesanan.puml
├── activity_customer_riwayat_pesanan.puml
├── activity_customer_chat.puml
├── activity_admin_dashboard.puml
├── activity_admin_menu.puml
├── activity_admin_kategori.puml
├── activity_admin_pesanan.puml
├── activity_admin_chat.puml
├── activity_admin_laporan_penjualan.puml
├── activity_admin_jurnal_umum.puml
├── activity_admin_buku_besar.puml
├── activity_admin_neraca_saldo.puml
└── activity_admin_laba_rugi.puml
```

**Total: 21 File PlantUML**
