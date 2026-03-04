
# 📖 **My Novel Management System**

✨ **My Novel Management System** adalah sebuahaplikasi web yang dirancang khusus untuk mengelola seluruh ekosistem karya tulis pribadimu.
Aplikasi ini memungkinkan penulis untuk mengarsipkan novel, mengelola draf per bab, memantau statistik pembaca (view), hingga menghasilkan laporan perkembangan tulisan secara terpusat melalui admin panel yang intuitif.

Proyek ini dibangun dengan **Laravel 11 (API)** & **Vue 3 + Vite (SPA frontend)**.

---

## 🔗 Demo
📘 _(Opsional, jika sudah deploy)_  
> [Demo Online](https://capitalexped.local)

---

## 🚀 Fitur

✅ Manajemen Novel (CRUD): Tambah, edit, dan hapus judul novel serta metadata (genre, sinopsis, cover).  
✅ Chapter Management: Kelola isi bab dengan editor yang nyaman.  
✅ Multi-role Admin Panel: Akses kontrol untuk Author, Editor, atau Guest.  
✅ Statistik & View: Pantau jumlah pembaca dan popularitas setiap karya.  
✅ Laporan Menulis: Laporan perkembangan jumlah kata dan publikasi per bulan.
✅ Dokumentasi Karakter/Worldbuilding: Lampiran untuk referensi dunia novel.
✅ Status Publikasi: Kelola status draf, review, hingga dipublikasikan.
✅ Notifikasi & Tracking
✅ SPA (Single Page Application): Navigasi cepat tanpa reload halaman.

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

## 🛰️ API Documentation

Semua request API harus menyertakan header `Accept: application/json`. Untuk endpoint yang terproteksi, tambahkan header `Authorization: Bearer <your_token>`.

### 🔐 Authentication
| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `POST` | `/api/login` | Mendapatkan JWT Token |
| `POST` | `/api/logout` | Revoke token saat ini |
| `GET` | `/api/me` | Mengambil data user yang login |

### 📚 Novels
| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/api/novels` | List semua novel & statistik view |
| `POST` | `/api/novels` | Membuat novel baru (judul, genre, cover) |
| `GET` | `/api/novels/{id}` | Detail novel beserta daftar chapter |
| `PUT` | `/api/novels/{id}` | Update metadata novel |
| `DELETE` | `/api/novels/{id}` | Menghapus novel dan filenya |

### 📄 Chapters
| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `POST` | `/api/novels/{id}/chapters` | Tambah bab baru ke novel tertentu |
| `GET` | `/api/chapters/{id}` | Baca isi draf/konten bab |
| `PUT` | `/api/chapters/{id}` | Update isi tulisan/bab |
| `PATCH` | `/api/chapters/{id}/publish` | Ubah status bab (Draft -> Published) |

### 📊 Analytics & Reports
| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/api/stats/summary` | Total views, total words, & active novels |
| `GET` | `/api/reports/monthly` | Laporan produktivitas bulanan |

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
# Clone repository
git clone https://github.com/nivecreative/my-novel.git
cd my-novel

# Install dependencies
composer install

# Environment setup
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan storage:link
```

Buat database baru:
```sql
CREATE DATABASE my_novel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
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
- Email: `admin@mynovel.local`
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
git checkout -b feature/FiturBaru
```
3️⃣ Commit perubahan:
```bash
git commit -m "Menambah fitur X"
```
4️⃣ Push ke branch:
```bash
git push origin feature/FiturBaru
```
5️⃣ Buat Pull Request

---

## 📝 Lisensi

MIT License © 2025 [nivecreative](https://github.com/nivecreative)

---

## 📬 Kontak

📧 Email: [mohamad.pais30@gmail.com](mailto:mohamad.pais30@gmail.com)  
🌐 Website: [nivecreative.local](http://localhost)

---

## 🌟 Terima kasih sudah menggunakan & mendukung **Throne of Fractured Fates**!  
_Keep writing. Your world is waiting._ ✨
