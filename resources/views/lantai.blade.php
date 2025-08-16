<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantai - SIFASMA</title>

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
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Lantai</h3>
                            <p class="text-subtitle text-muted">Manajemen Lantai.</p>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            Lantai
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mb-3">
                                <!-- Tombol Create -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createLantaiModal">
                                    <i data-feather="plus"></i> Tambah Lantai
                                </button>

                                <!-- Search bar akan otomatis ditambahkan oleh Simple-DataTables -->
                            </div>
                            <table class='table table-striped' id="table1">
                                <thead>
                                    <tr>
                                        <th>Kode Lantai</th>
                                        <th>Nama</th>
                                        <th>Gedung</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lantai as $lantaiItem)
                                        <tr>
                                            <td>{{ $lantaiItem->kode_lantai }}</td>
                                            <td>{{ $lantaiItem->nama_lantai }}</td>
                                            <td>{{ $lantaiItem->gedung->nama_gedung }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $lantaiItem->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $lantaiItem->status == 'Inactive' ? 'Inactive' : 'Active' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <button type="button" class="btn btn-sm btn-info me-3"
                                                        onclick="openEditModal('{{ $lantaiItem->id }}', '{{ $lantaiItem->kode_lantai }}', '{{ $lantaiItem->nama_lantai }}', '{{ $lantaiItem->id_gedung }}', '{{ $lantaiItem->status }}')">
                                                        <i data-feather="edit"></i> Edit
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
                <!-- Modal Create Lantai -->
                <div class="modal fade" id="createLantaiModal" tabindex="-1" aria-labelledby="createLantaiModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('lantai.store') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Lantai</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Kode Lantai --}}
                                    <div class="mb-3">
                                        <label class="form-label">Kode Lantai <span class="text-danger">*</span></label>
                                        <input type="text" name="kode_lantai" value="{{ old('kode_lantai') }}"
                                            class="form-control @error('kode_lantai', 'create') is-invalid @enderror"
                                            required pattern="[A-Z0-9]+" placeholder="Contoh: LT1, B1, P2">
                                        @error('kode_lantai', 'create')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Nama Lantai --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lantai <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_lantai" value="{{ old('nama_lantai') }}"
                                            class="form-control @error('nama_lantai', 'create') is-invalid @enderror"
                                            required placeholder="Masukkan nama lantai">
                                        @error('nama_lantai', 'create')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Gedung --}}
                                    <div class="mb-3">
                                        <label class="form-label">Gedung <span class="text-danger">*</span></label>
                                        <select name="id_gedung"
                                            class="form-select @error('id_gedung', 'create') is-invalid @enderror"
                                            required>
                                            <option value="">-- Pilih Gedung --</option>
                                            @foreach (\App\Models\Gedung::where('status', 'Active')->get() as $gedung)
                                                <option value="{{ $gedung->id }}"
                                                    {{ old('id_gedung') == $gedung->id ? 'selected' : '' }}>
                                                    {{ $gedung->nama_gedung }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_gedung', 'create')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Modal Create -->
                <!-- Modal Edit Lantai -->
                <div class="modal fade" id="editLantaiModal" tabindex="-1" aria-labelledby="editLantaiModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="editLantaiForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Lantai</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Kode Lantai (Disabled) --}}
                                    <div class="mb-3">
                                        <label class="form-label">Kode Lantai</label>
                                        <input type="text" id="edit_kode_lantai" class="form-control" disabled>
                                    </div>

                                    {{-- Nama Lantai --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lantai <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama_lantai" id="edit_nama_lantai"
                                            class="form-control" placeholder="Masukkan nama lantai" required>
                                    </div>

                                    {{-- Gedung --}}
                                    <div class="mb-3">
                                        <label class="form-label">Gedung <span class="text-danger">*</span></label>
                                        <select name="id_gedung" id="edit_id_gedung" class="form-select" required>
                                            @foreach (\App\Models\Gedung::where('status', 'Active')->get() as $gedung)
                                                <option value="{{ $gedung->id }}"
                                                    {{ old('id_gedung') == $gedung->id ? 'selected' : '' }}>
                                                    {{ $gedung->nama_gedung }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Status --}}
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="edit_status" class="form-select" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Perbarui</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Modal Edit -->

            </div>
            @include('layouts.footer')
        </div>
    </div>
    <script src="/assets/js/feather-icons/feather.min.js"></script>
    <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/app.js"></script>

    <script src="/assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/js/vendors.js"></script>

    <script src="/assets/js/main.js"></script>
    <script>
        function openEditModal(id, kode, nama, idGedung, status) {
            const form = document.getElementById('editLantaiForm');
            form.action = `/lantai/update/${id}`;

            document.getElementById('edit_kode_lantai').value = kode;
            document.getElementById('edit_nama_lantai').value = nama;
            document.getElementById('edit_id_gedung').value = idGedung;
            document.getElementById('edit_status').value = status;

            new bootstrap.Modal(document.getElementById('editLantaiModal')).show();
        }
    </script>
    @if ($errors->create->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('createLantaiModal')).show();
            });
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


</body>

</html>
