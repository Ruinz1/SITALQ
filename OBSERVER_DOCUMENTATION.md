# Dokumentasi Observer Laravel - Sistem Kas Otomatis

## 📋 Overview

Sistem ini menggunakan Observer Laravel untuk mengotomatisasi proses penambahan entri ke tabel `kas` berdasarkan perubahan pada model `Transaksi` dan `Pagu_anggaran`.

## 🔄 Logika Observer

### 1. TransaksiObserver

**Lokasi:** `app/Observers/TransaksiObserver.php`

**Trigger:** 
- Saat transaksi dibuat (`created`) dengan `status_pembayaran == 1`
- Saat transaksi diupdate (`updated`) dan `status_pembayaran` berubah menjadi `1`
- Saat transaksi dihapus (`deleted`) - **BARU**
- Saat transaksi dihapus permanen (`forceDeleted`) - **BARU**

**Aksi:**
- **Created/Updated**: Membuat entri baru di tabel `kas`
- **Deleted**: Menghapus entri kas yang terkait dengan transaksi
- **ForceDeleted**: Menghapus permanen entri kas yang terkait dengan transaksi

**Field yang diisi saat Created/Updated:**
  - `transaksi_id`: ID dari transaksi
  - `pagu_anggaran_id`: `null`
  - `tahun_ajaran_id`: dari relasi peserta
  - `tipe`: "masuk"
  - `sumber`: "Transaksi"
  - `jumlah`: `total_bayar` dari transaksi
  - `keterangan`: "Transaksi Pembayaran - [nama peserta]"
  - `kategori`: "Pendaftaran"

**Contoh Keterangan:**
```
Transaksi Pembayaran - John Doe
```

### 2. PaguAnggaranObserver

**Lokasi:** `app/Observers/PaguAnggaranObserver.php`

**Trigger:**
- Saat pagu anggaran dibuat (`created`) dengan `status == "approved"`
- Saat pagu anggaran diupdate (`updated`) dan `status` berubah menjadi `"approved"`
- Saat pagu anggaran dihapus (`deleted`) - **BARU**
- Saat pagu anggaran dihapus permanen (`forceDeleted`) - **BARU**

**Aksi:**
- **Created/Updated**: Membuat entri baru di tabel `kas`
- **Deleted**: Menghapus entri kas yang terkait dengan pagu anggaran
- **ForceDeleted**: Menghapus permanen entri kas yang terkait dengan pagu anggaran

**Field yang diisi saat Created/Updated:**
  - `transaksi_id`: `null`
  - `pagu_anggaran_id`: ID dari pagu anggaran
  - `tahun_ajaran_id`: dari pagu anggaran
  - `tipe`: "masuk"
  - `sumber`: "Anggaran/Pengadaan"
  - `jumlah`: `total_harga` dari pagu anggaran
  - `keterangan`: dari kolom `keterangan` pagu anggaran
  - `kategori`: dari pagu anggaran
  - `user_id`: `disetujui_oleh` dari pagu anggaran

## 📊 Struktur Tabel Kas

| Field | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `transaksi_id` | bigint (nullable) | ID transaksi (jika dari transaksi) |
| `pagu_anggaran_id` | bigint (nullable) | ID pagu anggaran (jika dari anggaran) |
| `tahun_ajaran_id` | bigint | ID tahun ajaran |
| `tipe` | enum | "masuk" atau "keluar" |
| `sumber` | string | Sumber dana |
| `jumlah` | decimal | Jumlah uang |
| `kategori` | string | Kategori transaksi |
| `keterangan` | text | Deskripsi transaksi |
| `tanggal` | date | Tanggal transaksi |
| `user_id` | bigint (nullable) | ID user yang melakukan |

## 🔧 Registrasi Observer

### TransaksiObserver
```php
// app/Models/transaksi.php
protected static function booted()
{
    static::observe(\App\Observers\TransaksiObserver::class);
}
```

### PaguAnggaranObserver
```php
// app/Models/Pagu_anggaran.php
protected static function booted()
{
    static::observe(\App\Observers\PaguAnggaranObserver::class);
}
```

## 🚨 Error Handling

Kedua Observer memiliki error handling yang komprehensif:

1. **Try-Catch Block**: Menangkap exception saat pembuatan/penghapusan entri kas
2. **Logging**: Mencatat log sukses dan error untuk debugging
3. **Rollback**: Untuk PaguAnggaranObserver, jika terjadi error, status akan dikembalikan ke nilai sebelumnya
4. **Exception Re-throw**: Error akan dilempar kembali agar dapat ditangani oleh aplikasi

## 📝 Log Format

### Sukses Log - Created/Updated
```php
Log::info('Entri kas berhasil dibuat untuk transaksi', [
    'transaksi_id' => $transaksi->id,
    'peserta_nama' => $transaksi->peserta->nama,
    'jumlah' => $transaksi->total_bayar
]);
```

### Sukses Log - Deleted
```php
Log::info('Entri kas berhasil dihapus untuk transaksi yang dihapus', [
    'transaksi_id' => $transaksi->id,
    'deleted_count' => $deletedCount
]);
```

### Error Log
```php
Log::error('Gagal membuat entri kas untuk transaksi', [
    'transaksi_id' => $transaksi->id,
    'error' => $e->getMessage()
]);
```

## 🧪 Testing

Untuk testing Observer, Anda dapat:

1. **Membuat transaksi dengan status lunas:**
```php
$transaksi = Transaksi::create([
    'peserta_id' => 1,
    'status_pembayaran' => 1,
    'total_bayar' => 1000000
]);
// Akan otomatis membuat entri kas
```

2. **Mengupdate status pagu anggaran:**
```php
$paguAnggaran = Pagu_anggaran::find(1);
$paguAnggaran->update(['status' => 'approved']);
// Akan otomatis membuat entri kas
```

3. **Menghapus transaksi:**
```php
$transaksi = Transaksi::find(1);
$transaksi->delete();
// Akan otomatis menghapus entri kas terkait
```

4. **Menghapus pagu anggaran:**
```php
$paguAnggaran = Pagu_anggaran::find(1);
$paguAnggaran->delete();
// Akan otomatis menghapus entri kas terkait
```

## ⚠️ Penting

1. **Relasi Peserta**: TransaksiObserver memastikan relasi `peserta` dimuat sebelum mengakses data peserta
2. **Status Check**: Observer hanya berjalan ketika status berubah ke nilai yang diinginkan
3. **Data Integrity**: Menggunakan transaction untuk memastikan konsistensi data
4. **Performance**: Observer berjalan secara asynchronous dan tidak memblokir operasi utama
5. **Cascade Delete**: Saat data utama dihapus, entri kas terkait akan otomatis terhapus

## 🔄 Workflow

### Create/Update Flow
```
Transaksi (status_pembayaran = 1) 
    ↓
TransaksiObserver::updated/created
    ↓
Kas::create([...])
    ↓
Log::info('Sukses')
```

### Delete Flow
```
Transaksi/PaguAnggaran dihapus
    ↓
TransaksiObserver/PaguAnggaranObserver::deleted
    ↓
Kas::where(...)->delete()
    ↓
Log::info('Sukses hapus')
```

```
PaguAnggaran (status = "approved")
    ↓
PaguAnggaranObserver::updated/created
    ↓
Kas::create([...])
    ↓
Log::info('Sukses')
``` 