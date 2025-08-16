<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas - SIFASMA</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendors/chartjs/Chart.min.css">
    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/favicon.svg" type="image/x-icon">
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
                            <h3>Fasilitas</h3>
                            <p class="text-subtitle text-muted">Manajemen Fasilitas.</p>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            Fasilitas
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
                                    data-bs-target="#createFasilitasModal">
                                    <i data-feather="plus"></i> Tambah Fasilitas
                                </button>
                                <!-- Search bar akan otomatis ditambahkan oleh Simple-DataTables -->
                            </div>
                            <table class='table table-striped' id="table1">
                                <thead>
                                    <tr>
                                        <th>Kode Fasilitas</th>
                                        <th>Nama</th>
                                        <th>Ruangan</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fasilitas as $fasilitasItem)
                                        <tr>
                                            <td>{{ $fasilitasItem->kode_fasilitas }}</td>
                                            <td>{{ $fasilitasItem->nama_fasilitas }}</td>
                                            <td>{{ $fasilitasItem->ruangan->nama_ruangan }}</td>
                                            <td>{{ $fasilitasItem->keterangan }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $fasilitasItem->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $fasilitasItem->status == 'Inactive' ? 'Inactive' : 'Active' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <button type="button" class="btn btn-sm btn-info me-3"
                                                        onclick="openEditModal('{{ $fasilitasItem->id }}','{{ $fasilitasItem->kode_fasilitas }}','{{ $fasilitasItem->nama_fasilitas }}','{{ $fasilitasItem->id_ruangan }}','{{ $fasilitasItem->keterangan }}','{{ $fasilitasItem->status }}')">
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
                <!-- Modal: Create Fasilitas -->
                <div class="modal fade" id="createFasilitasModal" tabindex="-1"
                    aria-labelledby="createFasilitasModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('fasilitas.store') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createFasilitasModalLabel">Tambah Fasilitas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Error Create --}}
                                    @if ($errors->create->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->create->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="kode_fasilitas" class="form-label">
                                            Kode Fasilitas <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="kode_fasilitas" class="form-control"
                                            value="{{ old('kode_fasilitas') }}" placeholder="Masukkan kode fasilitas"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_fasilitas" class="form-label">
                                            Nama Fasilitas <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama_fasilitas" class="form-control"
                                            value="{{ old('nama_fasilitas') }}" placeholder="Masukkan nama fasilitas"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_ruangan" class="form-label">
                                            Ruangan <span class="text-danger">*</span>
                                        </label>
                                        <select name="id_ruangan" class="form-control" required>
                                            <option value="" disabled {{ old('id_ruangan') ? '' : 'selected' }}>
                                                Pilih Ruangan</option>
                                            @foreach (\App\Models\Ruangan::where('status', 'Active')->get() as $ruangan)
                                                <option value="{{ $ruangan->id }}"
                                                    {{ old('id_ruangan') == $ruangan->id ? 'selected' : '' }}>
                                                    {{ $ruangan->nama_ruangan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="2"
                                            placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal: Edit Fasilitas -->
                <div class="modal fade" id="editFasilitasModal" tabindex="-1"
                    aria-labelledby="editFasilitasModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="editFasilitasForm" method="POST"
                            action="{{ session('edit_id') ? url('/fasilitas/update/' . session('edit_id')) : '' }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editFasilitasModalLabel">Edit Fasilitas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- Error Edit --}}
                                    @if ($errors->edit->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->edit->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="edit_kode_fasilitas" class="form-label">
                                            Kode Fasilitas <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="edit_kode_fasilitas" name="kode_fasilitas_display"
                                            class="form-control" value="{{ old('kode_fasilitas') }}" disabled
                                            placeholder="Kode otomatis">
                                        <input type="hidden" name="kode_fasilitas"
                                            value="{{ old('kode_fasilitas') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_nama_fasilitas" class="form-label">
                                            Nama Fasilitas <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nama_fasilitas" id="edit_nama_fasilitas"
                                            class="form-control" value="{{ old('nama_fasilitas') }}"
                                            placeholder="Masukkan nama fasilitas" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_id_ruangan" class="form-label">
                                            Ruangan <span class="text-danger">*</span>
                                        </label>
                                        <select name="id_ruangan" id="edit_id_ruangan" class="form-control" required>
                                            <option value="" disabled {{ old('id_ruangan') ? '' : 'selected' }}>
                                                Pilih Ruangan</option>
                                            @foreach (\App\Models\Ruangan::where('status', 'Active')->get() as $ruangan)
                                                <option value="{{ $ruangan->id }}"
                                                    {{ old('id_ruangan') == $ruangan->id ? 'selected' : '' }}>
                                                    {{ $ruangan->nama_ruangan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"
                                            placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_status" class="form-label">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="status" id="edit_status" class="form-control" required>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="Inactive"
                                                {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            @include('layouts.footer')
        </div>
    </div>
    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/vendors.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        function openEditModal(id, kode_fasilitas, nama_fasilitas, id_ruangan, keterangan, status) {
            // Isi value
            document.getElementById('edit_kode_fasilitas').value = kode_fasilitas;
            document.getElementById('edit_nama_fasilitas').value = nama_fasilitas;
            document.getElementById('edit_id_ruangan').value = id_ruangan;
            document.getElementById('edit_keterangan').value = keterangan;
            document.getElementById('edit_status').value = status;

            // Set action form
            document.getElementById('editFasilitasForm').action = `/fasilitas/update/${id}`;

            // Show modal
            var myModal = new bootstrap.Modal(document.getElementById('editFasilitasModal'));
            myModal.show();
        }
    </script>
    @if ($errors->create->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('createFasilitasModal')).show();
            });
        </script>
    @endif
</body>

</html>
