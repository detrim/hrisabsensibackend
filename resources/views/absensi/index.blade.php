@extends('layouts.app')
@section('title', 'Absensi Pegawai')
@section('content')

    <div class="container mt-4">

        <!-- Search + Button -->
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Absensi
            </button>
            <input type="text" id="search" class="form-control form-control-sm w-25" placeholder="Cari tahun...">
        </div>
        @include('session.session')


        <!-- Table -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th>TAHUN</th>
                    <th>BULAN</th>
                    <th>ABSENSI</th>
                    <th>Keterangan</th>
                    <th style="width: 100px;">AKSI</th>
                </tr>
            </thead>
            <tbody id="absensiTable">
                @forelse ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ $item->nama_bulan }}</td>
                        <td>
                            <a href="{{ route('periode.bulan', $item->id) }}"
                                class="btn btn-sm  {{ $item->status == 1 ? 'btn-danger' : 'btn-success' }}">View</a>
                        </td>
                        <td style="width: 200px;">
                            <form method="POST" action="{{ route('periode.update.status', $item->id) }}">
                                @csrf

                                <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                                    <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Aktif</option>
                                    <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Close</option>
                                </select>
                            </form>
                        <td>
                            <button class="btn btn-warning btn-sm btn-edit" data-title="Update Absensi"
                                data-id="{{ $item->id }}" data-nama-bulan="{{ $item->nama_bulan }}"
                                data-tahun="{{ $item->tahun }}" data-bulan="{{ $item->bulan }}"
                                data-status="{{ $item->status }}" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                Update
                            </button>
                        </td>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('periode.store') }}" method="POST" id="formPeriode">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>
                    <div class="modal-body">
                        <!-- TAHUN -->
                        <div class="mb-3">
                            <label>Tahun</label>
                            <div class="dropdown tahun-wrapper">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start tahun-btn"
                                    type="button" data-bs-toggle="dropdown">
                                    <span class="selected-tahun">Pilih Tahun</span>
                                </button>
                                <ul class="dropdown-menu w-100 tahun-dropdown-menu"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @for ($i = date('Y'); $i >= 2000; $i--)
                                        <li>
                                            <a class="dropdown-item tahun-option" href="#"
                                                data-value="{{ $i }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endfor
                                </ul>
                                <input type="hidden" name="id" id="periode_id">
                                <input type="hidden" name="tahun" class="tahun-input">
                            </div>
                        </div>

                        <!-- BULAN -->
                        <div class="mb-3">
                            <label>Bulan</label>
                            <div class="dropdown bulan-wrapper">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start bulan-btn"
                                    type="button" data-bs-toggle="dropdown">
                                    <span class="selected-bulan">Pilih Bulan</span>
                                </button>
                                <ul class="dropdown-menu w-100 bulan-dropdown-menu"
                                    style="max-height: 200px; overflow-y: auto;">

                                    @foreach ($bulan as $key => $b)
                                        <li>
                                            <a class="dropdown-item bulan-option" href="#"
                                                data-value="{{ $key }}">
                                                {{ $b }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="bulan" class="bulan-input">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <!-- Script -->
    @push('absensi-index')
        <script>
            const modal = document.getElementById('modalTambah');
            modal.addEventListener('hide.bs.modal', function() {
                document.activeElement?.blur();
            });
            modal.addEventListener('hidden.bs.modal', function() {
                document.body.focus();
            });
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-edit')) {

                    let button = e.target.closest('.btn-edit');
                    let id = button.dataset.id;
                    let url = "{{ route('periode.update', ':id') }}";
                    url = url.replace(':id', id);
                    let tahun = button.dataset.tahun;
                    let bulan = button.dataset.bulan;
                    let status = button.dataset.status;
                    let title = button.dataset.title;
                    let namaBulan = button.dataset.namaBulan;
                    document.getElementById('periode_id').value = id;
                    document.querySelector('.modal-title').textContent = title;
                    document.querySelector('.selected-tahun').textContent = tahun;
                    document.querySelector('.tahun-input').value = tahun;
                    document.querySelector('.selected-bulan').textContent = namaBulan;
                    document.querySelector('.bulan-input').value = bulan;
                    let statusSelect = document.querySelector('.status-edit');
                    if (statusSelect) statusSelect.value = status;
                    document.getElementById('formPeriode').action = url;
                    let modal = bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('modalTambah')
                    );
                    modal.show();
                }
            });


            document.addEventListener('DOMContentLoaded', function() {
                // TAHUN
                document.querySelectorAll('.tahun-option').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();

                        let wrapper = this.closest('.tahun-wrapper');
                        wrapper.querySelector('.selected-tahun').textContent = this.dataset.value;
                        wrapper.querySelector('.tahun-input').value = this.dataset.value;
                    });
                });

                // BULAN
                document.querySelectorAll('.bulan-option').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();

                        let wrapper = this.closest('.bulan-wrapper');
                        wrapper.querySelector('.selected-bulan').textContent = this.textContent;
                        wrapper.querySelector('.bulan-input').value = this.dataset.value;
                    });
                });

            });

            document.getElementById('search').addEventListener('keyup', function() {
                let keyword = this.value;
                let searchUrl = "{{ route('periode.search') }}";
                let urlTemplate = "{{ route('periode.bulan', ':id') }}";
                let urlTemplatestatus = "{{ route('periode.update.status', ':id') }}";

                fetch(`${searchUrl}?keyword=${keyword}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        let tbody = document.getElementById('absensiTable');
                        if (!tbody) return;

                        if (data.length === 0) {
                            html =
                                `<tr><td colspan="6" class="text-center text-danger">Data tidak ditemukan</td></tr>`;
                        } else {
                            data.forEach((item, index) => {
                                let url = urlTemplate.replace(':id', item.id);
                                let urlst = urlTemplatestatus.replace(':id', item.id);
                                let btnClass = item.status == 1 ? 'btn-danger' : 'btn-success';
                                html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.tahun}</td>
                            <td>${item.nama_bulan}</td>
                            <td>
                                <a href="${url}" class="btn ${btnClass} btn-sm">View</a>
                            </td>
                            <td style="width: 200px;">
                               <form method="POST" action="${urlst}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <select class="form-select form-select-sm" name="status"
                                        onchange="this.form.submit()">
                                        <option value="1" ${item.status == 1 ? 'selected' : ''}>Close</option>
                                        <option value="0" ${item.status == 0 ? 'selected' : ''}>Aktif</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit"
                                    data-title="Update Absensi"
                                    data-id="${item.id}"
                                    data-nama-bulan="${item.nama_bulan}"
                                    data-tahun="${item.tahun}"
                                    data-bulan="${item.bulan}"
                                    data-status="${item.status}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTambah">
                                    Update
                                </button>
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
