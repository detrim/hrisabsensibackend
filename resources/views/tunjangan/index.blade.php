@extends('layouts.app')
@section('title', 'Tunjangan Pegawai')
@section('content')

    <div class="container mt-4">
        <!-- Search -->
        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="search" class="form-control form-control-sm w-25" placeholder="Cari tahun...">
        </div>
        <!-- Table -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th>TAHUN</th>
                    <th>BULAN</th>
                    <th>ABSENSI</th>
                </tr>
            </thead>
            <tbody id="absensiTable1">
                @forelse ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ $item->nama_bulan }}</td>
                        <td>
                            <a href="{{ route('periode.bulan', $item->id) }}"
                                class="btn btn-sm  {{ $item->status == 1 ? 'btn-danger' : 'btn-success' }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-danger">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="row mt-2 align-items-center justify-content-end">
            <div class="col-auto">
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    @push('tunjangan-index')
        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                let keyword = this.value;
                let searchUrl = "{{ route('tunjangan.search') }}";
                let urlTemplate = "{{ route('tunjangan.bulan', ':id') }}";

                fetch(`${searchUrl}?keyword=${keyword}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        let tbody = document.getElementById('absensiTable1');
                        if (!tbody) return;

                        if (data.length === 0) {
                            html =
                                `<tr><td colspan="4" class="text-center text-danger">Data tidak ditemukan</td></tr>`;
                        } else {
                            data.forEach((item, index) => {
                                let url = urlTemplate.replace(':id', item.id);
                                let btnClass = item.status == 1 ? 'btn-success' : 'btn-danger';
                                html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.tahun}</td>
                            <td>${item.nama_bulan}</td>
                            <td>
                                <a href="${url}" class="btn ${btnClass} btn-sm">View</a>
                            </td>
                        </tr>
                    `;
                            });
                        }

                        tbody.innerHTML = html;
                    });
            });
        </script>
    @endpush
@endsection
