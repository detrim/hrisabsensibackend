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
        <div class="d-flex justify-content-between mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('lokasi.index') }}">
                + Lokasi
            </a>
            <input type="text" id="search" data-id="{{ $data->id }}" data-bulan="{{ $data->bulan }}"
                data-tgl="{{ $tgl }}" class="form-control form-control-sm w-25" placeholder="Nama Pegawai...">
        </div>
        <p><b>PERIODE :</b> {{ $hari }}, {{ $tgl }} {{ $data->nama_bulan }} {{ $data->tahun }}</p>
        @include('session.session')
        <table class="table table-bordered">
            <thead class="table-dark text-center align-middle">
                <tr>
                    <th rowspan="2" style="width:50px;">NO</th>
                    <th rowspan="2" style="width: 350px;">NAMA</th>
                    <th rowspan="2" style="width:170px;">STATUS PEGAWAI</th>
                    <th colspan="2" style="width:50px;">JAM</th>
                    <th rowspan="2" style="width: 275px;">KETERANGAN</th>
                </tr>
                <tr>
                    <th style="width:95px;">08:00</th>
                    <th style="width:95px;">17:00</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:400px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table table-bordered" id="pegawaiBody">
                <tbody>
                    @forelse  ($pegawai as $key => $p)
                        @php
                            $pagi = $p->absensi->first()->pagi ?? 0;
                            $sore = $p->absensi->first()->sore ?? 0;
                            $ket = $p->absensi->first()->keterangan ?? 0;
                            $disabled = $data->status == 1 ? 'disabled' : '';
                        @endphp
                        <tr>
                            <td class="text-center" style="width: 52px;">{{ $loop->iteration }}</td>
                            <td style="width: 366px;">{{ $p->nama }}</td>
                            <td class="text-center" style="width:178px;">
                                {{ ucfirst($p->status_pegawai) }}
                            </td>
                            <td class="text-center" style="width:99px;">
                                <input type="checkbox" class="absen-checkbox" data-nip="{{ $p->nip }}"
                                    data-id="{{ $data->id }}" data-bulan="{{ $data->bulan }}"
                                    data-tgl="{{ $tgl }}" data-jenis="pagi"
                                    {{ ($pagi ?? 0) == 1 ? 'checked' : '' }} {{ $disabled }}>
                            </td>

                            <td class="text-center" style="width:99px;">
                                <input type="checkbox" class="absen-checkbox" data-nip="{{ $p->nip }}"
                                    data-id="{{ $data->id }}" data-bulan="{{ $data->bulan }}"
                                    data-tgl="{{ $tgl }}" data-jenis="sore"
                                    {{ ($sore ?? 0) == 1 ? 'checked' : '' }} {{ $disabled }}>
                            </td>
                            <td style="width: 267px;">
                                <select name="keterangan[{{ $p->id }}]"
                                    class="form-control form-control-sm keterangan-select" data-nip="{{ $p->nip }}"
                                    data-id="{{ $data->id }}" data-bulan="{{ $data->bulan }}"
                                    data-tgl="{{ $tgl }}" data-jenis="ket" {{ $disabled }}>
                                    <option value="">Kosong</option>
                                    <option value="sakit" {{ $ket == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="cuti" {{ $ket == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="izin" {{ $ket == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="alpha" {{ $ket == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                </select>
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
        <div class="row mt-2 align-items-center justify-content-between">
            <div class="col-auto">
                {{ $pegawai->links('pagination::bootstrap-5') }}
            </div>
            <div class="col-auto">
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                    ← Back
                </a>
            </div>
        </div>
    </div>
    <div id="notif" class="alert d-none position-fixed top-0 end-0 m-3 shadow" style="z-index:9999; min-width:250px;">
    </div>
    @push('absensi')
        <script>
            function showNotif(message, type = 'success') {
                const notif = document.getElementById('notif');

                notif.className = `alert alert-${type} position-fixed top-0 end-0 m-3 shadow`;
                notif.style.zIndex = 9999;
                notif.innerText = message;
                notif.classList.remove('d-none');

                setTimeout(() => {
                    notif.classList.add('d-none');
                }, 3000);
            }
            document.getElementById('search').addEventListener('keyup', function() {
                let id = $(this).attr('data-id');
                let tgl = $(this).attr('data-tgl');
                let bulan = $(this).attr('data-bulan');
                let keyword = $(this).val();
                let data = {
                    id: id,
                    tgl: tgl,
                    bulan: bulan,
                    keyword: keyword,
                    _token: '{{ csrf_token() }}'
                };
                let searchUrl = "{{ route('absensi.search') }}";
                fetch(searchUrl, {
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
                            data.forEach((p, index) => {
                                let nip = p.absensi?.[0]?.pegawai_nip ?? 0;
                                let tgl = p.absensi?.[0]?.tgl ?? 0;
                                let bulan = p.absensi?.[0]?.bulan ?? 0;
                                let periode = p.absensi?.[0]?.periode_id ?? 0;
                                let pagi = p.absensi?.[0]?.pagi ?? 0;
                                let sore = p.absensi?.[0]?.sore ?? 0;
                                let ket = p.absensi?.[0]?.keterangan ?? 0;
                                let disabledAttr = p.status == 1 ? 'disabled' : '';
                                html += `
                <tr>
                    <td class="text-center" style="width: 50px;">${index + 1}</td>
                    <td style="width: 350px;">${p.nama}</td>
                    <td class="text-center" style="width:170px;">${p.status_pegawai.charAt(0).toUpperCase() + p.status_pegawai.slice(1)}</td>

                    <td class="text-center" style="width:95px;">
                        <input type="checkbox" name="masuk_pagi[${p.id}]" ${(pagi ?? 0) == 1 ? 'checked' : ''} ${disabledAttr} ${disabledAttr}>
                    </td>
                    <td class="text-center" style="width:95px;">
                        <input type="checkbox" name="masuk_sore[${p.id}]" ${(sore ?? 0) == 1 ? 'checked' : ''} ${disabledAttr} ${disabledAttr}>
                    </td>
                    <td style="width:275px;">
                        <select class="form-control form-control-sm keterangan-select"
                                data-nip="${nip}"
                                data-id="${periode}"
                                data-bulan="${bulan}"
                                data-tgl="${tgl}"
                                data-jenis="ket"
                                ${disabledAttr}
                                onchange="updateKeterangan(this)">
                            <option value="">Kosong</option>
                            <option value="sakit" ${ket == 'sakit' ? 'selected' : ''}>Sakit</option>
                            <option value="cuti" ${ket == 'cuti' ? 'selected' : ''}>Cuti</option>
                            <option value="izin" ${ket == 'izin' ? 'selected' : ''}>Izin</option>
                            <option value="alpha" ${ket == 'alpha' ? 'selected' : ''}>Alpha</option>
                        </select>
                    </td>
                </tr>
            `;
                            });
                        }
                        document.getElementById('pegawaiBody').innerHTML = html;
                    });
            });



            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('absen-checkbox')) {
                    let checkbox = e.target;
                    let data = {
                        nip: checkbox.dataset.nip,
                        jenis: checkbox.dataset.jenis,
                        id: checkbox.dataset.id,
                        bulan: checkbox.dataset.bulan,
                        tgl: checkbox.dataset.tgl,
                        value: checkbox.checked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    };
                    saveKeDB(data);
                }
            });

            function saveKeDB(data) {
                console.log("MASUK FUNCTION", data);
                let urlabs = "{{ route('absensi.update.ajax') }}";
                fetch(urlabs, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'

                        },
                        body: JSON.stringify(data)
                    })
                    .then(async res => {
                        if (!res.ok) {
                            showNotif("Gagal menyimpan data", "danger");
                            return;
                        }
                        let json = await res.json(); // langsung JSON
                        // console.log(json);
                        if (json.success) {
                            showNotif("Berhasil menyimpan data", "success");
                        } else {
                            showNotif("Gagal menyimpan data", "danger");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                    });
            };

            $(document).on('change', '.keterangan-select', function() {
                let id = $(this).attr('data-id');
                let nip = $(this).attr('data-nip');
                let jenis = $(this).attr('data-jenis');
                let bulan = $(this).attr('data-bulan');
                let tgl = $(this).attr('data-tgl');
                let value = $(this).val();
                let data = {
                    id: id,
                    nip: nip,
                    jenis: jenis,
                    bulan: bulan,
                    tgl: tgl,
                    keterangan: value,
                    _token: '{{ csrf_token() }}'
                };
                saveKeDB(data);
            });
        </script>
    @endpush
@endsection
