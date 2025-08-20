# Dokumentasi Fitur Date Table Jadwal

## Deskripsi
Fitur ini mengubah tampilan jadwal dari format tabel biasa menjadi format date table yang menampilkan jadwal berdasarkan hari dan jam. Tampilan ini memudahkan pengguna untuk melihat jadwal pelajaran dalam format yang lebih visual dan mudah dipahami.

## Fitur Utama

### 1. Tampilan Date Table
- **Format Tabel**: Hari sebagai kolom (Senin-Jumat) dan jam sebagai baris
- **Jam Pelajaran**: Dari jam 07:00 hingga 16:00 dengan interval 30 menit
- **Slot Kosong**: Menampilkan tombol "Tambah" untuk slot yang belum ada jadwal
- **Jadwal Aktif**: Menampilkan informasi mata pelajaran, guru, dan kelas

### 2. Filter dan Pencarian
- **Filter Tahun Ajaran**: Memfilter jadwal berdasarkan tahun ajaran aktif
- **Filter Kelas**: Memfilter jadwal berdasarkan kelas tertentu
- **Kombinasi Filter**: Dapat menggunakan kedua filter sekaligus

### 3. Statistik Jadwal
- **Total Jadwal**: Jumlah total jadwal yang ada
- **Jadwal Aktif**: Jumlah jadwal yang sudah diisi
- **Konflik Jadwal**: Jumlah jadwal yang bertabrakan (dengan detail)
- **Slot Kosong**: Jumlah slot waktu yang belum diisi

### 4. Deteksi Konflik
- **Konflik Otomatis**: Sistem mendeteksi jadwal yang bertabrakan
- **Visual Indicator**: Jadwal konflik ditampilkan dengan warna merah
- **Detail Konflik**: Modal popup untuk melihat detail konflik

### 5. Aksi Cepat
- **Tambah Jadwal**: Tombol untuk menambah jadwal baru dari slot kosong
- **Edit Jadwal**: Link langsung ke halaman edit jadwal
- **View Jadwal**: Link untuk melihat detail jadwal

## Struktur File

### 1. Controller
- `app/Http/Controllers/JadwalScheduleController.php`
  - `getSchedule()`: Mengambil data jadwal dalam format date table
  - `getConflicts()`: Mengambil data konflik jadwal

### 2. View
- `resources/views/filament/resources/jadwal-resource/pages/list-jadwals.blade.php`
  - Template utama untuk tampilan date table
  - JavaScript untuk interaksi dan rendering

### 3. Resource
- `app/Filament/Resources/JadwalResource.php`
  - Form untuk membuat dan mengedit jadwal
  - Default values untuk hari dan jam dari URL

### 4. Pages
- `app/Filament/Resources/JadwalResource/Pages/ListJadwals.php`
  - Custom view untuk menampilkan date table
  - Data untuk filter dropdown

## API Endpoints

### 1. GET /api/jadwal/schedule
Mengambil data jadwal dalam format yang sesuai untuk date table.

**Parameters:**
- `tahun_ajaran_id` (optional): ID tahun ajaran
- `kelas_id` (optional): ID kelas

**Response:**
```json
{
  "Senin": {
    "07:00": {
      "id": 1,
      "mapel": "Matematika",
      "guru": "Pak Ahmad",
      "kelas": "Kelas 10A",
      "jam": "07:00",
      "tahun_ajaran": "2024/2025",
      "has_conflict": false
    }
  }
}
```

### 2. GET /api/jadwal/conflicts
Mengambil data konflik jadwal.

**Parameters:**
- `tahun_ajaran_id` (optional): ID tahun ajaran
- `kelas_id` (optional): ID kelas

**Response:**
```json
[
  {
    "hari": "Senin",
    "jam": "08:00",
    "kelas": "Kelas 10A",
    "jadwals": [
      {
        "id": 1,
        "mapel": "Matematika",
        "guru": "Pak Ahmad"
      },
      {
        "id": 2,
        "mapel": "Fisika",
        "guru": "Bu Siti"
      }
    ]
  }
]
```

## Cara Penggunaan

### 1. Mengakses Date Table
1. Login ke admin panel
2. Navigasi ke menu "Jadwal Management" > "Jadwal"
3. Tampilan akan otomatis menampilkan date table

### 2. Filter Jadwal
1. Pilih tahun ajaran dari dropdown "Tahun Ajaran"
2. Pilih kelas dari dropdown "Kelas"
3. Klik tombol "Filter"
4. Jadwal akan diperbarui sesuai filter

### 3. Menambah Jadwal Baru
1. Klik tombol "+ Tambah" pada slot kosong
2. Form akan terbuka dengan hari dan jam yang sudah terisi
3. Isi data jadwal lainnya
4. Klik "Create" untuk menyimpan

### 4. Mengedit Jadwal
1. Klik link "Edit" pada jadwal yang ingin diedit
2. Ubah data yang diperlukan
3. Klik "Save" untuk menyimpan perubahan

### 5. Melihat Konflik
1. Klik pada card "Konflik Jadwal" di bagian statistik
2. Modal akan terbuka menampilkan detail konflik
3. Klik "Edit" pada jadwal yang ingin diperbaiki

## Fitur Keamanan

### 1. Role-based Access
- Hanya user dengan role yang sesuai yang dapat mengakses
- Edit dan delete hanya untuk admin

### 2. Data Validation
- Validasi input pada form jadwal
- Pengecekan konflik sebelum menyimpan

### 3. Soft Delete
- Data jadwal tidak dihapus permanen
- Dapat dipulihkan jika diperlukan

## Responsive Design

### 1. Desktop
- Tampilan penuh dengan semua fitur
- Tabel dengan scroll horizontal jika diperlukan

### 2. Tablet
- Grid layout yang menyesuaikan
- Filter dalam satu baris

### 3. Mobile
- Stack layout untuk filter
- Scroll horizontal untuk tabel
- Modal yang responsive

## Troubleshooting

### 1. Jadwal Tidak Muncul
- Periksa filter tahun ajaran dan kelas
- Pastikan data jadwal sudah ada di database
- Cek console browser untuk error JavaScript

### 2. Konflik Tidak Terdeteksi
- Pastikan ada jadwal dengan hari, jam, dan kelas yang sama
- Periksa format waktu di database
- Cek API endpoint `/api/jadwal/conflicts`

### 3. Filter Tidak Berfungsi
- Periksa koneksi internet
- Cek console browser untuk error
- Pastikan API endpoint dapat diakses

## Pengembangan Selanjutnya

### 1. Fitur yang Direncanakan
- Export jadwal ke PDF/Excel
- Import jadwal dari file Excel
- Drag & drop untuk mengatur jadwal
- Notifikasi real-time untuk konflik

### 2. Optimasi
- Caching untuk data jadwal
- Lazy loading untuk jadwal besar
- Optimasi query database

### 3. Integrasi
- Integrasi dengan sistem notifikasi
- Sync dengan Google Calendar
- Webhook untuk sistem eksternal



