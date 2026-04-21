<nav class="navbar navbar-light bg-light shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">@yield('title')</span>

        <div class="d-flex align-items-center gap-3">
            <span>
                {{ auth()->user()->role?->name }}
            </span>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirmLogout(event, this)">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>
@push('navbar')
    <script>
        function confirmLogout(event, form) {
            event.preventDefault();

            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Anda akan keluar dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return false;
        }
    </script>
@endpush
