<div class="bg-dark text-white p-3" style="width:250px; min-height:100vh;">
    <h5>Menu</h5>
    <hr>

    <ul class="nav flex-column">
        @if (auth()->user()->isSuperadmin())
            <li class="nav-item">
                <a href="{{ url('superadmin') }}" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.index') }}" class="nav-link text-white">Kelola User</a>
            </li>
        @elseif (auth()->user()->isAdminHRD())
            <li class="nav-item">
                <a href="{{ url('adminhrd') }}" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.index') }}" class="nav-link text-white">Kelola User</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pegawai.index') }}" class="nav-link text-white">Data Pegawai</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('periode.index') }}" class="nav-link text-white">Absensi Pegawai</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('setting.index') }}" class="nav-link text-white">Setting Tunjangan</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tunjangan.index') }}" class="nav-link text-white">Tunjangan Transport</a>
            </li>
        @elseif (auth()->user()->isManagerHRD())
            <li class="nav-item">
                <a href="{{ route('index') }}" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.index') }}" class="nav-link text-white">Kelola User</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pegawai.index') }}" class="nav-link text-white">Data Pegawai</a>
            </li>
        @endif
    </ul>
</div>
