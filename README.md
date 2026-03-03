
# 📖 **Fixed Asset Management System**

✨ **Fixed Asset Management System** adalah sebuah aplikasi web untuk mengelola pengajuan, persetujuan, dan pelaporan belanja modal (capital expenditure) dalam organisasi.  
Pengguna dapat membuat, memantau, dan mengelola permintaan belanja modal, melacak status persetujuan, serta menghasilkan laporan terkait pengeluaran dan aset secara terpusat melalui admin panel yang terintegrasi.

Proyek ini dibangun dengan **Laravel 11 (API)** & **Vue 3 + Vite (SPA frontend)**.

---

## 🔗 Demo
📘 _(Opsional, jika sudah deploy)_  
> [Demo Online](https://capitalexped.local)

---

## 🚀 Fitur

✅ Formulir Pengajuan Capex  
✅ Approval Workflow  
✅ Multi-role admin panel (`admin`, `dll`)  
✅ Budget Checking  
✅ Realisasi vs Budget  
✅ Laporan & Analisis  
✅ Dokumentasi Lampiran
✅ Notifikasi & Tracking
✅ Dibangun sebagai Single Page Application (SPA)

---

## 🧰 Tech Stack

| Layer       | Teknologi            |
|-------------|----------------------|
| Backend     | Laravel 11           |
| Frontend    | Vue 3 + Vite         |
| Database    | MySQL / MariaDB      |
| Styling     | TailwindCSS          |
| Auth        | JWT Token            |
| API         | RESTful JSON API     |

---

## 🖥️ Instalasi

### 📋 Prasyarat

- PHP >= 8.2
- Composer
- Node.js >= 20.x
- NPM >= 9.x
- MySQL/MariaDB

---

### 🔧 Setup Backend

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan storage:link

npm install
```

Buat database baru:
```sql
CREATE DATABASE capital_expenditure CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Sesuaikan `.env`:
```
DB_DATABASE=capital-expenditure
DB_USERNAME=root
DB_PASSWORD=
```

Lalu migrasi & seed:
```bash
php artisan migrate --seed
php artisan serve
```

---

### 🌐 Setup Frontend

```bash
npm install
npm run dev
```

Frontend akan berjalan di: [http://localhost:5173](http://localhost:5173)

---

## 🔑 Default Admin

Saat pertama kali migrate & seed:
- Email: `admin@capitalexpend.local`
- Password: `password`

_Segera ubah password setelah login pertama._

---

## 📁 Struktur Project

```
throne-of-fractured-fates/
├── app/                # Laravel app
├── database/
│   ├── migrations/     # DB migrations
│   ├── seeders/        # DB seeders
├── frontend/           # Vue 3 SPA
│   ├── src/
│   ├── public/
│   ├── .env.example
├── public/
├── routes/
│   └── api.php
├── .env.example
├── README.md
```

---

## 📝 .env

Pastikan `.env` di-root sudah disesuaikan:
```
APP_NAME="Throne of Fractured Fates"
APP_URL=http://localhost
DB_DATABASE=fractured_fates
DB_USERNAME=root
DB_PASSWORD=
```

---

## 👨‍💻 Kontribusi

1️⃣ Fork repo ini  
2️⃣ Buat branch baru:
```bash
git checkout -b feature/namamu
```
3️⃣ Commit perubahan:
```bash
git commit -m "Add feature"
```
4️⃣ Push ke branch:
```bash
git push origin feature/namamu
```
5️⃣ Buat Pull Request

---

## 📝 Lisensi

MIT License © 2025 [nivecreative](https://github.com/nivecreative)

---

## 📬 Kontak

📧 Email: [hello@nivecreative.local](mailto:hello@nivecreative.local)  
🌐 Website: [nivecreative.local](http://localhost)

---

## 🌟 Terima kasih sudah menggunakan & mendukung **Throne of Fractured Fates**!  
_Keep writing. Your world is waiting._ ✨
