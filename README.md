
---

# 🚀 Panduan Menjalankan Project Laravel

Ikuti langkah-langkah di bawah ini untuk menjalankan project Laravel secara lokal di komputer kamu:

## ✅ Prasyarat

Pastikan kamu sudah menginstal:

* [XAMPP](https://www.apachefriends.org/)
* [Composer](https://getcomposer.org/)
* PHP minimal versi 8.1
* Git (opsional)

---

## 🛠️ Langkah-Langkah Setup

### 1. Clone Repository (jika belum)

```bash
git clone https://github.com/arifhida1647/fasilitas-kampus.git
cd nama-project
```

### 2. Install Dependency dengan Composer

```bash
composer install
```

### 3. Buat Database di phpMyAdmin

Buka `http://localhost/phpmyadmin` lalu buat database baru dengan nama:

```
db_fasilitas
```

### 4. Salin dan Atur File `.env`

Jika belum ada file `.env`, salin dari `.env.example`:

```bash
cp .env.example .env
```

Kemudian ubah konfigurasi database di file `.env` menjadi seperti ini:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_fasilitas
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan**: Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` jika kamu mengatur password untuk MySQL kamu.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 7. Jalankan Seeder (opsional, jika tersedia)

```bash
php artisan db:seed
```

### 8. Jalankan Server Laravel

```bash
php artisan serve
```

Aplikasi sekarang bisa diakses di:

```
http://localhost:8000
```

### 9. Jalankan XAMPP (Jika Perlu)

Pastikan service **Apache** dan **MySQL** sudah aktif di XAMPP Control Panel.

---

## 📞 Troubleshooting

* Jika muncul error `SQLSTATE[HY000]`, pastikan database `db_fasilitas` sudah dibuat.
* Jika port `8000` sudah digunakan, kamu bisa jalankan:

  ```
  php artisan serve --port=8080
  ```

---

## 📄 Lisensi

Project ini menggunakan lisensi bebas (misalnya MIT, silakan sesuaikan jika perlu).

---

Kalau kamu ingin saya buatin file `README.md` siap pakai, tinggal bilang aja 😊
