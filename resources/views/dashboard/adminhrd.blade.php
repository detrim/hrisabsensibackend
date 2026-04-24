<style>
    body {
        background: linear-gradient(135deg, #f5f7fb, #eef2f7);
    }

    /* Card */
    .card {
        border-radius: 14px;
        border: 1px solid #eef0f4;
        background: #ffffff;
    }

    .shadow-sm {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
    }

    /* KPI */
    .kpi-card {
        transition: all 0.25s ease;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    /* Icon */
    .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
</style>

<div class="container py-4">

    {{-- 🔷 HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">
            Selamat Datang {{ auth()->user()->name }} - {{ auth()->user()->role?->name }}
        </h4>
        <p class="text-muted mb-0">Ringkasan data pegawai hari ini</p>
    </div>

    {{-- 🔵 KPI --}}
    <div class="row g-3 mb-4">

        {{-- Total Pegawai --}}
        <div class="col-lg-3 col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-primary-subtle text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Pegawai</h6>
                        <h4 class="fw-bold mb-0">{{ $total_pegawai }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hadir --}}
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Hadir</h6>
                        <h4 class="fw-bold text-success mb-0">{{ $totalHariIni }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Izin --}}
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-info-subtle text-info">
                        <i class="bi bi-envelope-open"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Izin</h6>
                        <h4 class="fw-bold text-info mb-0">{{ $izin }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sakit --}}
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-danger-subtle text-danger">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Sakit</h6>
                        <h4 class="fw-bold text-danger mb-0">{{ $sakit }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cuti --}}
        <div class="col-lg-3 col-md-4 col-6">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon bg-warning-subtle text-warning">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Cuti</h6>
                        <h4 class="fw-bold text-warning mb-0">{{ $cuti }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- DETAIL --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>Data Pegawai
                    </h6>
                    <p class="mb-2">Pegawai Aktif: <strong>{{ $pegawai_aktif }}</strong></p>
                    <p class="mb-0">Nonaktif: <strong>{{ $pegawai_nonaktif }}</strong></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-cash-stack me-2 text-success"></i>Tunjangan
                    </h6>
                    <p class="mb-2">Total Bulan Ini:
                        <strong>Rp {{ number_format($tunjanganBulanIni, 0, ',', '.') }}</strong>
                    </p>
                    <p class="mb-0">Total Tahun Ini:
                        <strong>Rp {{ number_format($tunjanganTahunIni, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
