<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.5; }
        .section { margin-bottom: 20px; }
        .title { font-weight: bold; font-size: 16px; margin-bottom: 10px; }
        .label { font-weight: bold; }
        .img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <div class="section">
        <div class="title">Informasi Laporan</div>
        <p><span class="label">Fasilitas:</span> {{ $laporan->fasilitas->nama_fasilitas }}</p>
        <p><span class="label">Deskripsi Kerusakan:</span> {{ $laporan->deskripsi_kerusakan }}</p>
        <p><span class="label">Deskripsi Tindakan:</span> {{ $laporan->description_process }}</p>
        <p><span class="label">Tanggal:</span> {{ \Carbon\Carbon::parse($laporan->created_at)->format('d-m-Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="title">Status & Proses</div>
        <p><span class="label">Status:</span> {{ ucfirst($laporan->status) }}</p>
        <p><span class="label">Pelapor:</span> {{ $laporan->pelapor->name ?? '-' }}</p>
        <p><span class="label">Teknisi:</span> {{ $laporan->teknisi->name ?? '-' }}</p>
        {{-- Tambahkan lainnya sesuai kebutuhan --}}
    </div>

    @if ($laporan->foto_kerusakan)
    <div class="section">
        <div class="title">Foto Kerusakan</div>
        <img src="{{ public_path($laporan->foto_kerusakan) }}" class="img">
    </div>
    @endif

    @if ($laporan->foto_hasil)
    <div class="section">
        <div class="title">Foto Hasil</div>
        <img src="{{ public_path($laporan->foto_hasil) }}" class="img">
    </div>
    @endif
</body>
</html>
