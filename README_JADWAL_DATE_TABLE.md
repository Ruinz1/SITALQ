# Fitur Date Table Jadwal

## 🎯 Overview

Fitur ini mengubah tampilan jadwal dari format tabel biasa menjadi format **date table** yang menampilkan jadwal berdasarkan hari dan jam. Tampilan ini memudahkan pengguna untuk melihat jadwal pelajaran dalam format yang lebih visual dan mudah dipahami.

## ✨ Fitur Utama

### 📅 Tampilan Date Table
- **Format Tabel**: Hari sebagai kolom (Senin-Jumat) dan jam sebagai baris
- **Jam Pelajaran**: Dari jam 07:00 hingga 16:00 dengan interval 30 menit
- **Slot Kosong**: Menampilkan tombol "Tambah" untuk slot yang belum ada jadwal
- **Jadwal Aktif**: Menampilkan informasi mata pelajaran, guru, dan kelas

### 🔍 Filter dan Pencarian
- **Filter Tahun Ajaran**: Memfilter jadwal berdasarkan tahun ajaran aktif
- **Filter Kelas**: Memfilter jadwal berdasarkan kelas tertentu
- **Kombinasi Filter**: Dapat menggunakan kedua filter sekaligus

### 📊 Statistik Jadwal
- **Total Jadwal**: Jumlah total jadwal yang ada
- **Jadwal Aktif**: Jumlah jadwal yang sudah diisi
- **Konflik Jadwal**: Jumlah jadwal yang bertabrakan (dengan detail)
- **Slot Kosong**: Jumlah slot waktu yang belum diisi

### ⚠️ Deteksi Konflik
- **Konflik Otomatis**: Sistem mendeteksi jadwal yang bertabrakan
- **Visual Indicator**: Jadwal konflik ditampilkan dengan warna merah
- **Detail Konflik**: Modal popup untuk melihat detail konflik

### ⚡ Aksi Cepat
- **Tambah Jadwal**: Tombol untuk menambah jadwal baru dari slot kosong
- **Edit Jadwal**: Link langsung ke halaman edit jadwal
- **View Jadwal**: Link untuk melihat detail jadwal

## 🚀 Cara Menjalankan

### 1. Pastikan Dependencies Terinstall
```bash
composer install
npm install
```

### 2. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### 3. Generate Assets
```bash
npm run build
```

### 4. Jalankan Server
```bash
php artisan serve
```

### 5. Akses Fitur
1. Login ke admin panel
2. Navigasi ke menu "Jadwal Management" > "Jadwal"
3. Tampilan akan otomatis menampilkan date table

## 📁 Struktur File

```
app/
├── Http/Controllers/
│   └── JadwalScheduleController.php    # API Controller untuk jadwal
├── Filament/Resources/
│   ├── JadwalResource.php              # Resource utama jadwal
│   └── JadwalResource/Pages/
│       ├── ListJadwals.php             # Custom list page
│       ├── CreateJadwal.php            # Create page
│       └── EditJadwal.php              # Edit page
└── Models/
    └── jadwal.php                      # Model jadwal

resources/views/filament/resources/jadwal-resource/pages/
└── list-jadwals.blade.php              # Custom view untuk date table

routes/
└── api.php                             # API routes untuk jadwal
```

## 🔧 Konfigurasi

### 1. API Routes
Pastikan route berikut sudah terdaftar di `routes/api.php`:
```php
Route::get('/jadwal/schedule', [JadwalScheduleController::class, 'getSchedule']);
Route::get('/jadwal/conflicts', [JadwalScheduleController::class, 'getConflicts']);
```

### 2. Filament Resource
Pastikan `JadwalResource` sudah terdaftar di `config/filament.php`:
```php
'resources' => [
    'namespace' => 'App\\Filament\\Resources',
    'path' => app_path('Filament/Resources'),
    'register' => [
        \App\Filament\Resources\JadwalResource::class,
    ],
],
```

## 🎨 Customization

### 1. Mengubah Jam Pelajaran
Edit file `list-jadwals.blade.php` dan ubah bagian:
```javascript
// Generate time slots from 07:00 to 16:00
const timeSlots = [];
for (let hour = 7; hour <= 16; hour++) {
    timeSlots.push(`${hour.toString().padStart(2, '0')}:00`);
    if (hour < 16) {
        timeSlots.push(`${hour.toString().padStart(2, '0')}:30`);
    }
}
```

### 2. Mengubah Warna Tema
Edit CSS classes di file `list-jadwals.blade.php`:
```html
<!-- Jadwal Normal -->
<div class="bg-blue-100 border-blue-300">

<!-- Jadwal Konflik -->
<div class="bg-red-100 border-red-300">

<!-- Slot Kosong -->
<div class="bg-indigo-50">
```

### 3. Menambah Hari Baru
Edit array hari di file `list-jadwals.blade.php`:
```javascript
const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
```

## 🔍 Troubleshooting

### 1. Jadwal Tidak Muncul
- ✅ Periksa filter tahun ajaran dan kelas
- ✅ Pastikan data jadwal sudah ada di database
- ✅ Cek console browser untuk error JavaScript

### 2. Konflik Tidak Terdeteksi
- ✅ Pastikan ada jadwal dengan hari, jam, dan kelas yang sama
- ✅ Periksa format waktu di database
- ✅ Cek API endpoint `/api/jadwal/conflicts`

### 3. Filter Tidak Berfungsi
- ✅ Periksa koneksi internet
- ✅ Cek console browser untuk error
- ✅ Pastikan API endpoint dapat diakses

### 4. Error 404 pada API
- ✅ Pastikan route sudah terdaftar
- ✅ Jalankan `php artisan route:clear`
- ✅ Periksa namespace controller

## 📱 Responsive Design

### Desktop
- Tampilan penuh dengan semua fitur
- Tabel dengan scroll horizontal jika diperlukan

### Tablet
- Grid layout yang menyesuaikan
- Filter dalam satu baris

### Mobile
- Stack layout untuk filter
- Scroll horizontal untuk tabel
- Modal yang responsive

## 🔒 Keamanan

### Role-based Access
- Hanya user dengan role yang sesuai yang dapat mengakses
- Edit dan delete hanya untuk admin

### Data Validation
- Validasi input pada form jadwal
- Pengecekan konflik sebelum menyimpan

### Soft Delete
- Data jadwal tidak dihapus permanen
- Dapat dipulihkan jika diperlukan

## 🚀 Pengembangan Selanjutnya

### Fitur yang Direncanakan
- [ ] Export jadwal ke PDF/Excel
- [ ] Import jadwal dari file Excel
- [ ] Drag & drop untuk mengatur jadwal
- [ ] Notifikasi real-time untuk konflik
- [ ] Sync dengan Google Calendar
- [ ] Webhook untuk sistem eksternal

### Optimasi
- [ ] Caching untuk data jadwal
- [ ] Lazy loading untuk jadwal besar
- [ ] Optimasi query database

## 📞 Support

Jika mengalami masalah atau memiliki pertanyaan, silakan:

1. Cek dokumentasi lengkap di `JADWAL_DATE_TABLE_DOCUMENTATION.md`
2. Periksa troubleshooting guide di atas
3. Hubungi tim development

## 📄 License

Fitur ini dikembangkan untuk internal use. Semua hak cipta dilindungi.



