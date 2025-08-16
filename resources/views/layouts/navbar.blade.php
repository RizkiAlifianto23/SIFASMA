<nav class="navbar navbar-header navbar-expand navbar-light">
    <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
    <button class="btn navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
            <li class="dropdown nav-icon">
                <a href="#" data-bs-toggle="dropdown" class="nav-link  dropdown-toggle nav-link-lg nav-link-user">
                    <div class="d-lg-inline-block">
                        <i data-feather="bell"></i>
                        @if ($unreadNotificationCount > 0)
                            <span
                                class="badge bg-danger badge-number position-absolute top-0 start-100 translate-middle">
                                {{ $unreadNotificationCount }}
                            </span>
                        @endif
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-large p-0 shadow">
                    <div class="px-4 py-3 border-bottom bg-white">
                        <h6 class="mb-0">Notifikasi</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse ($notifications->where('is_read', false) as $notif)
                            @php
                                $role = auth()->user()->role->name_role;
                                $laporanId = $notif->laporan_id ?? ($notif->id_laporan ?? null);

                                $link = match ($role) {
                                    'admin' => url("/admin/laporan/{$laporanId}"),
                                    'teknisi' => url("/teknisi/laporan/{$laporanId}"),
                                    default => url("/laporan/{$laporanId}"),
                                };

                                $liClass = 'bg-success';
                            @endphp

                            <li class="list-group-item d-flex align-items-start gap-3 position-relative ">
                                <div class="avatar {{ $liClass }} flex-shrink-0">
                                    <span class="avatar-content">
                                        <i data-feather="bell"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $notif->title }}</h6>
                                    <p class="mb-0 text-xs">{{ $notif->message }}</p>
                                    <a href="{{ $link }}" class="stretched-link"></a>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">
                                Tidak ada notifikasi.
                            </li>
                        @endforelse
                    </ul>
                </div>

            </li>
        </ul>
    </div>
</nav>
<style>
    .dropdown-menu-large .list-group {
        max-height: 340px;
        overflow-y: auto;
        min-width: 320px;
    }
    @media (max-width: 600px) {
        .dropdown-menu-large .list-group {
            max-height: 220px;
            min-width: 200px;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- Dummy data untuk testing, hapus di production --}}
