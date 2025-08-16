<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruangan - SIFASMA</title>

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
                            <h3>Ruangan</h3>
                            <p class="text-subtitle text-muted">Manajemen Ruangan.</p>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            Ruangan
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
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createRuanganModal">
                                    <i data-feather="plus"></i>Tambah Ruangan
                                </button>
                                <!-- Search bar akan otomatis ditambahkan oleh Simple-DataTables -->
                            </div>
                            <table class='table table-striped' id="table1">
                                <thead>
                                    <tr>
                                        <th>Kode Ruangan</th>
                                        <th>Ruangan</th>
                                        <th>Lantai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ruangan as $ruanganItem)
                                        <tr>
                                            <td>{{ $ruanganItem->kode_ruangan }}</td>
                                            <td>{{ $ruanganItem->nama_ruangan }}</td>
                                            <td>{{ $ruanganItem->lantai->nama_lantai }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $ruanganItem->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $ruanganItem->status == 'Inactive' ? 'Inactive' : 'Active' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <button type="button" class="btn btn-sm btn-info me-3"
                                                        onclick="openEditModal('{{ $ruanganItem->id }}','{{ $ruanganItem->kode_ruangan }}','{{ $ruanganItem->nama_ruangan }}','{{ $ruanganItem->id_lantai }}','{{ $ruanganItem->status }}')">
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
                <!-- Modal Create Ruangan -->
                <div class="modal fade" id="createRuanganModal" tabindex="-1" aria-labelledby="createRuanganModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('ruangan.store') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createRuanganModalLabel">Tambah Ruangan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Kode Ruangan --}}
                                    <div class="mb-3">
                                        <label class="form-label">Kode Ruangan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="kode_ruangan" placeholder="Contoh: R101"
                                            value="{{ old('kode_ruangan') }}"
                                            class="form-control @error('kode_ruangan', 'create') is-invalid @enderror"
                                            required pattern="[A-Z0-9]+">
                                        @error('kode_ruangan', 'create')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Nama Ruangan --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nama Ruangan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama_ruangan" placeholder="Nama ruangan"
                                            value="{{ old('nama_ruangan') }}"
                                            class="form-control @error('nama_ruangan', 'create') is-invalid @enderror"
                                            required>
                                        @error('nama_ruangan', 'create')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Pilih Lantai --}}
                                    <div class="mb-3">
                                        <label class="form-label">Lantai <span class="text-danger">*</span></label>
                                        <select name="id_lantai"
                                            class="form-select @error('id_lantai', 'create') is-invalid @enderror"
                                            required>
                                            <option value="">-- Pilih Lantai --</option>
                                            @foreach (\App\Models\Lantai::where('status', 'Active')->get() as $lantai)
                                                <option value="{{ $lantai->id }}"
                                                    {{ old('id_lantai') == $lantai->id ? 'selected' : '' }}>
                                                    {{ $lantai->nama_lantai }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_lantai', 'create')
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
                <!-- End Modal Create Ruangan -->

                <!-- Modal Update Ruangan -->
                <div class="modal fade" id="editRuanganModal" tabindex="-1" aria-labelledby="editRuanganModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="editRuanganForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editRuanganModalLabel">Edit Ruangan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Kode Ruangan --}}
                                    <div class="mb-3">
                                        <label class="form-label">Kode Ruangan</label>
                                        <input type="text" id="edit_kode_ruangan" class="form-control" disabled>
                                    </div>

                                    {{-- Nama Ruangan --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nama Ruangan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nama_ruangan" id="edit_nama_ruangan"
                                            class="form-control" required>
                                    </div>

                                    {{-- Lantai --}}
                                    <div class="mb-3">
                                        <label class="form-label">Lantai <span class="text-danger">*</span></label>
                                        <select name="id_lantai" id="edit_id_lantai" class="form-select" required>
                                            @foreach (\App\Models\Lantai::where('status', 'Active')->get() as $lantai)
                                                <option value="{{ $lantai->id }}">{{ $lantai->nama_lantai }}
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
                <!-- End Modal Update Ruangan -->

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
        function openEditModal(id, kode, nama, idLantai, status) {
            const form = document.getElementById('editRuanganForm');
            form.action = `/ruangan/update/${id}`; // Ganti sesuai route update kamu

            document.getElementById('edit_kode_ruangan').value = kode;
            document.getElementById('edit_nama_ruangan').value = nama;
            document.getElementById('edit_id_lantai').value = idLantai;
            document.getElementById('edit_status').value = status;

            const modal = new bootstrap.Modal(document.getElementById('editRuanganModal'));
            modal.show();
        }
    </script>

    @if ($errors->create->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('createRuanganModal'));
                modal.show();
            });
        </script>
    @endif

</body>

</html>
