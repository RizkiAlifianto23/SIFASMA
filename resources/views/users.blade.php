<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - SIFASMA</title>

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
                            <h3>Pengguna</h3>
                            <p class="text-subtitle text-muted">Manajemen Pengguna.</p>
                        </div>
                    </div>
                </div>

                <!-- Table User -->
                <section class="section">
                    <div class="card">
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
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#createUserModal">
                                    <i data-feather="plus"></i>Tambah Pengguna
                                </a>
                            </div>

                            <table class='table table-striped' id="table1">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td><span class="badge bg-secondary">{{ $user->role->name_role }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <a href="#" class="btn btn-sm btn-info me-3"
                                                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                        data-email="{{ $user->email }}"
                                                        data-role-id="{{ $user->id_role }}"
                                                        data-status="{{ $user->status }}">
                                                        <i data-feather="edit"></i> Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </section>
                <!-- End Table User -->

                <!-- Modal Tambah User -->
                <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            Email <span class="text-danger">*</span>
                                        </label> <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="role_id" class="form-label">Peran<span
                                                class="text-danger">*</span></label>
                                        <select name="role_id"
                                            class="form-select @error('role_id') is-invalid @enderror" required>
                                            <option value="">-- Select Role --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name_role }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status<span
                                                class="text-danger">*</span></label>
                                        <select name="status"
                                            class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Modal -->
                <!-- Modal Edit User -->
                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="editUserForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit-name" class="form-label">
                                            Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" id="edit-name" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-email" class="form-label">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" id="edit-email" class="form-control"
                                            disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-password" class="form-label">
                                            Password <small class="text-muted">(Kosongkan jika tidak ingin
                                                mengubah)</small>
                                        </label>
                                        <input type="password" name="password" id="edit-password"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-role-id" class="form-label">Role <span
                                                class="text-danger">*</span></label>
                                        <select name="role_id" id="edit-role-id" class="form-select" required>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name_role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="edit-status" class="form-select" required>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
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

    <!-- Scripts -->
    <script src="/assets/js/feather-icons/feather.min.js"></script>
    <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/js/vendors.js"></script>
    <script src="/assets/js/main.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-open modal if validation error exists -->
    @if ($errors->any())
        <script>
            const createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));
            createUserModal.show();
        </script>
    @endif
    <script>
        const editUserModal = document.getElementById('editUserModal');
        editUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const roleId = button.getAttribute('data-role-id');
            const status = button.getAttribute('data-status');

            // Set values ke input
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-role-id').value = roleId;
            document.getElementById('edit-status').value = status;

            // Kosongkan password field saat modal dibuka
            document.getElementById('edit-password').value = '';

            // Set form action
            const form = document.getElementById('editUserForm');
            form.action = `/users/${id}`; // Sesuaikan route edit user di sini
        });
    </script>


</body>

</html>
