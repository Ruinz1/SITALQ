<!DOCTYPE html>
<html>
<head>
    <title>Penerimaan Peserta</title>
</head>
<body>
    <h1>Pemberitahuan Penerimaan Peserta</h1>
    
    <p>Assamualaikum Warahmatullahi Wabarakatuh, <br> {{ $peserta->nama }}  <br> Selamat!</p>
    
    <p>Kami dengan senang hati memberitahukan bahwa Anda telah diterima sebagai peserta di TKIT AL-Qolam.</p>
    
    <p>Detail Peserta:</p>
    <ul>
        <li>Nama: {{ $peserta->nama }}</li>
        @if($peserta->pendaftaran)
        <li>Kode Pendaftaran: {{ $peserta->kodePendaftaran->kode }}</li>
        @endif
    </ul>
    
    <p>Untuk informasi penempatan kelas peserta, akan segera kami informasikan melalui email.</p>
    <p>Terima kasih atas kepercayaan Anda memilih TKIT AL-Qolam sebagai tempat pendidikan.</p>
    
    
    <br>
    <p>Salam,<br>TKIT AL-Qolam</p>
</body>
</html>