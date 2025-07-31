# Sistem Manajemen Sekolah - Trial Role Project

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11.9-red.svg" alt="Laravel Version">
<img src="https://img.shields.io/badge/PHP-8.3.1-blue.svg" alt="PHP Version">
<img src="https://img.shields.io/badge/Filament-3.2-green.svg" alt="Filament Version">
<img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License">
</p>

## 📋 Deskripsi Sistem

Sistem Manajemen Sekolah adalah aplikasi web berbasis Laravel yang dirancang untuk mengelola seluruh aspek administrasi sekolah, mulai dari pendaftaran peserta didik, manajemen kelas, jadwal pembelajaran, pembayaran, hingga penilaian akademik. Sistem ini menggunakan Filament sebagai admin panel yang modern dan responsif.

## ✨ Fitur Utama

### 🎓 Manajemen Akademik
- **Pendaftaran Peserta Didik**: Sistem pendaftaran online dengan validasi kode pendaftaran
- **Manajemen Kelas**: Pengaturan kelas, kapasitas, dan guru pengajar
- **Jadwal Pembelajaran**: Penjadwalan mata pelajaran dan kegiatan akademik
- **Penilaian**: Sistem penilaian dan rapor siswa
- **Mata Pelajaran**: Pengelolaan mata pelajaran dan kurikulum

### 👥 Manajemen SDM
- **Guru**: Data lengkap guru, mata pelajaran yang diampu
- **Peserta Didik**: Profil lengkap siswa beserta data keluarga
- **User Management**: Manajemen pengguna dengan role dan permission

### 💰 Manajemen Keuangan
- **Pembayaran**: Integrasi dengan Midtrans untuk pembayaran online
- **Kas**: Pencatatan keuangan sekolah
- **Pagu Anggaran**: Pengelolaan anggaran dan pengajuan dana
- **Transaksi**: Riwayat pembayaran dan status pembayaran

### 📊 Laporan dan Monitoring
- **Dashboard Admin**: Statistik dan overview sistem
- **Export Data**: Export data ke PDF dan Excel
- **Laporan Keuangan**: Laporan kas dan transaksi
- **Laporan Akademik**: Laporan nilai dan prestasi siswa

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 11.9** - Framework PHP modern
- **PHP 8.3.1** - Bahasa pemrograman
- **MySQL/PostgreSQL** - Database
- **Filament 3.2** - Admin panel framework

### Frontend
- **Tailwind CSS** - Framework CSS utility-first
- **Alpine.js** - JavaScript framework
- **Blade Templates** - Template engine Laravel

### Integrasi
- **Midtrans** - Payment gateway
- **Twilio** - WhatsApp integration
- **DOMPDF** - PDF generation
- **Spatie Permission** - Role dan permission management

## 📦 Instalasi

### Prerequisites
- PHP >= 8.3.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Git

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd trial-role-project
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**
```bash
# Edit file .env dan sesuaikan konfigurasi database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

5. **Setup Midtrans (Opsional)**
```bash
# Edit file .env untuk konfigurasi Midtrans
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

6. **Setup Twilio (Opsional)**
```bash
# Edit file .env untuk konfigurasi Twilio
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_WHATSAPP_NUMBER=your_whatsapp_number
```

7. **Jalankan Migration dan Seeder**
```bash
php artisan migrate
php artisan db:seed
```

8. **Build Assets**
```bash
npm run build
```

9. **Jalankan Server**
```bash
php artisan serve
```

## 🚀 Penggunaan

### Akses Admin Panel
- URL: `http://localhost:8000/admin`
- Default credentials akan dibuat melalui seeder

### Akses Frontend
- URL: `http://localhost:8000`
- Halaman utama website sekolah

## 📁 Struktur Proyek

```
trial-role-project/
├── app/
│   ├── Filament/           # Admin panel resources
│   ├── Http/Controllers/   # Controllers
│   ├── Models/            # Eloquent models
│   ├── Observers/         # Model observers
│   ├── Policies/          # Authorization policies
│   └── Services/          # Business logic services
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   └── views/            # Blade templates
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
└── public/               # Public assets
```

## 🔐 Role dan Permission

Sistem menggunakan Spatie Permission dengan role berikut:

- **Super Admin**: Akses penuh ke semua fitur
- **Admin**: Manajemen data sekolah
- **Guru**: Akses terbatas untuk data kelas dan siswa
- **Staff**: Akses untuk pendaftaran dan pembayaran

## 📊 Model Database

### Core Models
- **Peserta**: Data peserta didik
- **Kelas**: Data kelas dan pengaturan
- **Guru**: Data guru dan mata pelajaran
- **Transaksi**: Data pembayaran
- **Kas**: Pencatatan keuangan
- **Jadwal**: Penjadwalan pembelajaran

### Supporting Models
- **TahunAjaran**: Tahun ajaran aktif
- **Mapel**: Mata pelajaran
- **Pendaftaran**: Data pendaftaran
- **Penilaian**: Data penilaian siswa

## 🔄 Observer Pattern

Sistem menggunakan Observer pattern untuk:
- **KelasObserver**: Auto-generate kode kelas
- **PesertaObserver**: Auto-generate kode peserta
- **TransaksiObserver**: Handle payment status
- **PaguAnggaranObserver**: Auto-generate kode pengajuan

## 📧 Email Notifications

Sistem mengirim email otomatis untuk:
- Konfirmasi pendaftaran peserta
- Notifikasi pembayaran berhasil
- Pemberitahuan peserta terdaftar di kelas

## 🔗 API Endpoints

### Public API
- `GET /api/check-kode/{kode}` - Validasi kode pendaftaran
- `GET /api/check-pembayaran/{kode}` - Cek status pembayaran

### Payment Webhook
- `POST /payment/notification` - Midtrans payment notification

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Jalankan test specific
php artisan test --filter ObserverTest
```

## 📦 Deployment

### Production Setup
1. Set environment ke production
2. Optimize Laravel
3. Setup queue worker
4. Configure web server (Nginx/Apache)

```bash
# Production commands
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:work
```

### Docker Deployment
```bash
docker-compose up -d
```

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).

## 📞 Support

Untuk bantuan dan dukungan:
- Email: support@example.com
- Documentation: [Link ke dokumentasi]
- Issues: [GitHub Issues](https://github.com/username/repo/issues)

## 🔄 Changelog

### v1.0.0 (2024-11-22)
- Initial release
- Basic CRUD operations
- Payment integration
- Admin panel with Filament
- Role-based access control

---

**Dibuat dengan ❤️ menggunakan Laravel dan Filament**
