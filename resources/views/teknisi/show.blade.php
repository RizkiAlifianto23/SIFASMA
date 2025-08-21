<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - SIFASMA</title>

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

        @media print {
            body {
                margin: 100px;
                padding: 100px;
                zoom: 90%;
            }

            #laporanPDF {
                margin: 100px;
                padding: 1rem;
            }

            img {
                max-width: 100% !important;
                height: auto !important;
            }

            .main-content {
                padding-top: 120rem !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')

        <div id="main">
            @include('layouts.navbar')

            <div id="laporanPDF" class="main-content container-fluid">
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
                                            @if ($laporan->is_vendor)
                                                <p><strong>Butuh Vendor:</strong>
                                                    @if ($laporan->description_vendor)
                                                        <br><small>{{ $laporan->description_vendor }}</small>
                                                    @endif
                                            @endif
                                            </p>
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
                                                        class="text-muted">{{ \Carbon\Carbon::parse($laporan->processed_at)->format('F j, Y, g:i A') }}</small><br>
                                                    <strong>Deskripsi Tindakan:</strong>
                                                    {{ $laporan->description_process }}
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
                                                <img src="{{ route('show.image', ['filename' => basename($laporan->foto_kerusakan)]) }}"
                                                    class="img-fluid rounded shadow-sm w-100" alt="Foto Kerusakan"
                                                    style="cursor: pointer"
                                                    onclick="showImageModal('{{ route('show.image', ['filename' => basename($laporan->foto_kerusakan)]) }}')">
                                            </div>
                                        @endif

                                        @if ($laporan->foto_hasil)
                                            <div class="col-md-6 mb-4">
                                                <p><strong>Foto Hasil</strong></p>
                                                <img src="{{ route('show.image', ['filename' => basename($laporan->foto_hasil)]) }}"
                                                    class="img-fluid rounded shadow-sm w-100" alt="Foto Hasil"
                                                    style="cursor: pointer"
                                                    onclick="showImageModal('{{ route('show.image', ['filename' => basename($laporan->foto_hasil)]) }}')">
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                    <div class="mt-4 text-end d-flex gap-2 justify-content-end">
                                        @if ($laporan->status === 'tertunda')
                                            <button onclick="showVendorModal({{ $laporan->id }})"
                                                class="btn btn-warning rounded-pill px-4">
                                                <i class="bi bi-check-circle"></i> Butuh Vendor
                                            </button>
                                            <button onclick="showRejectModal({{ $laporan->id }})"
                                                class="btn btn-danger rounded-pill px-4">
                                                <i class="bi bi-check-circle"></i> Reject
                                            </button>
                                            <button onclick="showProcessModal({{ $laporan->id }})"
                                                class="btn btn-success rounded-pill px-4">
                                                <i class="bi bi-check-circle"></i> Process
                                            </button>
                                        @endif
                                        @if ($laporan->status === 'diterima')
                                            <button onclick="showProcessModal({{ $laporan->id }})"
                                                class="btn btn-success rounded-pill px-4">
                                                <i class="bi bi-check-circle"></i> Process
                                            </button>
                                        @endif
                                        @if ($laporan->status === 'diproses')
                                            <button onclick="showFinishModal({{ $laporan->id }})"
                                                class="btn btn-success rounded-pill px-4">
                                                <i class="bi bi-check-circle"></i> Selesaikan Process
                                            </button>
                                        @endif
                                        @if ($laporan->status === 'selesai')
                                            <a href="{{ route('laporan.pdf', $laporan->id) }}"
                                                class="btn btn-outline-danger rounded-pill px-4">
                                                <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
                                            </a>
                                        @endif
                                    </div>
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
    <!-- Modal Finish -->
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
                        <div class="mb-3">
                            <label for="tanggalFinish" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggalFinish" name="finished_at"
                                required>
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
                <form id="processForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Process</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin <strong>menyetujui</strong> laporan ini?</p>

                        <div class="mb-3">
                            <label for="tanggalPersetujuan" class="form-label">Tanggal Proses</label>
                            <input type="date" class="form-control" id="tanggalPersetujuan" name="processed_at"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsiTindakan" class="form-label">Deskripsi Tindakan</label>
                            <textarea class="form-control" id="deskripsiTindakan" name="description_process" rows="3"
                                placeholder="Tuliskan tindakan yang dilakukan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Process</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reject dengan alasan -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered ">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Penolakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin <strong>menolak</strong> laporan ini?</p>
                        <div class="mb-3">
                            <label for="rejected_reason" class="form-label">Alasan Penolakan</label>
                            <textarea name="rejected_reason" id="rejected_reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal vendor dengan alasan -->
    <div class="modal fade" id="vendorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered ">
            <form id="vendorForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda ingin <strong>mengajukan vendor untuk</strong> laporan ini?</p>
                        <div class="mb-3">
                            <label for="deskripsi_vendor" class="form-label">Deskripsi Pengajuan</label>
                            <textarea name="deskripsi_vendor" id="deskripsi_vendor" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Ajukan</button>
                    </div>
                </div>
            </form>
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
            const form = document.getElementById('processForm');
            form.action = `/teknisi/laporan/${id}/process`;
            new bootstrap.Modal(document.getElementById('processModal')).show();
        }

        function showRejectModal(id) {
            const rejectForm = document.getElementById('rejectForm');
            rejectForm.action = `/teknisi/laporan/${id}/reject`; // pastikan route ini ada
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        function showVendorModal(id) {
            const rejectForm = document.getElementById('vendorForm');
            rejectForm.action = `/teknisi/laporan/${id}/vendor`; // pastikan route ini ada
            new bootstrap.Modal(document.getElementById('vendorModal')).show();
        }
    </script>

    {{-- pdf maker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('laporanPDF');

            // Sembunyikan elemen no-print secara manual
            const noPrints = document.querySelectorAll('.no-print');
            noPrints.forEach(el => el.style.display = 'none');

            // Scroll ke elemen
            element.scrollIntoView();

            // Tunggu gambar selesai dimuat
            const images = element.querySelectorAll('img');
            const promises = Array.from(images).map(img => {
                if (img.complete) return Promise.resolve();
                return new Promise(resolve => img.onload = img.onerror = resolve);
            });

            Promise.all(promises).then(() => {
                window.scrollTo(0, 0);

                html2pdf()
                    .set({
                        margin: 0,
                        filename: 'laporan-detail.pdf',
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2,
                            scrollY: 0
                        },
                        jsPDF: {
                            unit: 'mm',
                            format: 'a4',
                            orientation: 'portrait'
                        }
                    })
                    .from(element)
                    .save()
                    .then(() => {
                        // Setelah PDF berhasil disimpan, tampilkan elemen kembali
                        noPrints.forEach(el => el.style.display = '');
                    });
            });
        }
    </script>


</body>

</html>
