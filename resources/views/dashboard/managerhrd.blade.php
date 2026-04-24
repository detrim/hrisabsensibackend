<style>
    .card {
        border-radius: 10px;
        border: 1px solid #eee;
    }

    .card-body {
        padding: 12px;
    }

    h6 {
        font-size: 13px;
        margin-bottom: 4px;
    }

    h4 {
        font-size: 18px;
        margin: 0;
    }

    /* 🔥 pembatas ukuran chart */
    .chart-box {
        height: 160px;
        position: relative;
    }
</style>

<div class="mb-4">
    <h4 class="fw-bold mb-1">
        Selamat Datang {{ auth()->user()->name }} - {{ auth()->user()->role?->name }}
    </h4>
    <p class="text-muted mb-0">Ringkasan data pegawai, status kepegawaian, dan distribusi gender</p>
</div>

{{-- WIDGET --}}
<div class="row g-2 mb-3">

    <div class="col-lg-3 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6>Total</h6>
                <h4>{{ $total_pegawai ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6>Kontrak</h6>
                <h4>{{ $pegawai_kontrak ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6>Tetap</h6>
                <h4>{{ $pegawai_tetap ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="card text-center">
            <div class="card-body">
                <h6>Magang</h6>
                <h4>{{ $pegawai_magang ?? 0 }}</h4>
            </div>
        </div>
    </div>

</div>

{{-- CHART --}}
<div class="row g-2 mb-3">

    <div class="col-md-6">
        <div class="card p-2">
            <h6 class="mb-2">Jenis Pegawai</h6>
            <div class="chart-box">
                <canvas id="chartJenis"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-2">
            <h6 class="mb-2">Gender</h6>
            <div class="chart-box">
                <canvas id="chartGender"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-body p-2">
        <h6 class="mb-2">Pegawai Kontrak Terbaru</h6>

        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Tgl Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawai_baru ?? [] as $p)
                        <tr>
                            <td>{{ $p->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td>{{ $p->status_pegawai }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    new Chart(document.getElementById('chartJenis'), {
        type: 'doughnut',
        data: {
            labels: ['Kontrak', 'Tetap', 'Magang'],
            datasets: [{
                data: [{{ $pegawai_kontrak ?? 0 }}, {{ $pegawai_tetap ?? 0 }},
                    {{ $pegawai_magang ?? 0 }}
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // 🔥 WAJIB
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    new Chart(document.getElementById('chartGender'), {
        type: 'doughnut',
        data: {
            labels: ['Pria', 'Wanita'],
            datasets: [{
                data: [{{ $laki_laki ?? 0 }}, {{ $perempuan ?? 0 }}]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // 🔥 WAJIB
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
