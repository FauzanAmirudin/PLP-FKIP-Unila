# PLP FKIP UNILA - Pengenalan Lapangan Persekolahan

Aplikasi Manajemen Pengenalan Lapangan Persekolahan (PLP) untuk Fakultas Keguruan dan Ilmu Pendidikan (FKIP) Universitas Lampung. Sistem ini dirancang untuk mempermudah alur pendaftaran mahasiswa, penempatan lokasi, hingga pelaporan kegiatan secara digital dan terintegrasi.

---

## 🚀 Tech Stack

- **Backend**: PHP (GF Framework - Custom Lightweight MVC)
- **Database**: MySQL / MariaDB
- **Frontend**: 
  - HTML5 & CSS3 (Modern Responsive Design)
  - SCSS (Source styling)
  - Vanilla JavaScript
  - [Chartist.js](https://gionkunz.github.io/chartist-js/) (Visualisasi Data)
- **Build Tools**: Gulp (untuk kompilasi SCSS dan optimasi aset)
- **Security**: AES-128-CTR Encryption, Level-based Access Control (RBAC)

---

## 📂 Struktur Folder

```text
FKIP_PPL2/
├── application/          # Inti aplikasi (MVC)
│   ├── config/           # Konfigurasi database, site, dan framework
│   ├── controller/       # Logika alur aplikasi (Controllers)
│   ├── helper/           # Fungsi bantuan (URL, Form, Auth, dll.)
│   ├── model/            # Interaksi database (Models)
│   └── view/             # Template tampilan (Views)
│       ├── navigation.php# Navigasi utama
│       ├── sidebar.php   # Sidebar menu
│       └── page/         # Konten halaman spesifik
├── assets/               # File publik (CSS, JS, Images, Icons)
│   ├── css/              # Hasil kompilasi CSS (style.min.css)
│   ├── js/               # Script frontend
│   └── images/           # Aset gambar dan ikon
├── src/                  # Source code aset
│   └── scss/             # File sumber SCSS (Modular styles)
├── system/               # Inti Framework (GF Core)
├── uploads/              # Penyimpanan laporan & dokumen user
├── tmp/                  # Folder sementara
├── index.php             # Entry point aplikasi
└── .gitignore            # Konfigurasi Git
```

---

## 🛠️ Fitur Utama

1. **Dashboard Dinamis**: Ringkasan status pendaftaran dan kegiatan mahasiswa.
2. **Sistem Registrasi**: Form pendaftaran mahasiswa dengan validasi berkas.
3. **Validasi Admin**: Panel khusus untuk Admin/Operator untuk menyetujui atau menolak pendaftaran mahasiswa.
4. **Penempatan (Assignment)**: Fitur plotting mahasiswa ke lokasi sekolah dan pendampingan Dosen Pembimbing Lapangan (DPL).
5. **Manajemen Laporan**: Upload laporan mingguan oleh mahasiswa dan validasi oleh DPL/Admin.
6. **Panel Informasi**: Berita dan pengumuman terbaru yang dikelola melalui dashboard.
7. **Galeri Institusional**: Media showcase kegiatan PLP.
8. **Pengaturan Sistem**: Konfigurasi periode pendaftaran, tahun akademik, dan batas upload laporan.

---

## 👤 Peran Pengguna (User Roles)

- **Admin**: Akses penuh ke seluruh sistem, manajemen user, dan konfigurasi global.
- **Operator**: Manajemen data operasional mahasiswa, pendaftaran, dan laporan.
- **DPL (Dosen Pembimbing Lapangan)**: Memantau dan memvalidasi laporan mahasiswa bimbingan.
- **Monitor**: Akses baca (audit) untuk memantau progres kegiatan secara keseluruhan.
- **Mahasiswa**: Melakukan pendaftaran, melihat penempatan, dan mengirimkan laporan mingguan.

---

## ⚙️ Instalasi

### Persyaratan Sistem
- Web Server (Laragon direkomendasikan / XAMPP)
- PHP >= 7.4
- MySQL / MariaDB

### Langkah-langkah
1. **Clone Repository**:
   ```bash
   git clone https://github.com/FauzanAmirudin/PLP-FKIP-Unila.git
   ```
2. **Persiapan Database**:
   - Buat database baru (misal: `test_fkip_plt`).
   - Import file SQL `plt_administrative_record.sql` yang tersedia di root folder.
3. **Konfigurasi Database**:
   - Buka `application/config/db.php`.
   - Sesuaikan `server`, `username`, `password`, dan `database` dengan environment Anda.
4. **Konfigurasi Base URL**:
   - Buka `application/config/config.php`.
   - Ubah `$config['base_url']` sesuai dengan alamat akses lokal Anda (contoh: `http://localhost/FKIP_PPL2`).
5. **Akses Aplikasi**:
   - Buka browser dan arahkan ke alamat tersebut.

---

## 👨‍💻 Pengembangan (Development)

Untuk melakukan perubahan pada styling:
1. Pastikan Node.js terinstall.
2. Jalankan perintah kompilasi SCSS (jika menggunakan workflow Gulp):
   ```bash
   gulp watch
   ```
   *Catatan: Pastikan `gulpfile.js` dan dependencies di `package.json` sudah terinstall.*

---

## 🔒 Keamanan
Aplikasi ini menerapkan beberapa lapisan keamanan:
- **RBAC**: Pengecekan level akses pada setiap controller melalui `require_level()`.
- **Encryption**: Menggunakan `openssl_encrypt` untuk data sensitif.
- **Input Sanitization**: Seluruh input user melalui proses pembersihan untuk mencegah XSS dan SQL Injection (menggunakan library `sql` bawaan framework).

---

© 2026 - FKIP Universitas Lampung
