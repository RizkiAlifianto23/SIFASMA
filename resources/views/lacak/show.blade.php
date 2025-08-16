<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - Facility Building Management System</title>

    {{-- Styles --}}
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/gundarlogo.png" type="image/x-icon">

    {{-- Custom Style --}}
    <style>
        html,
        body {
            height: 100%;
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
        }

        .card-body p {
            margin-bottom: 1rem;
            color: #555;
        }

        .card-body strong {
            color: #333;
        }

        .img-fluid {
            max-height: 300px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')

        <div id="main">
            @include('layouts.navbar')

            <div class="main-content container-fluid">
                <div class="page-heading">
                    <h3>Detail Laporan</h3>
                </div>

                <div class="page-content">
                    <section class="row">
                        <div class="col-12">
                            <div class="card shadow-lg border-0">
                                <div class="card-body px-4 py-5">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h5 class="text-primary mb-3">Informasi Laporan</h5>

                                            <p><strong>Fasilitas:</strong><br>{{ $laporan->fasilitas->nama_fasilitas }}
                                            </p>
                                            <p><strong>Deskripsi
                                                    Kerusakan:</strong><br>{{ $laporan->deskripsi_kerusakan }}</p>

                                            <p><strong>Tanggal
                                                    Laporan:</strong></strong><br>{{ \Carbon\Carbon::parse($laporan->created_at)->format('F j, Y, g:i A') }}
                                            </p>

                                            @if ($laporan->deskripsi_perbaikan)
                                                <p><strong>Deskripsi
                                                        Perbaikan:</strong><br>{{ $laporan->deskripsi_perbaikan }}</p>
                                            @endif

                                            <p><strong>Status:</strong><br>
                                                <span
                                                    class="badge bg-{{ $laporan->status === 'tertunda'
                                                        ? 'warning'
                                                        : ($laporan->status === 'diterima'
                                                            ? 'success'
                                                            : ($laporan->status === 'ditolak'
                                                                ? 'danger'
                                                                : 'secondary')) }}">
                                                    <i class="bi bi-circle-fill me-1"></i>
                                                    {{ ucfirst($laporan->status) }}
                                                </span>
                                            </p>

                                            <hr>

                                            <p><strong>Pelapor:</strong> {{ $laporan->pelapor->name ?? '-' }}</p>
                                            <p><strong>Teknisi:</strong> {{ $laporan->teknisi->name ?? '-' }}</p>
                                            <p><strong>Dibuat oleh:</strong> {{ $laporan->creator->name ?? '-' }}</p>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <h5 class="text-primary mb-3">Proses Penanganan</h5>

                                            @if ($laporan->approved_at)
                                                <p><strong>Disetujui:</strong><br>
                                                    {{ $laporan->approver->name ?? '-' }} <br>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($laporan->approved_at)->format('F j, Y, g:i A') }}</small>
                                                </p>
                                            @endif

                                            @if ($laporan->rejected_at)
                                                <p><strong>Ditolak:</strong><br>
                                                    {{ $laporan->rejector->name ?? '-' }} <br>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($laporan->rejected_at)->format('F j, Y, g:i A') }}</small><br>
                                                    <strong>Alasan:</strong> {{ $laporan->rejected_reason }}
                                                </p>
                                            @endif

                                            @if ($laporan->processed_at)
                                                <p><strong>Diproses oleh:</strong><br>
                                                    {{ $laporan->processor->name ?? '-' }} <br>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($laporan->processed_at)->format('F j, Y, g:i A') }}</small>
                                                </p>
                                            @endif

                                            @if ($laporan->finished_at)
                                                <p><strong>Selesai oleh:</strong><br>
                                                    {{ $laporan->finisher->name ?? '-' }} <br>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($laporan->finished_at)->format('F j, Y, g:i A') }}</small>
                                                </p>
                                            @endif
                                            @if ($laporan->cancelled_at)
                                                <p><strong>Cancel oleh:</strong><br>
                                                    {{ $laporan->canceller->name ?? '-' }} <br>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($laporan->cancelled_at)->format('F j, Y, g:i A') }}</small>
                                                </p>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="row mt-4">
                                        <h5 class="text-primary mb-3">Foto Laporan</h5>
                                        @if ($laporan->foto_kerusakan || $laporan->foto_hasil)

                                            @if ($laporan->foto_kerusakan)
                                                <div class="col-md-6 mb-4">
                                                    <p><strong>Foto Kerusakan</strong></p>
                                                    <img src="{{ asset($laporan->foto_kerusakan) }}"
                                                        class="img-fluid rounded shadow-sm w-100" alt="Foto Kerusakan"
                                                        style="cursor: pointer"
                                                        onclick="showImageModal('{{ asset($laporan->foto_kerusakan) }}')">
                                                </div>
                                            @endif

                                            @if ($laporan->foto_hasil)
                                                <div class="col-md-6 mb-4">
                                                    <p><strong>Foto Hasil</strong></p>
                                                    <img src="{{ asset($laporan->foto_hasil) }}"
                                                        class="img-fluid rounded shadow-sm w-100" alt="Foto Hasil"
                                                        style="cursor: pointer"
                                                        onclick="showImageModal('{{ asset($laporan->foto_hasil) }}')">
                                                </div>
                                            @endif
                                    </div>
                                    <div class="mt-4 text-end d-flex gap-2 justify-content-end">
                                        @if ($laporan->status === 'selesai')
                                            <a href="{{ route('laporan.pdf', $laporan->id) }}"
                                                class="btn btn-outline-danger rounded-pill px-4">
                                                <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
                                            </a>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </section>

                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>
    <!-- Modal Lihat Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-body text-center position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 me-2 mt-2"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    <img id="modalImage" src="" alt="Detail Foto" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Process -->
    <div class="modal fade" id="finishModal" tabindex="-1" aria-labelledby="finishModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="finishForm" action="{{ route('teknisi.finish', ['id' => $laporan->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Process</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin <strong>Process</strong> laporan ini?</p>
                        <div class="mb-3">
                            <label for="foto_hasil" class="form-label">Upload Foto Hasil Perbaikan</label>
                            <input type="file" name="foto_hasil" id="foto_hasil" class="form-control"
                                accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Process</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal Process -->
    <div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="processModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi process</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin <strong>menyetujui</strong> laporan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a id="processConfirmBtn" href="#" class="btn btn-success">Ya, process</a>
                </div>
            </div>
        </div>
    </div>



    {{-- Scripts --}}
    <script src="/assets/js/feather-icons/feather.min.js"></script>
    <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/js/vendors.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        function showImageModal(imageUrl) {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }
    </script>
    <script>
        function showFinishModal(id) {
            const form = document.getElementById('finishForm');
            form.action = `/teknisi/laporan/${id}/finish`;
            new bootstrap.Modal(document.getElementById('finishModal')).show();
        }

        function showProcessModal(id) {
            document.getElementById('processConfirmBtn').href = `/teknisi/laporan/${id}/process`;
            new bootstrap.Modal(document.getElementById('processModal')).show();
        }
    </script>
</body>

</html>
