# Artikel

![License](https://img.shields.io/github/license/balqisshaula/artikel)
![Stars](https://img.shields.io/github/stars/balqisshaula/artikel)
![Forks](https://img.shields.io/github/forks/balqisshaula/artikel)

*Artikel* adalah platform web sederhana berbasis PHP & MySQL untuk mengelola dan mempublikasikan artikel secara efisien. Cocok untuk blog pribadi, portal berita kecil, atau media informasi internal.

## 🚀 Fitur Utama

- 🔐 Autentikasi user (login/logout)
- ✍ CRUD artikel (buat, baca, edit, hapus)
- 🗂 Kategori artikel
- 🔎 Pencarian artikel
- 📈 Statistik sederhana
- 🖼 Upload gambar untuk artikel
- 📱 Responsif di berbagai perangkat

## 📸 Preview
<!--
Tambahkan screenshot aplikasi Anda di sini!
-->
<!-- ![Screenshot](https://user-images.githubusercontent.com/your-username/screenshot-path.png) -->

## 🛠 Teknologi yang Digunakan

- PHP (Native)
- MySQL/MariaDB
- HTML5, CSS3, Bootstrap
- JavaScript (jika ada fitur interaktif)
- Apache/Nginx

## ⚡ Instalasi & Setup

### 1. Clone repositori

bash
git clone https://github.com/balqisshaula/artikel.git
cd artikel


### 2. Buat database dan import file SQL

bash
# Masuk ke MySQL
mysql -u root -p

# Buat database baru
CREATE DATABASE db_time;

# Keluar dari MySQL
exit

# Import file SQL ke database
mysql -u root -p db_time < db_time.sql


### 3. Edit konfigurasi database

bash
# Buka file config dan sesuaikan user, password, dan nama database
nano config/database.php


### 4. Pastikan web server dan PHP sudah berjalan

bash
# Untuk Apache (Ubuntu/Debian)
sudo systemctl restart apache2

# Untuk Nginx (jika menggunakan Nginx)
sudo systemctl restart nginx


### 5. Akses aplikasi

Buka browser dan kunjungi:

http://localhost/artikel

atau sesuai domain/server Anda.

## 💡 Penggunaan

- Login menggunakan user yang sudah terdaftar.
- Buat, edit, dan kelola artikel sesuai kebutuhan.
- Kelola kategori dan lihat statistik.

## 🤝 Kontribusi

Kontribusi sangat terbuka! Silakan fork repositori ini, buat branch baru, dan ajukan pull request.  
Laporkan bug atau request fitur melalui [issues](https://github.com/balqisshaula/artikel/issues).

## 📄 Lisensi

Project ini menggunakan lisensi [MIT](LICENSE).

---

> Made with ❤ by [balqisshaula](https://github.com/balqisshaula)
