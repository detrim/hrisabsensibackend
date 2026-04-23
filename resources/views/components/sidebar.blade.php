<div class="bg-dark text-white p-3" style="width:250px; min-height:100vh;">
    <h5><i class="fa-solid fa-bars me-2"></i> Menu</h5>
    <hr>

    <ul class="nav flex-column">

        @if (auth()->user()->isSuperadmin())
            <li class="nav-item">
                <a href="{{ url('superadmin') }}"
                    class="nav-link text-white {{ request()->is('superadmin') ? 'bg-primary rounded' : '' }}">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.index') }}"
                    class="nav-link text-white {{ request()->routeIs('user.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-users me-2 "></i> Kelola
                    User
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('log.activity') }}"
                    class="nav-link text-white {{ request()->routeIs('log.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i> User Activity
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('online.index') }}"
                    class="nav-link text-white {{ request()->routeIs('online.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-circle text-success me-2"></i> User Online
                </a>
            </li>
        @elseif (auth()->user()->isAdminHRD())
            <li class="nav-item">
                <a href="{{ url('adminhrd') }}"
                    class="nav-link text-white {{ request()->is('adminhrd') ? 'bg-primary rounded' : '' }}">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.index') }}"
                    class="nav-link text-white {{ request()->routeIs('user.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> Kelola User
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pegawai.index') }}"
                    class="nav-link text-white {{ request()->routeIs('pegawai.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-id-card me-2"></i> Data Pegawai
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('periode.index') }}"
                    class="nav-link text-white {{ request()->routeIs('periode.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-calendar-check me-2"></i> Absensi Pegawai
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('setting.index') }}"
                    class="nav-link text-white {{ request()->routeIs('setting.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-gear me-2"></i> Setting Tunjangan
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tunjangan.index') }}"
                    class="nav-link text-white {{ request()->routeIs('tunjangan.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-money-bill-wave me-2"></i> Tunjangan Transport
                </a>
            </li>
        @elseif (auth()->user()->isManagerHRD())
            <li class="nav-item">
                <a href="{{ route('managerhrd') }}"
                    class="nav-link text-white {{ request()->is('superadmin') ? 'bg-primary rounded' : '' }}">
                    <i class="fa-solid fa-gauge me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.index') }}"
                    class="nav-link text-white {{ request()->routeIs('user.*') ? 'bg-primary' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> Kelola User
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pegawai.index') }}" class="nav-link text-white">
                    <i class="fa-solid fa-id-card me-2"></i> Data Pegawai
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('tunjangan.index') }}" class="nav-link text-white">
                    <i class="fa-solid fa-money-bill-wave me-2"></i> Tunjangan Transport
                </a>
            </li>
        @endif

    </ul>
</div>
