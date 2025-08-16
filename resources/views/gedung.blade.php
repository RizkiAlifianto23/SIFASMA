<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gedung - SIFASMA</title>

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
                            <h3>Gedung</h3>
                            <p class="text-subtitle text-muted">Management Gedung.</p>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            Gedung
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
                                    data-bs-target="#createGedungModal">
                                    <i data-feather="plus"></i> Tambah Gedung
                                </button>
                                <!-- Search bar akan otomatis ditambahkan oleh Simple-DataTables -->
                            </div>
                            <table class='table table-striped' id="table1">
                                <thead>
                                    <tr>
                                        <th>Kode Gedung</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gedungs as $gedung)
                                        <tr>
                                            <td>{{ $gedung->kode_gedung }}</td>
                                            <td>{{ $gedung->nama_gedung }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $gedung->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $gedung->status == 'Inactive' ? 'Inactive' : 'Active' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <button type="button" class="btn btn-sm btn-info me-3"
                                                        onclick="openEditModal('{{ $gedung->id }}', '{{ $gedung->kode_gedung }}', '{{ $gedung->nama_gedung }}', '{{ $gedung->status }}')">
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
                <!-- Modal Create Gedung -->
                <div class="modal fade" id="createGedungModal" tabindex="-1" aria-labelledby="createGedungModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('gedung.store') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createGedungModalLabel">Tambah Gedung</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Kode Gedung --}}
                                    <div class="mb-3">
                                        <label for="kode_gedung" class="form-label">Kode Gedung <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="kode_gedung"
                                            class="form-control @error('kode_gedung', 'create') is-invalid @enderror"
                                            value="{{ old('kode_gedung') }}" required placeholder="Contoh: GD001">
                                        @error('kode_gedung', 'create')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Nama Gedung --}}
                                    <div class="mb-3">
                                        <label for="nama_gedung" class="form-label">Nama Gedung <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama_gedung"
                                            class="form-control @error('nama_gedung', 'create') is-invalid @enderror"
                                            value="{{ old('nama_gedung') }}" required
                                            placeholder="Contoh: Gedung Administrasi">
                                        @error('nama_gedung', 'create')
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
                <!-- End Modal Create Gedung -->
                <!-- Modal Edit Gedung -->
                <div class="modal fade" id="editGedungModal" tabindex="-1" aria-labelledby="editGedungModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="editGedungForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editGedungModalLabel">Edit Gedung</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Kode Gedung (disabled) --}}
                                    <div class="mb-3">
                                        <label class="form-label">Kode Gedung</label>
                                        <input type="text" id="edit_kode_gedung" class="form-control" disabled>
                                    </div>

                                    {{-- Nama Gedung --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nama Gedung <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama_gedung" id="edit_nama_gedung"
                                            class="form-control" required>
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
                <!-- End Modal Edit Gedung -->
            </div>
            @include('layouts.footer')
        </div>
    </div>
    <script src="/assets/js/feather-icons/feather.min.js"></script>
    <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/js/vendors.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script>
        function openEditModal(id, kode, nama, status) {
            const form = document.getElementById('editGedungForm');
            form.action = `/gedung/update/${id}`; // Sesuaikan route jika berbeda

            document.getElementById('edit_kode_gedung').value = kode;
            document.getElementById('edit_nama_gedung').value = nama;
            document.getElementById('edit_status').value = status;

            new bootstrap.Modal(document.getElementById('editGedungModal')).show();
        }
    </script>
    @if ($errors->create->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('createGedungModal'));
                modal.show();
            });
        </script>
    @endif

</body>

</html>
