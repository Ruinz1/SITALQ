@component('mail::message')
Assamualaikum Warahmatullahi Wabarakatuh,
# Selamat {{ $peserta->nama }}!

Anda telah terdaftar di kelas berikut:

**Kelas:** {{ $kelas->nama_kelas }}  
**Tahun Ajaran:** {{ $kelas->tahunAjaran->nama }}

Terima kasih telah bergabung dengan kami.

Salam,<br>
{{ config('app.name') }}
@endcomponent 