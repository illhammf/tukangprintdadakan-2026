<h1 align="center">
PROJECT AKHIR PEMROGRAMAN WEB (CR002) <br>
TUKANG PRINT DADAKAN
</h1>

---

<p align="center">

<img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
<img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" />
<img src="https://img.shields.io/badge/Filament-Admin%20Panel-F59E0B?style=for-the-badge" />
<img src="https://img.shields.io/badge/Docker-Container-2496ED?style=for-the-badge&logo=docker&logoColor=white" />
<img src="https://img.shields.io/badge/MariaDB-Database-003545?style=for-the-badge&logo=mariadb&logoColor=white" />
<img src="https://img.shields.io/badge/Midtrans-Payment-1F73B7?style=for-the-badge&logo=midtranspayment&logoColor=white">
<img src="https://img.shields.io/github/actions/workflow/status/illhammf/tukangprintdadakan-2026/laravel.yml?style=for-the-badge&label=Laravel%20CI&logo=githubactions&logoColor=white" />

</p>

<p align="center">

<img src="https://img.shields.io/github/last-commit/illhammf/tukangprintdadakan-2026?style=for-the-badge" />
<img src="https://img.shields.io/github/repo-size/illhammf/tukangprintdadakan-2026?style=for-the-badge" />
<img src="https://komarev.com/ghpvc/?username=illhammf&repo=tukangprintdadakan-2026&style=for-the-badge&color=blue" />

</p>

---

## Tentang Project
**Tukang Print Dadakan** adalah sistem pemesanan dan pengelolaan layanan cetak mahasiswa berbasis web. Sistem ini dikembangkan untuk membantu pelanggan melakukan pemesanan layanan cetak secara lebih terstruktur serta membantu pemilik usaha mengelola pesanan, file, pembayaran, antrean pengerjaan, dan laporan operasional dalam satu aplikasi.

Project ini dikembangkan sebagai **tugas akhir mata kuliah Pemrograman Web** pada Program Studi Teknik Informatika, Fakultas Ilmu Komputer, Universitas Esa Unggul.

---

<p align="center>

## Informasi Project

