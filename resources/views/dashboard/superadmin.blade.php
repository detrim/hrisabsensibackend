<style>
    body {
        background: linear-gradient(135deg, #f5f7fb, #eef2f7);
    }

    .card {
        border-radius: 14px;
        border: 1px solid #eef0f4;
    }

    .kpi-card {
        transition: all 0.25s ease;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container py-4">
    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">
            Super Admin Dashboard
        </h4>
        <p class="text-muted mb-0">Kontrol sistem & monitoring aktivitas pengguna</p>
    </div>
    {{-- KPI --}}
    <div class="row g-3 mb-4">

        {{-- Total User --}}
        <div class="col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-primary-subtle text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total User</h6>
                        <h4 class="fw-bold mb-0">{{ $totalUser }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Online --}}
        <div class="col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-success-subtle text-success">
                        <i class="bi bi-wifi"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">User Online</h6>
                        <h4 class="fw-bold text-success mb-0">{{ $userOnline }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Log Hari Ini --}}
        <div class="col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-warning-subtle text-warning">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Aktivitas Hari Ini</h6>
                        <h4 class="fw-bold text-warning mb-0">{{ $logHariIni }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- MENU CEPAT --}}
    <div class="row g-4">

        {{-- Kelola User --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-person-gear fs-1 text-primary"></i>
                    <h6 class="mt-3 fw-semibold">Kelola User</h6>
                    <p class="text-muted small">Tambah, edit, dan atur hak akses user</p>
                    <a href="{{ route('user.index.super') }}" class="btn btn-primary btn-sm">
                        Kelola
                    </a>
                </div>
            </div>
        </div>

        {{-- Log Activity --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history fs-1 text-warning"></i>
                    <h6 class="mt-3 fw-semibold">Log Activity</h6>
                    <p class="text-muted small">Lihat aktivitas user dalam sistem</p>
                    <a href="{{ route('log.activity') }}" class="btn btn-warning btn-sm">
                        Lihat Log
                    </a>
                </div>
            </div>
        </div>

        {{-- User Online --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-broadcast fs-1 text-success"></i>
                    <h6 class="mt-3 fw-semibold">User Online</h6>
                    <p class="text-muted small">Monitoring user aktif saat ini</p>
                    <a href="{{ route('online.index') }}" class="btn btn-success btn-sm">
                        Monitoring
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
