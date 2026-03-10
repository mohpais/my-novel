
# 📖 **My Novel Management System**

✨ **My Novel Management System** adalah sebuah aplikasi web yang dirancang khusus untuk mengelola seluruh ekosistem karya tulis pribadimu.
Aplikasi ini memungkinkan penulis untuk mengarsipkan novel, mengelola draf per bab, memantau statistik pembaca (view), hingga menghasilkan laporan perkembangan tulisan secara terpusat melalui admin panel yang intuitif.

Proyek ini dibangun dengan **Laravel 11 (API)** & **Vue 3 + Vite (SPA frontend)**.

---

## 🔗 Demo
📘 _(Opsional, jika sudah deploy)_  
> [Demo Online](https://mynovel.local)

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

<!-- ## 🛰️ API Documentation

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

--- -->

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
git clone https://github.com/mohpais/my-novel.git
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
CREATE DATABASE my_novel_management_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Sesuaikan `.env`:
```
DB_DATABASE=my_novel_management_system
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
- Email: `mohamad.pais30@gmail.com`
- Password: `Admin123!`

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
APP_NAME="My Novel Management System"
APP_URL=http://localhost
DB_DATABASE=my_novel_management_system
DB_USERNAME=root
DB_PASSWORD=
```

## 🧠 AI Embedding & Automation (Vector Search)

Sistem ini dilengkapi dengan integrasi AI yang secara otomatis menghasilkan vektor embedding setiap kali ada perubahan data pada elemen cerita. Ini memungkinkan asisten AI memahami konteks novel Anda secara mendalam.

### 🔄 Observers & AI Jobs
Sistem menggunakan **Laravel Observers** untuk memantau perubahan model berikut:
- **Chapter**: Mengambil konten bab untuk referensi alur cerita.
- **Character**: Melacak profil, peran, dan latar belakang karakter.
- **CharacterPower**: Mendokumentasikan sistem kekuatan dan level karakter.
- **Location**: Memetakan geografi dan iklim dunia novel.
- **LoreEntry**: Mengarsipkan sejarah dan hukum dunia (*worldbuilding*).

Setiap kali data di atas di-`save` atau di-`update`, sistem akan memicu `ProcessAiEmbedding` yang dikirim ke antrean (*queue*) untuk diproses oleh AI.

---

## 🛠️ Panduan Operasional (Queue & Deployment)

Karena proses AI Embedding memakan waktu dan bergantung pada API eksternal, proses ini dijalankan di background.

### 💻 Menjalankan di Local (Development)
1. **Pastikan Driver Queue diset ke database** di file `.env`:
   ```env
   QUEUE_CONNECTION=database
   ```
   
2. **Jalankan Worker** di terminal terpisah agar proses embedding berjalan otomatis:
   ```bash
   php artisan queue:work
   ```
   Tanpa perintah ini, data novel Anda tidak akan ter-embed ke dalam database AI.
---

## 🚀 Menjalankan di Production
Di lingkungan produksi, Anda tidak bisa sekadar menjalankan queue:work di terminal karena prosesnya akan mati jika terminal ditutup.

1. **Gunakan Supervisor**: Instal dan konfigurasi Supervisor di server Linux Anda untuk memastikan php artisan queue:work berjalan terus-menerus (restart otomatis jika crash).

2. **Optimasi Cache**
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
   ```

3. **Restart Worker setelah Update**: Setiap kali Anda melakukan git pull atau perubahan kode pada Production, wajib restart worker:
    ```bash
    php artisan queue:restart
   ```
---

## 🔧 Pemeliharaan Database AI
Jika Anda menghapus data (Novel/Chapter/Character), Observer akan otomatis menghapus vektor terkait di tabel ai_vectors menggunakan Polymorphic Delete.

Jika Anda ingin melakukan re-embedding massal untuk data lama:

```bash
# Gunakan Tinker untuk memicu ulang observer pada semua data
php artisan tinker
>>> \App\Models\Chapter::all()->each->save();
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

MIT License © 2025 [fzdev](https://github.com/mohpais)

---

## 📬 Kontak

📧 Email: [mohamad.pais30@gmail.com](mailto:mohamad.pais30@gmail.com)  
🌐 Website: [mynovel.local](http://localhost)

---

## 🌟 Terima kasih sudah menggunakan & mendukung **My Novel Management System**!  
_Keep writing. Your world is waiting._ ✨