| Informasi | Keterangan |
|---|---|
| Nama project | Tukang Print Dadakan |
| Jenis project | Capstone Project Pemrograman Web |
| Pengembang | Ilham Firmansyah |
| NIM | 20240801102 |
| Program Studi | Teknik Informatika |
| Fakultas | Fakultas Ilmu Komputer |
| Universitas | Universitas Esa Unggul |
| Semester | Genap 2025–2026 |
| Dosen Pembimbing | Jefry Sunupurwa Asri, S.Kom., M.Kom. |
| Jalur Capstone | Web |
| Repository | [github.com/ilhammf/tukangprintdadakan-2026](https://github.com/ilhammf/tukangprintdadakan-2026) |
| Website | [print.ilhamfirmansyah.store](https://print.ilhamfirmansyah.store) |

</p>
---

## Latar Belakang

Tukang Print Dadakan merupakan usaha jasa cetak yang melayani kebutuhan mahasiswa, seperti pencetakan tugas, laporan, proposal, makalah, skripsi, dan dokumen akademik lainnya.

Sebelum sistem dikembangkan, proses pemesanan dilakukan melalui WhatsApp. Pelanggan mengirimkan file, menjelaskan spesifikasi cetak, menanyakan harga, melakukan pembayaran, dan menanyakan perkembangan pesanan melalui percakapan langsung dengan admin.

Proses tersebut menjadi kurang efektif ketika jumlah pesanan meningkat karena data pelanggan, file, detail pesanan, pembayaran, dan status pengerjaan tersebar dalam banyak percakapan. Admin juga mengalami kesulitan dalam mencari file lama, mengelola antrean, menghitung biaya, dan memberikan informasi status kepada pelanggan.

Tukang Print Dadakan dikembangkan sebagai solusi berbasis web untuk memusatkan seluruh proses pemesanan dan pengelolaan layanan cetak dalam satu sistem yang terstruktur.

---

## Permasalahan yang Diselesaikan

Sistem ini dikembangkan untuk mengatasi beberapa permasalahan berikut:

- Data pesanan dan file pelanggan tersebar dalam percakapan WhatsApp.
- Admin kesulitan mencari kembali data dan file pesanan lama.
- Pelanggan harus menghubungi admin untuk mengetahui harga dan status pesanan.
- Perhitungan biaya berisiko mengalami kesalahan pencatatan.
- Konfirmasi pembayaran dilakukan secara manual.
- Status pembayaran dan pengerjaan belum tercatat secara terpusat.
- Pengelolaan antrean kurang efektif ketika jumlah pesanan meningkat.
- Pemilik usaha kesulitan memantau transaksi dan pendapatan.
- Informasi layanan dan harga belum tersedia dalam satu media terpusat.

---

## Tujuan Pengembangan

Tujuan pengembangan sistem Tukang Print Dadakan adalah:

1. Menyediakan sistem pemesanan layanan cetak yang dapat diakses secara online.
2. Memusatkan data pelanggan, layanan, pesanan, file, pembayaran, dan riwayat status.
3. Mempermudah pelanggan dalam membuat dan memantau pesanan.
4. Menyediakan perhitungan estimasi biaya secara otomatis.
5. Mendukung pembayaran online melalui Midtrans.
6. Membantu admin mengelola pesanan dan antrean pengerjaan.
7. Membantu pemilik usaha memantau transaksi dan pendapatan.
8. Mengurangi ketergantungan pada proses pemesanan melalui WhatsApp.
9. Meningkatkan ketertiban, efisiensi, dan kualitas pelayanan usaha.

---

## Pengguna Sistem

### Pelanggan

Pelanggan merupakan pengguna yang menggunakan sistem untuk melihat layanan, membuat pesanan, mengunggah file, melakukan pembayaran, dan memantau status pengerjaan.

### Admin atau Pemilik Usaha

Admin bertanggung jawab dalam mengelola pelanggan, layanan, pesanan, file, pembayaran, antrean pengerjaan, informasi website, dan laporan operasional.

### Midtrans

Midtrans merupakan layanan eksternal yang digunakan untuk memproses pembayaran dan mengirimkan pembaruan status transaksi kepada sistem.

---

## Fitur Website Publik

Website publik dapat diakses tanpa melakukan login.

Fitur yang tersedia meliputi:

- Halaman beranda.
- Halaman tentang kami.
- Daftar layanan cetak.
- Detail layanan dan harga.
- Informasi kontak.
- Informasi WhatsApp.
- Informasi email.
- Informasi alamat dan jam operasional.
- Formulir pertanyaan pelanggan.
- Akses menuju halaman login dan registrasi.

---

## Fitur Pelanggan

Pelanggan dapat menggunakan fitur berikut:

- Registrasi akun.
- Login dan logout.
- Mengelola profil.
- Mengubah kata sandi.
- Melihat daftar layanan.
- Melihat detail dan harga layanan.
- Membuat pesanan layanan cetak.
- Mengunggah file yang akan dicetak.
- Memilih jenis cetak.
- Memilih ukuran kertas.
- Menentukan jumlah halaman.
- Menentukan jumlah salinan.
- Memilih jilid atau laminating.
- Menambahkan catatan pesanan.
- Menentukan jadwal pengambilan.
- Menentukan lokasi pengambilan.
- Melihat estimasi biaya.
- Melakukan pembayaran melalui Midtrans.
- Melihat status pembayaran.
- Melihat status pengerjaan.
- Melihat detail dan riwayat pesanan.
- Membatalkan pesanan sesuai ketentuan.

---

## Fitur Admin

Admin atau pemilik usaha dapat menggunakan fitur berikut:

- Melihat dashboard operasional.
- Mengelola data pelanggan.
- Mengelola kategori layanan.
- Mengelola data layanan.
- Mengatur harga layanan.
- Mengaktifkan atau menonaktifkan layanan.
- Melihat pesanan pelanggan.
- Melihat dan mengunduh file pesanan.
- Memverifikasi pesanan dan file.
- Mengelola antrean pengerjaan.
- Memperbarui status pesanan.
- Melihat riwayat perubahan status.
- Melihat status dan data pembayaran.
- Mengelola pesan masuk.
- Mengelola informasi website.
- Mengelola hari libur dan jadwal operasional.
- Mengatur biaya tambahan.
- Mengelola role dan permission.
- Melihat riwayat transaksi.
- Melihat ringkasan pendapatan.
- Melihat layanan yang sering digunakan.
- Memantau aktivitas penting dalam sistem.

---

## Status Pesanan

Sistem menggunakan status pesanan berikut:

| Status | Keterangan |
|---|---|
| Menunggu Verifikasi | Pesanan telah dibuat dan menunggu pemeriksaan admin |
| Diproses | Pesanan telah diverifikasi dan sedang dikerjakan |
| Siap Diambil | Pesanan selesai dicetak dan siap diambil |
| Selesai | Pesanan telah diserahkan kepada pelanggan |
| Dibatalkan | Pesanan dibatalkan sebelum diproses |

Alur utama status pesanan:

```text
Menunggu Verifikasi
├── Diproses
│   └── Siap Diambil
│       └── Selesai
└── Dibatalkan
```

Pelanggan hanya dapat membatalkan pesanan selama pesanan masih berstatus **Menunggu Verifikasi**.

---

## Status Pembayaran

Sistem menggunakan status pembayaran berikut:

| Status | Keterangan |
|---|---|
| Belum Bayar | Pelanggan belum menyelesaikan pembayaran |
| Menunggu Verifikasi | Pembayaran masih dalam proses atau menunggu konfirmasi |
| Lunas | Pembayaran berhasil diterima |
| Ditolak | Pembayaran ditolak, dibatalkan, kedaluwarsa, atau gagal |

Status transaksi dari Midtrans seperti `pending`, `settlement`, `capture`, `deny`, `cancel`, `expire`, dan `failure` dipetakan ke status pembayaran yang digunakan oleh aplikasi.

---

## Format File Pesanan

Format file yang dapat diunggah oleh pelanggan meliputi:

- PDF: `.pdf`
- Microsoft Word: `.doc`, `.docx`
- Microsoft PowerPoint: `.ppt`, `.pptx`
- Gambar: `.jpg`, `.jpeg`, `.png`

Ketentuan upload:

| Ketentuan | Batas |
|---|---:|
| Jumlah file setiap pesanan | Maksimal 5 file |
| Ukuran setiap file | Maksimal 20 MB |
| Total seluruh file | Maksimal 50 MB |

File yang tidak sesuai dengan format atau ukuran yang ditentukan akan ditolak oleh sistem.

---

## Estimasi Biaya

Estimasi biaya dihitung berdasarkan beberapa komponen:

- Harga dasar layanan.
- Jumlah halaman.
- Jumlah salinan.
- Jenis cetak.
- Ukuran kertas.
- Biaya jilid.
- Biaya laminating.
- Biaya prioritas.
- Biaya pengiriman atau biaya tambahan lainnya.

Perhitungan dasar:

```text
Subtotal cetak =
harga layanan × jumlah halaman × jumlah salinan
```

Total pesanan:

```text
Total pesanan =
subtotal cetak
+ biaya jilid
+ biaya laminating
+ biaya prioritas
+ biaya pengiriman
+ biaya tambahan lainnya
```

Estimasi biaya merupakan perkiraan awal dan dapat berubah setelah admin melakukan verifikasi terhadap file dan rincian pesanan.

---

## Alur Proses Bisnis

Alur utama sistem Tukang Print Dadakan adalah sebagai berikut:

1. Pelanggan melakukan registrasi atau login.
2. Pelanggan melihat dan memilih layanan.
3. Pelanggan mengisi detail kebutuhan cetak.
4. Pelanggan mengunggah file pesanan.
5. Sistem menghitung estimasi biaya.
6. Sistem membuat kode pesanan secara otomatis.
7. Pesanan disimpan dengan status Menunggu Verifikasi.
8. Pelanggan melakukan pembayaran melalui Midtrans.
9. Midtrans memproses dan mengirimkan status pembayaran.
10. Sistem memperbarui status pembayaran.
11. Admin memeriksa detail dan file pesanan.
12. Admin mengatur antrean pengerjaan.
13. Admin mengubah status pesanan menjadi Diproses.
14. Setelah selesai dicetak, status diubah menjadi Siap Diambil.
15. Pesanan diubah menjadi Selesai setelah diterima pelanggan.
16. Pelanggan dapat memantau seluruh proses melalui website.

---

## Pengembangan dengan Metode Agile

Sistem dikembangkan menggunakan metode Agile agar proses pengembangan dapat dilakukan secara bertahap dan menyesuaikan kebutuhan pengguna.

Tahapan pengembangan meliputi:

### Plan

- Mengidentifikasi permasalahan pemesanan manual.
- Menyusun kebutuhan bisnis dan kebutuhan produk.
- Menentukan user story, product backlog, dan prioritas fitur.

### Design

- Merancang alur proses bisnis.
- Merancang basis data dan hubungan antarentitas.
- Membuat sitemap, diagram, wireframe, dan rancangan antarmuka.

### Develop

- Mengembangkan fitur website publik.
- Mengembangkan fitur pelanggan dan admin.
- Mengintegrasikan database dan payment gateway.

### Test

- Melakukan pengujian fungsional.
- Menguji validasi dan hak akses.
- Melakukan User Acceptance Testing.

### Deploy

- Menjalankan aplikasi pada server produksi.
- Menghubungkan aplikasi dengan domain.
- Mengaktifkan akses melalui HTTPS.

### Review

- Mengevaluasi hasil implementasi.
- Memperbaiki bug dan ketidaksesuaian.
- Melakukan pengujian ulang.

### Launch

- Memastikan fitur utama dapat digunakan.
- Membuka akses sistem kepada pengguna.
- Melakukan pemantauan awal sistem.

---

## Iterasi Pengembangan

Pengembangan sistem dibagi menjadi beberapa iterasi.

### Iterasi 1 — Autentikasi dan Informasi Layanan

- Registrasi.
- Login dan logout.
- Daftar layanan.
- Detail layanan.

### Iterasi 2 — Pemesanan Layanan

- Pembuatan pesanan.
- Upload file.
- Estimasi biaya.
- Status pesanan.
- Riwayat pesanan.

### Iterasi 3 — Manajemen Pesanan

- Verifikasi pesanan.
- Verifikasi file.
- Pengelolaan antrean.
- Pembaruan status pesanan.

### Iterasi 4 — Manajemen Data dan Komunikasi

- Manajemen kategori.
- Manajemen layanan.
- Pesan masuk.
- Pengaturan website.

### Iterasi 5 — Dashboard dan Pelaporan

- Dashboard monitoring.
- Riwayat transaksi.
- Ringkasan pendapatan.
- Statistik layanan.

---

## Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Backend | Laravel 12 |
| Bahasa pemrograman | PHP dan JavaScript |
| Frontend | Blade, HTML, CSS, JavaScript |
| Styling | Tailwind CSS |
| Admin panel | Filament |
| Database | MariaDB |
| Payment gateway | Midtrans |
| Penyimpanan file | Laravel Storage |
| Web server | Nginx |
| Reverse proxy | Nginx Proxy Manager |
| Containerization | Docker |
| Version control | Git dan GitHub |
| Server | VPS Ubuntu |
| Development environment | WSL Ubuntu dan Visual Studio Code |
| Keamanan akses | HTTPS |

---

## Data Utama Sistem

Sistem mengelola beberapa kelompok data utama:

- Data pengguna.
- Data role dan permission.
- Data kategori layanan.
- Data layanan.
- Data pesanan.
- Data detail pesanan.
- Data file pelanggan.
- Data pembayaran.
- Data pengiriman atau pengambilan.
- Data riwayat status.
- Data pesan masuk.
- Data pengaturan website.
- Data pengaturan booking.
- Data hari libur.
- Data aktivitas sistem.

---

## Ruang Lingkup

Ruang lingkup sistem mencakup:

- Pemesanan layanan cetak melalui website.
- Pengunggahan file pelanggan.
- Perhitungan estimasi biaya.
- Pembayaran online.
- Monitoring status pembayaran.
- Monitoring status pengerjaan.
- Pengelolaan layanan oleh admin.
- Pengelolaan pesanan dan file.
- Pengelolaan antrean.
- Dashboard dan laporan operasional.
- Pengelolaan informasi website.
- Pembatasan akses berdasarkan role dan permission.

---

## Batasan Sistem

Sistem belum mencakup:

- Aplikasi mobile Android atau iOS.
- Integrasi printer secara otomatis.
- Pelacakan kurir secara real-time.
- Integrasi jasa pengiriman pihak ketiga secara otomatis.
- Refund Midtrans secara otomatis.
- Pembayaran berlangganan atau berulang.
- Sistem akuntansi dan pembukuan lengkap.
- Sistem multi-cabang.
- Otomatisasi proses pencetakan.

Proses pemeriksaan file dan pencetakan tetap dilakukan secara manual oleh admin.

---

## Kriteria Keberhasilan

Sistem dinyatakan memenuhi kebutuhan apabila:

- Pelanggan dapat melakukan registrasi dan login.
- Pelanggan dapat melihat daftar dan detail layanan.
- Pelanggan dapat membuat pesanan.
- File pesanan dapat diunggah dan diakses admin.
- Kode pesanan terbentuk secara otomatis.
- Estimasi biaya dapat ditampilkan.
- Pembayaran Midtrans dapat digunakan.
- Status pembayaran dapat diperbarui.
- Status pesanan dapat dipantau pelanggan.
- Admin dapat mengelola layanan dan pesanan.
- Admin dapat memverifikasi file.
- Admin dapat memperbarui status pengerjaan.
- Data transaksi dan pendapatan dapat ditampilkan.
- Akses pelanggan dan admin dapat dibatasi.
- Sistem dapat diakses melalui domain production menggunakan HTTPS.

---

## Dokumentasi Project

Dokumentasi pengembangan project meliputi:

- Business Requirement Document.
- Product Requirement Document.
- User story.
- Product backlog.
- Sitemap.
- Use case diagram.
- Activity diagram.
- Entity Relationship Diagram.
- Wireframe dan mockup.
- Dokumentasi implementasi.
- Pengujian black-box.
- User Acceptance Testing.
- Laporan Capstone Project.

---

## Website

Website Tukang Print Dadakan dapat diakses melalui:

**[https://print.ilhamfirmansyah.store](https://print.ilhamfirmansyah.store)**

---

## Repository

Source code dan dokumentasi project tersedia melalui:

**[https://github.com/ilhammf/tukangprintdadakan-2026](https://github.com/ilhammf/tukangprintdadakan-2026)**

---

## Pengembang

**Ilham Firmansyah**  
NIM: 20240801102  
Program Studi Teknik Informatika  
Fakultas Ilmu Komputer  
Universitas Esa Unggul

GitHub: [@ilhammf](https://github.com/ilhammf)

---

## Catatan Keamanan

Repository tidak boleh memuat:

- Password database.
- File `.env`.
- Midtrans Server Key.
- Token GitHub.
- Credential VPS.
- Data pribadi pelanggan.
- File pesanan pelanggan.
- Informasi transaksi yang bersifat rahasia.

Data yang digunakan dalam dokumentasi publik harus menggunakan data dummy atau telah disamarkan.

---

## Disclaimer

Project ini dikembangkan untuk memenuhi tugas akhir mata kuliah **Pemrograman Web** pada Program Studi Teknik Informatika, Universitas Esa Unggul.

Sistem Tukang Print Dadakan dikembangkan sebagai implementasi akademik dari proses analisis kebutuhan, perancangan sistem, pengembangan aplikasi web, integrasi basis data, pengujian, dan deployment.

---

## Lisensi

Repository ini digunakan untuk kebutuhan akademik. Hak penggunaan dan pengembangan source code berada pada pengembang.