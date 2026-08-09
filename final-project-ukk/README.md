# 🎫 Helpdesk AI — Sistem Tiket Pengaduan Otomatis

Aplikasi helpdesk berbasis web yang dibangun menggunakan **Laravel 12**, dilengkapi dengan fitur **AI Auto-Reply** menggunakan **Groq API (LLaMA 3.1)**, notifikasi email otomatis via **Gmail SMTP**, keamanan captcha via **Google reCAPTCHA v3**, serta panel admin yang lengkap.

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Proyek](#-struktur-proyek)
- [Prasyarat](#-prasyarat)
- [Cara Instalasi](#-cara-instalasi)
- [Konfigurasi Environment](#-konfigurasi-environment)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Alur Kerja Aplikasi](#-alur-kerja-aplikasi)
- [Panduan Penggunaan](#-panduan-penggunaan)

---

## ✨ Fitur Utama

### Untuk Pengguna (Publik)
- **Submit Tiket** — Pengguna dapat mengirim tiket keluhan dengan melampirkan file (JPG, PNG, PDF, DOC, DOCX).
- **AI Auto-Reply** — Setiap tiket yang masuk akan langsung dibalas secara otomatis oleh AI (Groq LLaMA 3.1).
- **Notifikasi Email** — Email konfirmasi dan balasan AI langsung dikirim ke email pengguna setelah tiket dibuat.
- **Lacak Tiket** — Pengguna dapat melacak status tiket menggunakan nomor tiket unik (format: `TIX-XXXXXX`).
- **Keamanan reCAPTCHA** — Formulir submit tiket dilindungi oleh Google reCAPTCHA v3.

### Untuk Admin
- **Dashboard** — Ringkasan statistik tiket berdasarkan status dan prioritas.
- **Manajemen Tiket** — Melihat semua tiket yang masuk dengan informasi lengkap.
- **Balas Tiket** — Admin dapat membalas tiket langsung dari panel admin, dan email balasan otomatis dikirim ke pengguna.
- **AI Analisis Tiket** — Setiap tiket yang dibuka admin akan dianalisis AI untuk menghasilkan ringkasan, saran kategori, dan saran prioritas.
- **Terapkan Saran AI** — Admin dapat menerima saran kategori dan prioritas dari AI dengan satu klik.
- **Ubah Status Tiket** — Admin dapat mengubah status tiket (Open / In Progress / Closed).
- **Export Data** — Admin dapat mengekspor data tiket.

---

## 🛠 Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Framework Backend | Laravel 12 (PHP ^8.2) |
| Database | SQLite (default) |
| Frontend | Blade Template + Vite |
| AI Engine | Groq API — Model `llama-3.1-8b-instant` |
| Email | Gmail SMTP |
| Keamanan Form | Google reCAPTCHA v3 |
| Autentikasi Admin | Laravel Built-in Auth |

---

## 📁 Struktur Proyek

```
final-project-ukk/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── PublicController.php        # Handle tiket publik & lacak tiket
│   │       └── Admin/
│   │           ├── AuthController.php      # Login/logout admin
│   │           ├── DashboardController.php # Dashboard & export
│   │           └── TicketController.php    # Manajemen tiket admin + AI
│   ├── Mail/
│   │   ├── TicketCreatedMail.php           # Email konfirmasi tiket dibuat
│   │   ├── TicketReplyMail.php             # Email balasan AI ke pengguna
│   │   └── TicketRepliedMail.php           # Email notifikasi balasan admin
│   ├── Models/
│   │   ├── Ticket.php
│   │   ├── TicketReply.php
│   │   ├── Category.php
│   │   └── User.php
│   └── Services/
│       └── GroqService.php                 # Integrasi Groq AI API
├── database/
│   └── migrations/                         # Skema database
├── resources/
│   └── views/
│       ├── public/                         # Tampilan halaman publik
│       └── admin/                          # Tampilan panel admin
├── routes/
│   └── web.php                             # Definisi semua route
└── .env                                    # Konfigurasi environment
```

---

## ✅ Prasyarat

Pastikan perangkat Anda sudah terpasang:

- **PHP** >= 8.2
- **Composer**
- **Node.js** & **NPM**
- **Laravel Herd** (atau server lokal lain seperti XAMPP/Laravel Valet)
- Akun **Groq** → [console.groq.com](https://console.groq.com) (untuk mendapatkan API Key)
- Akun **Google** dengan **App Password** yang aktif (untuk Gmail SMTP)
- Akun **Google reCAPTCHA v3** → [google.com/recaptcha](https://www.google.com/recaptcha)

---

## 🚀 Cara Instalasi

### 1. Clone Repositori

```bash
git clone <url-repositori-anda>
cd final-project-ukk
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node.js

```bash
npm install
```

### 4. Salin File Environment

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Buat File Database SQLite

```bash
# Windows
type nul > database\database.sqlite

# Linux / macOS
touch database/database.sqlite
```

### 7. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 8. Buat Akun Admin (Seeder atau Tinker)

```bash
php artisan tinker
```

Kemudian jalankan perintah berikut di dalam Tinker:

```php
\App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@helpdesk.test',
    'password' => bcrypt('password123'),
]);
exit
```

---

## ⚙️ Konfigurasi Environment

Buka file `.env` dan sesuaikan konfigurasi berikut:

### Konfigurasi Dasar Aplikasi
```env
APP_NAME=Laravel
APP_URL=http://final-project-ukk.test
```

### Konfigurasi Email (Gmail SMTP)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailkamu@gmail.com
MAIL_PASSWORD="xxxx xxxx xxxx xxxx"   # Gunakan Google App Password, BUKAN password Gmail biasa
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=emailkamu@gmail.com
MAIL_FROM_NAME="Helpdesk AI"
```

> **Cara mendapatkan Google App Password:**
> Buka [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords), lalu buat App Password baru untuk kategori "Mail".

### Konfigurasi Groq AI
```env
GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

> **Cara mendapatkan Groq API Key:**
> Daftar/login di [console.groq.com](https://console.groq.com), masuk ke menu **API Keys**, lalu buat key baru.

### Konfigurasi Google reCAPTCHA v3
```env
RECAPTCHA_SITE_KEY=your_recaptcha_site_key
RECAPTCHA_SECRET_KEY=your_recaptcha_secret_key
```

> **Cara mendapatkan reCAPTCHA Key:**
> Kunjungi [google.com/recaptcha/admin](https://www.google.com/recaptcha/admin), buat situs baru dengan tipe **reCAPTCHA v3**, daftarkan domain Anda.

---

## ▶️ Menjalankan Aplikasi

### Mode Development

```bash
# Build assets frontend (Vite)
npm run dev

# Jalankan server Laravel (di terminal terpisah)
php artisan serve
```

Atau jika menggunakan **Laravel Herd**, cukup akses langsung:
```
http://final-project-ukk.test
```

### Build untuk Produksi

```bash
npm run build
```

---

## 🔄 Alur Kerja Aplikasi

```
Pengguna Submit Tiket
        │
        ▼
[Verifikasi reCAPTCHA v3]
        │
        ▼
[Tiket Tersimpan di Database]
        │
        ├──► Email Konfirmasi dikirim ke Pengguna (Gmail SMTP)
        │
        ├──► Groq AI (llama-3.1-8b-instant) Membuat Balasan Otomatis
        │         │
        │         ├──► Balasan AI disimpan di database (sender: 'ai')
        │         └──► Email Balasan AI dikirim ke Pengguna (Gmail SMTP)
        │
        └──► Admin membuka tiket di Panel Admin
                  │
                  ├──► AI menganalisis tiket → ringkasan + saran kategori + saran prioritas
                  ├──► Admin dapat menerima atau mengabaikan saran AI
                  ├──► Admin membalas tiket
                  │         └──► Email notifikasi balasan admin dikirim ke Pengguna
                  └──► Admin mengubah status (Open → In Progress → Closed)
```

---

## 📖 Panduan Penggunaan

### Akses Halaman Publik

| Halaman | URL |
|---|---|
| Beranda / Form Submit Tiket | `http://your-domain/` |
| Lacak Status Tiket | `http://your-domain/lacak` |

### Akses Panel Admin

| Halaman | URL |
|---|---|
| Login Admin | `http://your-domain/admin/login` |
| Dashboard Admin | `http://your-domain/admin` |
| Daftar Tiket | `http://your-domain/admin/tickets` |

### Nomor Tiket

Setiap tiket yang berhasil dibuat akan mendapatkan nomor unik dengan format:
```
TIX-XXXXXX   (contoh: TIX-AB3C9F)
```
Nomor ini digunakan pengguna untuk melacak status tiket di halaman `/lacak`.

---

## 📄 Lisensi

Proyek ini dikembangkan sebagai **Final Project UKK**.
