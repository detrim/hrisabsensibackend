@extends('layouts.app')
@section('title', 'Absensi')
@section('content')
    <style>
        .table-scroll {
            width: 100%;
            table-layout: fixed;
        }

        .table-scroll thead,
        .table-scroll tbody tr {
            display: table;
            width: 99%;
            table-layout: fixed;
        }

        .table-scroll tbody {
            display: block;
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
    <div class="container mt-4">
        <div class="d-flex justify-content-end mb-3">
            <input type="text" id="search" data-id="{{ $data->id }}" data-bulan="{{ $data->bulan }}"
                class="form-control form-control-sm w-25" placeholder="Nama Pegawai...">
        </div>
        <p><b>PERIODE :</b>{{ $data->nama_bulan }} {{ $data->tahun }}</p>
        @include('session.session')
        <table class="table table-bordered">
            <thead class="table-dark text-center align-middle">
                <tr>
                    <th rowspan="2" style="width:50px;">No</th>
                    <th rowspan="2" style="width: 350px;">Nama</th>
                    <th rowspan="2" style="width:100px;">Jarak</th>
                    <th colspan="2" style="width:50px;">Total Hari</th>
                    <th rowspan="2" style="width: 275px;">Total Tunjangan</th>
                    <th rowspan="2" style="width: 5px;"></th>
                </tr>
                <tr>
                    <th style="width:95px;">Minimal</th>
                    <th style="width:95px;">Absensi</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:400px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table table-bordered" id="pegawaiBody">
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="row mt-2 align-items-center justify-content-between">
            <div class="col-auto">
                {{-- {{ $pegawai->links('pagination::bootstrap-5') }} --}}
            </div>
            <div class="col-auto">
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                    ← Back
                </a>
            </div>
        </div>
    </div>
    @push('tunjangan-bulan')
    @endpush
@endsection
