---

# 🚀 Panduan Menjalankan Project Laravel

Ikuti langkah-langkah di bawah ini untuk menjalankan project Laravel secara lokal di komputer kamu:

## ✅ Prasyarat

Pastikan kamu sudah menginstal:

* [Postgre - PGAdmin](https://www.pgadmin.org/download/)
* [Composer](https://getcomposer.org/)
* PHP minimal versi 8.1
* Git (opsional)

---

## 🛠️ Langkah-Langkah Setup

### 1. Clone Repository (jika belum)

```bash
git clone https://github.com/RizkiAlifianto23/SIFASMA.git
cd nama-project
```

### 2. Install Dependency dengan Composer

```bash
composer install
```

### 3. Buat Database di Postgre

Buatlah database di pgadmin dengan nama :

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
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_fasilitas
DB_USERNAME=postgres
DB_PASSWORD=root
```

> **Catatan**: Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` jika kamu mengatur password untuk Postgre kamu.

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

## 📄 Lisensi

Project ini menggunakan lisensi bebas (misalnya MIT, silakan sesuaikan jika perlu).

---

Kalau kamu ingin saya buatin file `README.md` siap pakai, tinggal bilang aja 😊
