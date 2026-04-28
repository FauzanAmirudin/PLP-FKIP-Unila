# Laporan Peningkatan (Improvement Report) - Fitur Jadwal Dinamis

Laporan ini mendokumentasikan tiga (3) perbaikan spesifik yang dilakukan pada *branch* `feature-jadwal-dinamis` untuk memenuhi standar keamanan (*Security*), kerapihan (*Cleanliness*), dan pengalaman pengguna (*UX*).

## 1. Validasi Tanggal di Form (Fungsionalitas & UX)

**Masalah:** 
Sebelumnya, Admin bisa tidak sengaja memasukkan Tanggal Berakhir (`WAKTUAKHIR`) yang terjadi sebelum Tanggal Mulai (`WAKTUAWAL`), yang mana akan mengacaukan logika sistem penjadwalan.

**Penyelesaian:**
- Menambahkan validasi JavaScript *Event Listener* pada saat *form submit* di `application/view/page/kelolajadwalform.php`.
- Jika `Tanggal Berakhir` < `Tanggal Mulai`, pengiriman form akan diblokir otomatis (`e.preventDefault()`) dan memunculkan *alert*: "Kesalahan: Tanggal Berakhir tidak boleh lebih awal dari Tanggal Mulai!".
- Fitur ini memberikan *feedback* instan kepada operator tanpa perlu membebani *server* dengan me-*reload* halaman.

## 2. Refaktorisasi Format Tanggal (Clean Code / DRY Principle)

**Masalah:** 
Fungsi `date("d M Y", strtotime($row['WAKTUAWAL']))` diulang-ulang di tiga file berbeda (`dashboard.php`, `jadwal.php`, dan `kelolajadwal.php`). Hal ini melanggar prinsip *Don't Repeat Yourself* (DRY) dan membuat kode sulit dikelola jika di masa depan kita ingin mengubah format tanggal menjadi Bahasa Indonesia (misal: "12 Agustus 2026").

**Penyelesaian:**
- Menambahkan *private method* `_format_dates()` langsung pada Model (`application/model/jadwal.php`).
- Sekarang, fungsi `get()`, `list()`, dan method baru `dashboard_list()` akan secara otomatis menyisipkan *key* array baru: `WAKTUAWAL_FORMATTED` dan `WAKTUAKHIR_FORMATTED`.
- File *View* (`dashboard.php`, `jadwal.php`, `kelolajadwal.php`) telah dibersihkan secara masif dan kini hanya perlu memanggil variabel tersebut (contoh: `echo $row['WAKTUAWAL_FORMATTED'];`), menjadikan kode *front-end* jauh lebih bersih dan terpusat.

## 3. Proteksi CSRF pada Fitur Hapus (Keamanan / Security)

**Masalah:** 
Proses penghapusan jadwal sebelumnya hanya menggunakan *parameter* statis pada URL `site/kelola_jadwal_delete&id=X`. Kondisi ini sangat rentan terhadap eksploitasi CSRF (*Cross-Site Request Forgery*). Serangan bisa terjadi jika Admin dalam kondisi *login*, lalu tidak sengaja mengeklik tautan manipulasi dari *email* atau *website* lain yang otomatis mengeksekusi penghapusan jadwal.

**Penyelesaian:**
- Mengimplementasikan generasi Token Anti-CSRF menggunakan `md5(uniqid(rand(), true))` pada metode `kelola_jadwal()` di `application/controller/site.php`.
- Token unik ini disimpan dalam sesi (`session_save`) dan diteruskan ke halaman *View* `kelolajadwal.php`.
- *Link* penghapusan di halaman Admin sekarang wajib membawa parameter tambahan: `&id=X&csrf=[TOKEN_RAHASIA]`.
- Pada metode *controller* `kelola_jadwal_delete()`, sistem sekarang memvalidasi `$_GET['csrf']` dengan sesi server. Jika token salah atau kosong, eksekusi akan digagalkan dan menampilkan pesan "Token keamanan tidak valid. Penghapusan dibatalkan".
- Jadwal sekarang **100% aman** dari manipulasi jarak jauh oleh pihak yang tidak bertanggung jawab.

---
**Status Pengecekan Akhir:**
- [x] Kode dieksekusi tanpa *error* (Bebas dari *Notice/Warning*).
- [x] Fungsi Hapus berjalan lancar saat *token valid*.
- [x] Tampilan Dashboard dan Jadwal Mahasiswa berhasil menarik data format tanggal dari Model.
- [x] *Alert JS* berjalan semestinya di Form Input.
