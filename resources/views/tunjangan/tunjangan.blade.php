@extends('layouts.app')
@section('title', 'Tunjangan Transport')
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
        <p><b>PERIODE :</b> {{ $data->nama_bulan }} {{ $data->tahun }}</p>
        @include('session.session')
        <table class="table table-bordered">
            <thead class="table-dark text-center align-middle">
                <tr>
                    <th rowspan="2" style="width:50px;">No</th>
                    <th colspan="1" style="width: 350px;">Nama</th>
                    <th rowspan="2" style="width:100px;">Jarak</th>
                    <th colspan="2" style="width:50px;">Total Hari</th>
                    <th rowspan="2" style="width: 290px;">Total Tunjangan</th>
                </tr>
                <tr>
                    <th>Pegawai Tetap</th>
                    <th style="width:95px;">Minimal</th>
                    <th style="width:95px;">Absensi</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:450px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table " id="pegawaiBody">
                <tbody>
                    @foreach ($tunjangan as $index => $item)
                        <tr>
                            <td style="width: 52px;" class="text-center">{{ $index + 1 }}</td>
                            <td style="width: 366px;">
                                {{ $item->pegawai->nama ?? '-' }}
                            </td>
                            <td style="width: 105px;" class="text-center">
                                {{ $item->jarak_km ?? 0 }} km
                            </td>
                            <td style="width: 99px;" class="text-center">
                                {{ $item->minimal_hari ?? 19 }}
                            </td>
                            <td style="width: 99px;" class="text-center">
                                {{ $item->jumlah_hari_masuk ?? 0 }}
                            </td>
                            <td style="width:290px;">
                                Rp {{ number_format($item->total_tunjangan, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-2 align-items-center justify-content-between">
            <div class="col-auto">
                {{ $tunjangan->links('pagination::bootstrap-5') }}
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
