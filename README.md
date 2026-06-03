# Undi

Undi adalah starter aplikasi SaaS undangan digital berbasis Laravel, MySQL, Tailwind CSS, Blade, dan Filament Admin. Aplikasi ini disusun untuk mengelola undangan online, template, tamu, RSVP, ucapan, galeri, timeline, amplop digital, statistik pengunjung, paket langganan, transaksi, dan konfigurasi website.

## Stack

- Laravel 13.x
- PHP 8.3+
- MySQL 8+
- Blade + Tailwind CSS 4
- Filament Admin 5
- Laravel Excel untuk import/export tamu dan RSVP
- Vite untuk asset frontend

## Fitur Utama

- Landing page, pricing page, template listing, sitemap, dan robots.txt
- Authentication sederhana: register, login, logout, dan halaman reset password placeholder
- Dashboard user dengan statistik undangan, tamu, RSVP, ucapan, views, dan shortcut pembuatan undangan
- CRUD undangan dengan slug unik, template, data acara, lokasi, status, password opsional, tema, musik, dan pesan WhatsApp
- Halaman undangan publik mobile-first dengan opening screen, countdown, galeri, timeline, maps, RSVP, ucapan, amplop digital, QR code, dan share WhatsApp
- Manajemen tamu dengan link personal, WhatsApp message, import Excel, dan export Excel
- RSVP dengan batas jumlah tamu berdasarkan data personal
- Moderasi ucapan sebelum tampil ke publik
- Statistik dasar: total view, unique visitor, share, conversion rate, dan kunjungan terakhir
- Struktur paket langganan dan transaksi untuk integrasi Midtrans/Xendit
- Field custom domain/subdomain dan status verifikasi domain
- Filament resources untuk User, Plan, Invitation, InvitationTemplate, Guest, RSVP, GuestMessage, Gallery, Story, GiftAccount, Transaction, dan Setting

## Instalasi

Dependency project ini bisa memakai tool global, atau tool portable yang sudah disiapkan di folder `.tools/`:

- `.tools/php/php.exe`
- `.tools/composer.phar`
- `.tools/node/node.exe`
- `.tools/node/npm.cmd`

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Atur database MySQL di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=undi
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi dan seed:

```bash
php artisan migrate --seed
php artisan storage:link
```

Install dan build asset:

```bash
npm install
npm run build
```

Jika memakai tool portable di PowerShell:

```powershell
$env:PATH = "$PWD\.tools\php;$PWD\.tools\node;$env:PATH"
.\.tools\php\php.exe .\.tools\composer.phar install
.\.tools\node\npm.cmd install
.\.tools\node\npm.cmd run build
.\.tools\php\php.exe artisan serve
```

Untuk development:

```bash
composer run dev
```

Atau jalankan manual:

```bash
php artisan serve
npm run dev
```

## Akun Demo

Seeder membuat akun berikut:

- Admin: `admin@undi.test` / `password`
- Customer: `customer@undi.test` / `password`

Panel admin tersedia di:

```text
/admin
```

Demo undangan tersedia setelah seed:

```text
/u/andi-sinta
```

## Format Import Tamu

Import tamu menerima file `.xlsx`, `.xls`, atau `.csv` dengan heading berikut:

```text
nama, whatsapp, email, kategori, maksimal_tamu, catatan
```

Heading bahasa Inggris juga diterima untuk beberapa kolom:

```text
name, wa, category, max_companions, notes
```

## Catatan Produksi

- Gunakan queue untuk export besar, notifikasi, webhook payment gateway, dan proses optimasi gambar.
- QR code saat ini memakai endpoint PNG eksternal di accessor model agar starter langsung tampil. Untuk production penuh, ganti dengan generator QR lokal dan simpan ke storage.
- Form public memakai rate limit `public-forms` dan CSRF protection.
- Ucapan publik disanitasi dengan `strip_tags()` dan default disembunyikan sampai disetujui.
- Data user dibatasi melalui ownership check di dashboard dan policy `InvitationPolicy`.
- Publish migration export Filament bila ingin memakai `ExportAction` bawaan Filament secara penuh.

## Status Verifikasi Lokal

Project sudah dijalankan memakai tool portable di `.tools/`. Migrasi, seeder, test Laravel, Vite dev server, dan Laravel dev server berhasil dipakai secara lokal.
