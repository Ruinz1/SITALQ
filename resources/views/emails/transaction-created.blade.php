<div>
    <h2>Informasi Transaksi Pendaftaran</h2>
    <p>Halo {{ $peserta->nama }},</p>
    <p>Terima kasih telah mendaftar. Berikut adalah detail transaksi Anda:</p>
    <p>Kode Transaksi: <strong>{{ $kodeTransaksi }}</strong></p>
    <p>Total Pembayaran: Rp {{ number_format($totalBayar, 0, ',', '.') }}</p>
    <p>Silakan klik link di bawah ini untuk melakukan pembayaran:</p>
    <p>
        <a href="{{ route('front.pembayaran', ['kode' => $kodeTransaksi]) }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Lakukan Pembayaran
        </a>
    </p>
    <p>Atau gunakan kode transaksi di atas untuk melakukan pembayaran di halaman pembayaran.</p>
</div> 