@extends('layouts.app')
@section('title', 'Tunjangan Transport')
@section('content')

    <div class="container mt-4">
        <!-- Search -->
        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="search" class="form-control form-control-sm w-25" placeholder="Cari tahun...">
        </div>
        <!-- Table -->
        <table class="table mt-4">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 150px;">Tahun</th>
                    <th style="width: 250px;">Bulan</th>
                    <th style="width: 350px;">Tunjangan</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:450px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table table-striped">
                <tbody id="absensiTable1">
                    @forelse ($data as $key => $item)
                        <tr>
                            <td style="width: 50px;">{{ $key + 1 }}</td>
                            <td style="width: 150px;">{{ $item->tahun }}</td>
                            <td style="width: 250px;">{{ $item->nama_bulan }}</td>
                            <td style="width: 340px;">
                                <a href="{{ route('tunjangan.bulan.' . auth()->user()->role_name, [
                                    'bln' => $item->bulan,
                                    'thn' => $item->tahun,
                                    'id' => $item->id,
                                ]) }}"
                                    class="btn btn-sm  {{ $item->status == 1 ? 'btn-success' : 'btn-danger' }}">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row mt-3 align-items-center justify-content-between">
            <div class="col-auto">
                <small>
                    Showing {{ $data->firstItem() }}
                    to {{ $data->lastItem() }}
                    of {{ $data->total() }} results
                </small>
            </div>
            <div class="col-auto">
                <small>{{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}</small>
            </div>
        </div>
    </div>
    @php
        $role = auth()->user()->role_name;
    @endphp
    @push('tunjangan-index')
        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                let keyword = this.value;
                let searchUrl = "{{ route('tunjangan.search.' . $role) }}";
                let urlTemplate =
                    "{{ route('tunjangan.bulan.' . $role, ['bln' => ':bln', 'thn' => ':thn', 'id' => ':id']) }}";

                fetch(`${searchUrl}?keyword=${keyword}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        let tbody = document.getElementById('absensiTable1');
                        if (!tbody) return;

                        if (data.length === 0) {
                            html =
                                `<tr>
            <td colspan="4" class="text-center text-danger">Data tidak ditemukan</td>
        </tr>`;
                        } else {
                            data.forEach((item, index) => {
                                let url = urlTemplate.replace(':id', item.id);
                                let btnClass = item.status == 1 ? 'btn-success' : 'btn-danger';
                                html += `
        <tr>
            <td style="width: 50px;">${index + 1}</td>
            <td style="width: 150px;">${item.tahun}</td>
            <td style="width: 250px;">${item.nama_bulan}</td>
            <td style="width: 340px;">
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
