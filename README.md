# 📦 Aplikasi Inventaris Sederhana (UKK/SMK)

Aplikasi manajemen inventaris barang berbasis web, dibangun menggunakan **PHP Native** tanpa *framework* dan database **MySQL**. Proyek ini dirancang untuk memenuhi kriteria Uji Kompetensi Kejuruan (UKK) atau tugas akhir sekolah menengah kejuruan.

---

## ✨ Fitur Utama (Core Features)

Aplikasi ini mencakup modul-modul penting berikut:

* **Otentikasi & Hak Akses (Authentication & Authorization):**
    * Login, Register, Logout.
    * Sistem Role-Based Access Control (RBAC): **Admin** dan **Pengguna**.
* **Master Data CRUD (Create, Read, Update, Delete):**
    * **Barang:** CRUD lengkap dengan pengecekan stok.
    * **Kategori:** CRUD lengkap untuk pengelompokan barang.
* **Modul Transaksi:**
    * **Peminjaman:** Mencatat transaksi peminjaman dengan mekanisme **keranjang** (Session-based) untuk multi-item.
    * **Pengembalian:** Memproses pengembalian barang dan otomatis memperbarui stok barang.
* **Informasi:**
    * **Dashboard Dinamis:** Menampilkan ringkasan KPI (Total Barang, Total Kategori, Peminjaman Aktif).

---

## ⚙️ Stack Teknologi

| Komponen | Deskripsi |
| :--- | :--- |
| **Backend** | PHP Native (murni) |
| **Database** | MySQL/MariaDB |
| **Styling** | Bootstrap 5 (Minimalis) |
| **Arsitektur** | Front Controller (Semua *request* melalui `public/index.php`) |

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah ini untuk menjalankan aplikasi di lingkungan lokal (seperti Laragon/XAMPP/MAMP):

### Prasyarat
* Web Server Lokal (PHP 7.4+ atau 8.x).
* MySQL/MariaDB.

### Langkah-langkah Setup

1.  **Clone Repository:**
    ```bash
    git clone [LINK_REPOSITORY_ANDA] ukk_inventaris
    cd ukk_inventaris
    ```
2.  **Konfigurasi Database:**
    * Buat database baru (misalnya, `ukk_inventaris`).
    * Lakukan *Import* skema database Anda (termasuk tabel `pengguna`, `barang`, `kategori`, `peminjaman`, dan `detail_peminjaman`).
3.  **Atur Koneksi:**
    * Buka file **`src/shared/config/database.php`**.
    * Ubah kredensial database sesuai dengan pengaturan lokal Anda.
    ```php
    // Contoh konfigurasi di database.php
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = ''; 
    $DB_NAME = 'ukk_inventaris'; 
    ```
4.  **Akses Aplikasi:**
    * Akses aplikasi melalui browser: `http://localhost/ukk_inventaris/public/` (sesuaikan dengan *path* Anda).

---

## 👥 Kontributor

* [Nama Anda] - Developer Utama
