<div id="sidebar" class='active'>
    <div class="sidebar-wrapper active">
        <div class="sidebar-header text-center">
            <img src="{{ asset('assets/images/logo-SIFASMA.png') }}" alt="" style="width: 100%; height: 100%;"
                class="mx-auto d-block">
        </div>

        <div class="sidebar-menu">
            <div class="card shadow-sm bg-white border border-3 rounded-3 mx-3 mb-3" style="border-color: #231f40;">
                <div class="card-body p-3 text-center">
                    <i data-feather="user" class="text-secondary mb-2" width="24"></i>
                    <div class="fw-semibold text-dark" style="font-size: 1rem;">
                        {{ auth()->user()->name ?? '-' }}
                    </div>
                    <div class="text-primary mb-2" style="font-size: 0.875rem;">
                        {{ auth()->user()->role->name_role ?? '-' }}
                    </div>
                    <i data-feather="calendar" class="text-muted mb-1" width="18"></i>
                    <div class="text-muted mb-2" style="font-size: 0.75rem;">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i data-feather="log-out"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>

            @php
                $role = auth()->user()->role->name_role;
            @endphp

            <ul class="menu">
            
                @if ($role === 'admin' || $role === 'teknisi')
                    <li class='sidebar-title'>Menu Utama</li>
                     <li class="sidebar-item {{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ url('/') }}" class='sidebar-link'>
                            <i data-feather="home" width="20"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->is('dashboard') || request()->is('dashboard/*') ? 'active' : '' }}">
                        <a href="{{ url('/dashboard') }}" class='sidebar-link'>
                            <i data-feather="layers" width="20"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                {{-- OB --}}
                @if ($role === 'ob'|| $role === 'teknisi')
                    <li class='sidebar-title'>Petugas Lapangan</li>
                    <li
                        class="sidebar-item {{ request()->is('laporan') || request()->is('laporan/*') ? 'active' : '' }}">
                        <a href="{{ url('/laporan') }}" class='sidebar-link'>
                            <i data-feather="alert-circle" width="20"></i>
                            <span>Laporan Kerusakan</span>
                        </a>
                    </li>
                @endif
                @if ($role === 'teknisi')
                    <li class='sidebar-title'>Petugas Teknisi</li>

                    <li
                        class="sidebar-item {{ request()->is('teknisi/dashboard') || request()->is('teknisi/laporan/*') ? 'active' : '' }}">
                        <a href="{{ url('/teknisi/dashboard') }}" class='sidebar-link'>
                            <i data-feather="tool" width="20"></i>
                            <span>Proses Perbaikan</span>
                        </a>
                    </li>
                @endif
                {{-- Admin --}}
                @if ($role === 'admin')
                    <li class='sidebar-title'>Admin</li>

                    <li
                        class="sidebar-item {{ request()->is('admin/dashboard') || request()->is('admin/laporan/*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/dashboard') }}" class='sidebar-link'>
                            <i data-feather="alert-circle" width="20"></i>
                            <span>Validasi Kerusakan</span>
                        </a>
                    </li>
                @endif

                @if ($role === 'superadmin')
                    <li class='sidebar-title'>Setting</li>

                    <li class="sidebar-item {{ request()->is('users') ? 'active' : '' }}">
                        <a href="{{ url('/users') }}" class='sidebar-link'>
                            <i data-feather="users" width="20"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('fasilitas') ? 'active' : '' }}">
                        <a href="{{ url('/fasilitas') }}" class='sidebar-link'>
                            <i data-feather="package" width="20"></i>
                            <span>Fasilitas</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('gedung') ? 'active' : '' }}">
                        <a href="{{ url('/gedung') }}" class='sidebar-link'>
                            <i data-feather="home" width="20"></i>
                            <span>Gedung</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('lantai') ? 'active' : '' }}">
                        <a href="{{ url('/lantai') }}" class='sidebar-link'>
                            <i data-feather="layers" width="20"></i>
                            <span>Lantai</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->is('ruangan') ? 'active' : '' }}">
                        <a href="{{ url('/ruangan') }}" class='sidebar-link'>
                            <i data-feather="grid" width="20"></i>
                            <span>Ruangan</span>
                        </a>
                    </li>
                @endif

            </ul>

        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
