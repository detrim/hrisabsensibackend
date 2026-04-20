@extends('layouts.app')
@section('title', 'Tunjangan Transport')
@section('content')

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <p class="mb-0">
                <b>PERIODE :</b> {{ $data->nama_bulan }} {{ $data->tahun }}
            </p>
            <input type="text" id="search" data-id="{{ $data->id }}" data-bulan="{{ $data->bulan }}"
                class="form-control form-control-sm w-25" placeholder="Nama Pegawai...">
        </div>
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
            <table class="table " id="tunjanganBody">
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
                            <td style="width:290px;" class="text-end">
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
    @push('tunjangan')
        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                let id = $(this).attr('data-id');
                let bulan = $(this).attr('data-bulan');
                let urlTemplate = "{{ route('tunjangan.nama') }}";
                let keyword = $(this).val();
                let data = {
                    id: id,
                    bulan: bulan,
                    keyword: keyword,
                    _token: '{{ csrf_token() }}'
                };
                fetch(urlTemplate, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html =
                                `<tr><td colspan="6" class="text-center text-danger">Data tidak ditemukan</td></tr>`;
                        } else {
                            data.forEach((item, index) => {
                                let nama = item.pegawai?.nama ?? '-';
                                let jarak = item.jarak_km ?? 0;
                                let minimal = item.minimal_hari ?? 19;
                                let masuk = item.jumlah_hari_masuk ?? 0;
                                let tunjangan = item.total_tunjangan ?? 0;

                                let rupiah = new Intl.NumberFormat('id-ID').format(tunjangan);

                                html += `
            <tr>
                <td class="text-center" style="width: 52px;">${index + 1}</td>
                <td style="width: 366px;">${nama}</td>
                <td class="text-center" style="width: 105px;">${jarak} km</td>
                <td class="text-center" style="width: 99px;">${minimal}</td>
                <td class="text-center" style="width: 99px;">${masuk}</td>
                <td class="text-end" style="width: 260px;">Rp ${rupiah}</td>
            </tr>
        `;
                            });
                        }
                        document.getElementById('tunjanganBody').innerHTML = html;
                    });
            });
        </script>
    @endpush
@endsection
