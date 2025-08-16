<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - SIFASMA</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/simple-datatables/style.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/gundarlogo.png" type="image/x-icon">
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main">
            @include('layouts.navbar')

            <div class="main-content container-fluid">
                @if (session('success'))
                    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
                        <div id="successToast" class="toast align-items-center text-light border-0 show bg-success"
                            role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body ">
                                    <i data-feather="check-circle"style="color: white"></i> <span
                                        style="color: white">{{ session('success') }}</span>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                    data-bs-dismiss="toast" aria-label="Close" style="color: white"></button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Laporan</h3>
                            <p class="text-subtitle text-muted">Management Laporan.</p>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mt-3 ms-3">Laporan Kerusakan</h4>
                        </div>
                        <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
                            {{-- Kiri: Export Buttons --}}
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-danger" onclick="exportTableToPDF()">PDF</button>
                                <button class="btn btn-primary" onclick="exportTableToExcel()">Excel</button>
                            </div>

                            {{-- Kanan: Tambah --}}
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLaporanModal">
                                <i data-feather="plus"></i> Tambah Laporan
                            </button>
                            <form method="GET" action="{{ url('/laporan') }}" class="w-100">
                                <div class="row g-2 align-items-end">
                                    {{-- Filter Status --}}
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select"
                                            onchange="this.form.submit()">
                                            <option value="">-- Semua Status --</option>
                                            <option value="tertunda"
                                                {{ request('status') == 'tertunda' ? 'selected' : '' }}>Tertunda
                                            </option>
                                            <option value="diterima"
                                                {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima
                                            </option>
                                            <option value="ditolak"
                                                {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="dibatalkan"
                                                {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                                            </option>
                                            <option value="diproses"
                                                {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses
                                            </option>
                                            <option value="selesai"
                                                {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>

                                    {{-- Filter Gedung --}}
                                    <div class="col-md-3">
                                        <label for="gedung_filter" class="form-label">Gedung</label>
                                        <select name="gedung_filter" id="gedung_filter" class="form-select"
                                            onchange="this.form.submit()">
                                            <option value="">-- Semua Gedung --</option>
                                            @foreach ($gedungs as $gedung)
                                                <option value="{{ $gedung->id }}"
                                                    {{ request('gedung_filter') == $gedung->id ? 'selected' : '' }}>
                                                    {{ $gedung->nama_gedung }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Filter Lantai --}}
                                    <div class="col-md-3">
                                        <label for="lantai_filter" class="form-label">Lantai</label>
                                        <select name="lantai_filter" id="lantai_filter" class="form-select"
                                            onchange="this.form.submit()">
                                            <option value="">-- Semua Lantai --</option>
                                            @foreach ($lantais as $lantai)
                                                <option value="{{ $lantai->id }}"
                                                    {{ request('lantai_filter') == $lantai->id ? 'selected' : '' }}>
                                                    {{ $lantai->nama_lantai }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Pencarian --}}
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Cari Fasilitas</label>
                                        <div class="input-group">
                                            <input type="text" name="search" id="search"
                                                value="{{ request('search') }}" class="form-control"
                                                placeholder="Nama/Kode fasilitas">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                        </div>
                                    </div>
                                </div>
                            </form>


                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="laporanTable">
                                    <thead>
                                        <tr>
                                            <th>Fasilitas</th>
                                            <th>Deskripsi Kerusakan</th>
                                            <th>Gedung</th>
                                            <th>Lantai</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Pelapor</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($laporan as $item)
                                            <tr>
                                                <td>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</td>
                                                <td>{{ $item->deskripsi_kerusakan }}</td>
                                                <td>{{ $item->fasilitas->ruangan->lantai->gedung->nama_gedung ?? '-' }}
                                                </td>
                                                <td>{{ $item->fasilitas->ruangan->lantai->nama_lantai ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y, g:i A') }}
                                                </td>
                                                <td>{{ $item->creator->name ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $status = $item->status;
                                                        $badgeClass = match ($status) {
                                                            'tertunda' => 'bg-warning text-dark',
                                                            'diterima' => 'bg-primary',
                                                            'ditolak' => 'bg-danger',
                                                            'dibatalkan' => 'bg-secondary',
                                                            'diproses' => 'bg-info text-dark',
                                                            'selesai' => 'bg-success',
                                                            default => 'bg-light text-dark',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            Opsi
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ url('laporan/' . $item->id) }}">
                                                                    <i data-feather="eye"></i> Detail
                                                                </a>
                                                            </li>
                                                            @php
                                                                $isCreator = auth()->id() === $item->creator->id;
                                                            @endphp

                                                            @if ($isCreator && $item->status === 'tertunda')
                                                                <li>
                                                                    <button class="dropdown-item"
                                                                        onclick="openEditModal({{ $item->id }})">
                                                                        <i data-feather="edit"></i> Edit
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item text-danger"
                                                                        onclick="openCancelModal({{ $item->id }})">
                                                                        <i data-feather="x"></i> Cancel
                                                                    </button>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
                                    {{-- Info halaman --}}
                                    <div>
                                        Menampilkan {{ $laporan->firstItem() }} - {{ $laporan->lastItem() }} dari
                                        total
                                        {{ $laporan->total() }} laporan
                                    </div>

                                    {{-- Tombol pagination --}}
                                    <div>
                                        {{ $laporan->withQueryString()->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                {{-- Modal: Create Laporan --}}
                <div class="modal fade" id="createLaporanModal" tabindex="-1"
                    aria-labelledby="createLaporanModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Laporan Kerusakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    {{-- Error Bag untuk Create --}}
                                    @if ($errors->hasBag('create') && $errors->create->any())
                                        <div class="alert alert-danger rounded-3">
                                            <ul class="mb-0">
                                                @foreach ($errors->create->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {{-- Gedung --}}
                                    <div class="mb-3">
                                        <label for="gedung" class="form-label">Gedung <span
                                                class="text-danger">*</span></label>
                                        <select id="gedung" class="form-select" required>
                                            <option value="">-- Pilih Gedung --</option>
                                            @foreach (\App\Models\Gedung::where('status', 'Active')->get() as $gedung)
                                                <option value="{{ $gedung->id }}">{{ $gedung->nama_gedung }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Lantai --}}
                                    <div class="mb-3">
                                        <label for="lantai" class="form-label">Lantai <span
                                                class="text-danger">*</span></label>
                                        <select id="lantai" class="form-select" required disabled>
                                            <option value="">-- Pilih Lantai --</option>
                                        </select>
                                    </div>

                                    {{-- Ruangan --}}
                                    <div class="mb-3">
                                        <label for="ruangan" class="form-label">Ruangan <span
                                                class="text-danger">*</span></label>
                                        <select id="ruangan" class="form-select" required disabled>
                                            <option value="">-- Pilih Ruangan --</option>
                                        </select>
                                    </div>

                                    {{-- Fasilitas --}}
                                    <div class="mb-3">
                                        <label for="id_fasilitas" class="form-label">Fasilitas <span
                                                class="text-danger">*</span></label>
                                        <select name="id_fasilitas" id="id_fasilitas" class="form-select" required
                                            disabled>
                                            <option value="">-- Pilih Fasilitas --</option>
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label for="deskripsi_kerusakan" class="form-label">Deskripsi Kerusakan <span
                                                class="text-danger">*</span></label>
                                        <textarea name="deskripsi_kerusakan" class="form-control" placeholder="Masukkan deskripsi kerusakan..." required>{{ old('deskripsi_kerusakan') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="foto_kerusakan" class="form-label">Foto Kerusakan</label>
                                        <input type="file" name="foto_kerusakan" class="form-control"
                                            accept="image/*">
                                        <small class="text-muted">Format: jpg, jpeg, png. Maks: 2MB</small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal: Edit Laporan --}}
                <div class="modal fade" id="editLaporanModal" tabindex="-1" aria-labelledby="editLaporanModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" id="editLaporanForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Laporan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    {{-- Error Bag untuk Edit --}}
                                    @if ($errors->hasBag('edit') && $errors->edit->any())
                                        <div class="alert alert-danger rounded-3">
                                            <ul class="mb-0">
                                                @foreach ($errors->edit->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    {{-- Gedung --}}
                                    <div class="mb-3">
                                        <label for="edit_gedung" class="form-label">Gedung <span
                                                class="text-danger">*</span></label>
                                        <select id="edit_gedung" class="form-select" required>
                                            <option value="">-- Pilih Gedung --</option>
                                            @foreach (\App\Models\Gedung::all() as $gedung)
                                                <option value="{{ $gedung->id }}">{{ $gedung->nama_gedung }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Lantai --}}
                                    <div class="mb-3">
                                        <label for="edit_lantai" class="form-label">Lantai <span
                                                class="text-danger">*</span></label>
                                        <select id="edit_lantai" class="form-select" required disabled>
                                            <option value="">-- Pilih Lantai --</option>
                                        </select>
                                    </div>

                                    {{-- Ruangan --}}
                                    <div class="mb-3">
                                        <label for="edit_ruangan" class="form-label">Ruangan <span
                                                class="text-danger">*</span></label>
                                        <select id="edit_ruangan" class="form-select" required disabled>
                                            <option value="">-- Pilih Ruangan --</option>
                                        </select>
                                    </div>

                                    {{-- Fasilitas --}}
                                    <div class="mb-3">
                                        <label for="edit_id_fasilitas" class="form-label">Fasilitas <span
                                                class="text-danger">*</span></label>
                                        <select name="id_fasilitas" id="edit_id_fasilitas" class="form-select"
                                            required disabled>
                                            <option value="">-- Pilih Fasilitas --</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_deskripsi_kerusakan" class="form-label">Deskripsi Kerusakan
                                            <span class="text-danger">*</span></label>
                                        <textarea name="deskripsi_kerusakan" id="edit_deskripsi_kerusakan" class="form-control"
                                            placeholder="Masukkan deskripsi kerusakan..." required>{{ old('deskripsi_kerusakan') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_foto_kerusakan" class="form-label">Foto Baru
                                            (Opsional)</label>
                                        <input type="file" name="foto_kerusakan" class="form-control"
                                            accept="image/*">
                                        <small class="text-muted">Format: jpg, jpeg, png. Maks: 2MB</small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal: Cancel Laporan --}}
                <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin <strong>membatalkan</strong> laporan ini?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                                <a id="cancelConfirmBtn" href="#" class="btn btn-danger">Ya, Batalkan</a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            @include('layouts.footer')
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
        function openEditLaporanModal(id, idFasilitas, deskripsi) {
            document.getElementById('edit_id_fasilitas').value = idFasilitas;
            document.getElementById('edit_deskripsi_kerusakan').value = deskripsi;
            document.getElementById('editLaporanForm').action = `/laporan/${id}`;
            var modal = new bootstrap.Modal(document.getElementById('editLaporanModal'));
            modal.show();
        }

        feather.replace(); // refresh feather icons
    </script>

    @if ($errors->create->any() && session('error_modal') === 'create')
        <script>
            new bootstrap.Modal(document.getElementById('createLaporanModal')).show();
        </script>
    @endif

    @if ($errors->edit->any() && session('error_modal') === 'edit')
        <script>
            new bootstrap.Modal(document.getElementById('editLaporanModal')).show();
        </script>
    @endif

    <script>
        // Filter Lantai berdasarkan Gedung
        document.getElementById('gedung').addEventListener('change', function() {
            const gedungId = this.value;
            fetch(`/get-lantai/${gedungId}`)
                .then(res => res.json())
                .then(data => {
                    let lantai = document.getElementById('lantai');
                    lantai.innerHTML = '<option value="">-- Pilih Lantai --</option>';
                    data.forEach(item => {
                        lantai.innerHTML += `<option value="${item.id}">${item.nama_lantai}</option>`;
                    });
                    lantai.disabled = false;

                    // Reset bawahnya
                    document.getElementById('ruangan').innerHTML =
                        '<option value="">-- Pilih Ruangan --</option>';
                    document.getElementById('ruangan').disabled = true;
                    document.getElementById('id_fasilitas').innerHTML =
                        '<option value="">-- Pilih Fasilitas --</option>';
                    document.getElementById('id_fasilitas').disabled = true;
                });
        });

        // Filter Ruangan berdasarkan Lantai
        document.getElementById('lantai').addEventListener('change', function() {
            const lantaiId = this.value;
            fetch(`/get-ruangan/${lantaiId}`)
                .then(res => res.json())
                .then(data => {
                    let ruangan = document.getElementById('ruangan');
                    ruangan.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
                    data.forEach(item => {
                        ruangan.innerHTML += `<option value="${item.id}">${item.nama_ruangan}</option>`;
                    });
                    ruangan.disabled = false;

                    // Reset fasilitas
                    document.getElementById('id_fasilitas').innerHTML =
                        '<option value="">-- Pilih Fasilitas --</option>';
                    document.getElementById('id_fasilitas').disabled = true;
                });
        });

        // Filter Fasilitas berdasarkan Ruangan
        document.getElementById('ruangan').addEventListener('change', function() {
            const ruanganId = this.value;
            fetch(`/get-fasilitas/${ruanganId}`)
                .then(res => res.json())
                .then(data => {
                    let fasilitas = document.getElementById('id_fasilitas');
                    fasilitas.innerHTML = '<option value="">-- Pilih Fasilitas --</option>';
                    data.forEach(item => {
                        fasilitas.innerHTML +=
                            `<option value="${item.id}">${item.nama_fasilitas}</option>`;
                    });
                    fasilitas.disabled = false;
                });
        });
    </script>

    <script>
        function openEditModal(laporanId) {
            fetch(`/laporan/${laporanId}/edit-data`)
                .then(res => res.json())
                .then(data => {
                    // Set form action
                    document.getElementById('editLaporanForm').action = `/laporan/update/${laporanId}`;

                    // Dropdown
                    const gedungSelect = document.getElementById('edit_gedung');
                    const lantaiSelect = document.getElementById('edit_lantai');
                    const ruanganSelect = document.getElementById('edit_ruangan');
                    const fasilitasSelect = document.getElementById('edit_id_fasilitas');

                    // Kosongkan & isi gedung
                    gedungSelect.innerHTML = `<option value="">-- Pilih Gedung --</option>`;
                    data.gedungs.forEach(g => {
                        gedungSelect.innerHTML +=
                            `<option value="${g.id}" ${g.id == data.selected_gedung ? 'selected' : ''}>${g.nama}</option>`;
                    });
                    gedungSelect.disabled = false;

                    // Kosongkan & isi lantai
                    lantaiSelect.innerHTML = `<option value="">-- Pilih Lantai --</option>`;
                    data.lantais.forEach(l => {
                        lantaiSelect.innerHTML +=
                            `<option value="${l.id}" ${l.id == data.selected_lantai ? 'selected' : ''}>${l.nama}</option>`;
                    });
                    lantaiSelect.disabled = false;

                    // Kosongkan & isi ruangan
                    ruanganSelect.innerHTML = `<option value="">-- Pilih Ruangan --</option>`;
                    data.ruangans.forEach(r => {
                        ruanganSelect.innerHTML +=
                            `<option value="${r.id}" ${r.id == data.selected_ruangan ? 'selected' : ''}>${r.nama}</option>`;
                    });
                    ruanganSelect.disabled = false;

                    // Kosongkan & isi fasilitas
                    fasilitasSelect.innerHTML = `<option value="">-- Pilih Fasilitas --</option>`;
                    data.fasilitass.forEach(f => {
                        fasilitasSelect.innerHTML +=
                            `<option value="${f.id}" ${f.id == data.selected_fasilitas ? 'selected' : ''}>${f.nama}</option>`;
                    });
                    fasilitasSelect.disabled = false;

                    // Isi deskripsi
                    document.getElementById('edit_deskripsi_kerusakan').value = data.deskripsi_kerusakan;

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editLaporanModal'));
                    modal.show();
                });
        }

        // === Tambahkan ini di bawah ===

        document.getElementById('edit_gedung').addEventListener('change', function() {
            const gedungId = this.value;
            fetch(`/get-lantai/${gedungId}`)
                .then(res => res.json())
                .then(data => {
                    const lantaiSelect = document.getElementById('edit_lantai');
                    lantaiSelect.innerHTML = '<option value="">-- Pilih Lantai --</option>';
                    data.forEach(item => {
                        lantaiSelect.innerHTML +=
                            `<option value="${item.id}">${item.nama_lantai}</option>`;
                    });
                    lantaiSelect.disabled = false;

                    // Reset yang di bawahnya
                    document.getElementById('edit_ruangan').innerHTML =
                        '<option value="">-- Pilih Ruangan --</option>';
                    document.getElementById('edit_ruangan').disabled = true;
                    document.getElementById('edit_id_fasilitas').innerHTML =
                        '<option value="">-- Pilih Fasilitas --</option>';
                    document.getElementById('edit_id_fasilitas').disabled = true;
                });
        });

        document.getElementById('edit_lantai').addEventListener('change', function() {
            const lantaiId = this.value;
            fetch(`/get-ruangan/${lantaiId}`)
                .then(res => res.json())
                .then(data => {
                    const ruanganSelect = document.getElementById('edit_ruangan');
                    ruanganSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
                    data.forEach(item => {
                        ruanganSelect.innerHTML +=
                            `<option value="${item.id}">${item.nama_ruangan}</option>`;
                    });
                    ruanganSelect.disabled = false;

                    // Reset fasilitas
                    document.getElementById('edit_id_fasilitas').innerHTML =
                        '<option value="">-- Pilih Fasilitas --</option>';
                    document.getElementById('edit_id_fasilitas').disabled = true;
                });
        });

        document.getElementById('edit_ruangan').addEventListener('change', function() {
            const ruanganId = this.value;
            fetch(`/get-fasilitas/${ruanganId}`)
                .then(res => res.json())
                .then(data => {
                    const fasilitasSelect = document.getElementById('edit_id_fasilitas');
                    fasilitasSelect.innerHTML = '<option value="">-- Pilih Fasilitas --</option>';
                    data.forEach(item => {
                        fasilitasSelect.innerHTML +=
                            `<option value="${item.id}">${item.nama_fasilitas}</option>`;
                    });
                    fasilitasSelect.disabled = false;
                });
        });
    </script>
    {{-- pdf maker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script>
        function exportTableToCSV() {
            let csv = [];
            const rows = document.querySelectorAll("table tr");
            for (let row of rows) {
                let cols = row.querySelectorAll("td, th");
                let rowData = Array.from(cols)
                    .slice(0, -1) // abaikan kolom terakhir (Aksi)
                    .map(col => `"${col.innerText.trim()}"`);
                csv.push(rowData.join(","));
            }
            const csvString = csv.join("\n");
            const blob = new Blob([csvString], {
                type: "text/csv;charset=utf-8;"
            });
            saveAs(blob, "laporan.csv");
        }


        function exportTableToExcel() {
            let table = document.getElementById("laporanTable");

            // Clone table & hapus kolom terakhir
            let clone = table.cloneNode(true);
            clone.querySelectorAll("tr").forEach(tr => tr.removeChild(tr.lastElementChild));

            let wb = XLSX.utils.table_to_book(clone, {
                sheet: "Laporan"
            });
            XLSX.writeFile(wb, "laporan.xlsx");
        }

        function exportTableToPDF() {
            let doc = {
                content: []
            };
            const table = document.querySelector("#laporanTable");
            const headers = Array.from(table.querySelectorAll("thead th"))
                .slice(0, -1) // abaikan "Aksi"
                .map(th => th.innerText);

            const body = [headers];

            table.querySelectorAll("tbody tr").forEach(tr => {
                const row = Array.from(tr.querySelectorAll("td"))
                    .slice(0, -1) // abaikan "Aksi"
                    .map(td => td.innerText);
                body.push(row);
            });

            doc.content.push({
                table: {
                    headerRows: 1,
                    widths: Array(headers.length).fill("*"),
                    body: body
                }
            });

            pdfMake.createPdf(doc).download("laporan.pdf");
        }
    </script>

    {{-- cancel laporan --}}
    <script>
        function openCancelModal(id) {
            const url = "{{ url('/laporan') }}/" + id + "/cancel";
            document.getElementById('cancelConfirmBtn').setAttribute('href', url);
            const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
            modal.show();
        }
    </script>

    {{-- close toast --}}
    <script>
        feather.replace();

        setTimeout(() => {
            const toastEl = document.getElementById('successToast');
            if (toastEl) {
                const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
                toast.hide();
            }
        }, 2000); // hilang setelah 4 detik
    </script>
</body>

</html>
