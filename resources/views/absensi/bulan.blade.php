@extends('layouts.app')
@section('title', 'Absensi Pegawai')
@section('content')
    <style>
        /* Jarak atas (dropdown show entries) */
        .dataTables_wrapper .dataTables_length {
            margin-bottom: 15px;
        }

        /* Jarak search */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }

        /* Jarak bawah tabel ke pagination */
        .dataTables_wrapper .dataTables_info {
            margin-top: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 10px;
        }
    </style>
    <div class="container mt-3 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <p class="mb-0">
                <b>PERIODE :</b> {{ $data->nama_bulan }} {{ $data->tahun }}
            </p>
            @php
                $disabled = $data->status == 1 ? 'disabled' : '';
            @endphp
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalHari" {{ $disabled }}>
                + Tambah Hari
            </button>
        </div>
        @include('session.session')
        <table class="table table-bordered w-100 mt-3 mb-3">
            <thead class="table-dark align-middle">
                <tr>
                    {{-- <th style="50px;" class="text-center">No</th> --}}
                    <th class="text-center" style="width: 100px;">Tanggal</th>
                    <th class="text-center" style="width:100px;">Hari</th>
                    <th class="text-center" style="width:100px;">Total Pegawai Bulan Ini</th>
                    <th style="width:200px;" class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:450px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table w-100 ">
                <thead class="table-dark align-middle">
                    <tr>
                        {{-- <th style="50px;"></th> --}}
                        <th style="width: 100px;"></th>
                        <th style="width:100px;"></th>
                        <th style="width:100px;"></th>
                        <th style="width:200px;"></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($dataHari as $d)
                        <tr>
                            {{-- <td style="width:30px;">{{ $loop->iteration }}</td> --}}
                            <td style="width:100px;">{{ $d['tanggal'] }}</td>
                            <td style="width:100px;">{{ $d['hari'] }}</td>
                            <td style="width:100px;">{{ $d['total'] }}</td>
                            <td style="width:200px;">
                                <button class="btn
                            btn-warning btn-sm btn-edit"
                                    {{ $disabled }} data-tanggal="{{ $d['tanggal'] }}" data-id="{{ $data->id }}"
                                    data-bs-toggle="modal" data-bs-target="#modalHari">
                                    Edit
                                </button>
                                <a href="{{ route('absensi.hari', [
                                    'tgl' => $d['tanggal'],
                                    'bln' => $data->bulan,
                                    'thn' => $data->tahun,
                                    'id' => $data->id,
                                ]) }}"
                                    class="btn btn-info btn-sm">
                                    View
                                </a>
                                <form action="{{ route('periode.hari.hapus') }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="periode_id" value="{{ $data->id }}">
                                    <input type="hidden" name="tanggal" value="{{ $d['tanggal'] }}">
                                    <button type="submit" class="btn btn-danger btn-sm" {{ $disabled }}
                                        onclick="return confirm('Yakin hapus tanggal ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modalHari" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('periode.hari') }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Hari</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $data->bulan, $data->tahun);
                        @endphp
                        <div class="row">
                            <!-- HARI -->
                            <div class="col-md-4">
                                <label>Hari</label>
                                <div class="dropdown hari-wrapper">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                        type="button" data-bs-toggle="dropdown">
                                        <span class="selected-hari">{{ $hariIni }}</span>
                                    </button>

                                    <ul class="dropdown-menu w-100" style="max-height: 200px; overflow-y: auto;">
                                        @for ($i = 1; $i <= $jumlahHari; $i++)
                                            <li>
                                                <a class="dropdown-item hari-option {{ $i == $hariIni ? 'active' : '' }}"
                                                    href="#" data-value="{{ $i }}">
                                                    {{ $i }}
                                                </a>
                                            </li>
                                        @endfor
                                    </ul>
                                    <input type="hidden" name="hari" class="hari-input" value="{{ $hariIni }}">
                                </div>
                            </div>
                            <!-- BULAN -->
                            <div class="col-md-4">
                                <label>Bulan</label>
                                <input type="text" class="form-control" value="{{ $data->nama_bulan }}" disabled>
                            </div>
                            <!-- TAHUN -->
                            <div class="col-md-4">
                                <label>Tahun</label>
                                <input type="text" class="form-control" value="{{ $data->tahun }}" disabled>
                            </div>
                        </div>
                        <input type="hidden" name="periode_id" value="{{ $data->id }}" id="periode_id">
                        <input type="hidden" name="tanggal_lama" id="tgl_lama">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('bulan')
        <script>
            $(document).ready(function() {
                $('#hariTable').DataTable({
                    language: {
                        emptyTable: "Data kosong"
                    },
                    paging: false,
                    lengthMenu: [10, 20, 30],
                    autoWidth: false,
                    responsive: true,
                    searching: false,
                    info: false,
                    ordering: true
                });
            });

            document.querySelectorAll('.hari-option').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();

                    let wrapper = this.closest('.hari-wrapper');
                    wrapper.querySelector('.selected-hari').textContent = this.textContent;
                    wrapper.querySelector('.hari-input').value = this.dataset.value;
                });
            });


            document.querySelectorAll('.btn-edit').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    let hari = this.getAttribute('data-tanggal');
                    let id = this.getAttribute('data-id');
                    // set ke input hidden
                    document.querySelector('#periode_id').value = id;
                    document.querySelector('#tgl_lama').value = hari;
                    document.querySelector('.hari-input').value = hari;
                    // tampilkan di tombol dropdown
                    document.querySelector('.selected-hari').innerText = hari;
                    // highlight active
                    document.querySelectorAll('.hari-option').forEach(function(el) {
                        el.classList.remove('active');
                        if (el.getAttribute('data-value') == hari) {
                            el.classList.add('active');
                        }
                    });

                });
            });
        </script>
    @endpush
@endsection
