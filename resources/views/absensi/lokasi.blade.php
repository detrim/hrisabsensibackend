@extends('layouts.app')
@section('title', 'Lokasi')
@section('content')
    <div class="container mt-3">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        Data Lokasi Kantor
                    </div>
                    <div class="card-body">
                        <p class="mb-1">
                            <b>Lokasi:</b> <span id="lokasi1">-</span>
                        </p>
                        <p class="mb-1">
                            <b>Latitude:</b> <span id="latitude1">-</span>
                        </p>
                        <p class="mb-0">
                            <b>Longitude:</b> <span id="longitude1">-</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- KOTAK KANAN -->
            <div class="col-md-6">

                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        Cari Kantor & Pegawai
                    </div>
                    <div class="card-body">
                        <label class="form-label">Cari Kantor</label>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <input type="checkbox" id="cek" onchange="toggle(this)">
                            <div class="position-relative flex-grow-1">
                                <input type="text" id="lokasi" class="form-control" placeholder="Jl. / Desa"
                                    autocomplete="off" disabled style="text-transform: capitalize;">
                                <div class="list-alamat list-group position-absolute w-100"
                                    style="z-index:999; display:none;"></div>
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" onclick="simpanLokasi(this)">
                                <span id="btnText">Simpan</span>
                                <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>

                        </div>

                        <hr>
                        <label class="form-label">Cari Pegawai</label>
                        <input type="text" id="searchNama" class="form-control" placeholder="Search nama pegawai...">
                    </div>
                </div>

            </div>

        </div>
        <hr>
        @include('session.session')

        <!-- TABLE -->
        <table class="table table-bordered mt-2">
            <thead class="table-dark text-center">
                <tr>
                    <th width="50">#</th>
                    <th>Nama Pegawai</th>
                    <th>Lokasi Pegawai</th>
                    <th>Cari Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="tbodyPegawai">
                @forelse ($pegawai as $i => $p)
                    @php
                        $data = explode(',', $p->lokasi?->lokasi);
                        $desa = $data[0] ?? null;
                        $kab = $data[1] ?? null;
                        $prov = $data[2] ?? null;
                        $lokasi = ($d = array_filter([$desa, $kab, $prov])) ? implode(', ', $d) : null;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $pegawai->firstItem() + $i }} </td>
                        <td class="nama">{{ $p->nama }}</td>
                        <td class="text-center" style="width: 370px">{{ $lokasi ? $lokasi : null }}</td>
                        <td class="position-relative flex-grow-1">
                            <input type="text" class="form-control lokasi-input" placeholder="Jl. / Desa"
                                autocomplete="off" style="text-transform: capitalize;" data-id="{{ $p->id }}">
                            <div class="list-alamat list-group position-absolute w-100" style="z-index:999; display:none;">
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" onclick="simpanLokasiMulti(this)">
                                <span class="btn-text">Simpan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-danger">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
    <input type="hidden" id="lokasi">
    <input type="hidden" id="latitude">
    <input type="hidden" id="longitude">
    @push('lokasi')
        <script>
            let timer;
            let lokasiMultiData = [];
            let pegawaiData = @json($pegawai);

            document.addEventListener('input', function(e) {
                let input = e.target;
                // hanya untuk 2 tipe input ini
                if (!input.classList.contains('lokasi-input') && input.id !== 'lokasi') return;
                let keyword = input.value;
                // data-id hanya ada di lokasi-input
                let id = input.dataset?.id ?? null;
                let isGlobal = input.id === 'lokasi';
                let isMulti = input.classList.contains('lokasi-input');
                let list = input.parentElement.querySelector('.list-alamat');
                if (!list) return;
                clearTimeout(timer);
                if (keyword.length < 3) {
                    list.style.display = 'none';
                    return;
                }
                timer = setTimeout(() => {
                    fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(keyword)}`
                        )
                        .then(res => res.json())
                        .then(data => {
                            list.innerHTML = '';
                            if (!Array.isArray(data) || data.length === 0) {
                                list.style.display = 'none';
                                return;
                            }
                            data.slice(0, 5).forEach(item => {
                                let div = document.createElement('a');
                                div.href = "#";
                                div.classList.add('list-group-item', 'list-group-item-action');
                                div.innerText = item.display_name;

                                div.onclick = function(ev) {
                                    ev.preventDefault();
                                    // GLOBAL INPUT (#lokasi)
                                    if (isGlobal) {
                                        input.value = item.display_name;
                                        document.getElementById('latitude').value = item.lat;
                                        document.getElementById('longitude').value = item.lon;
                                        document.getElementById('lokasi').value = item
                                            .display_name;
                                    }
                                    // MULTI INPUT (.lokasi-input)
                                    if (isMulti) {
                                        input.value = item.display_name;
                                        let container = input.closest('.position-relative');
                                        let lat = container.querySelector('.latitude') ||
                                            createHidden(container, 'latitude');
                                        let lng = container.querySelector('.longitude') ||
                                            createHidden(container, 'longitude');
                                        let lok = container.querySelector('.lokasi') ||
                                            createHidden(container, 'lokasi');
                                        lat.value = item.lat;
                                        lng.value = item.lon;
                                        lok.value = item.display_name;
                                    }

                                    list.style.display = 'none';
                                };
                                list.appendChild(div);
                            });
                            list.style.display = 'block';
                        })
                        .catch(err => console.log("ERROR:", err));

                }, 300);
            });

            // helper hidden input
            function createHidden(parent, name) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.className = name;
                parent.appendChild(input);
                return input;
            }

            function simpanLokasiMulti(btn) {
                //data
                let row = btn.closest('tr');
                let input = row.querySelector('.lokasi-input');
                let container = input.closest('.position-relative');
                let id_pegawai = input.dataset.id;
                let lat = container.querySelector('.latitude');
                let lng = container.querySelector('.longitude');
                let lok = container.querySelector('.lokasi');
                if (!lok || !lat || !lng) {
                    alert('Data lokasi tidak lengkap!');
                    return;
                }
                setLoading(btn, true);
                let data = {
                    id_pegawai: id_pegawai,
                    nama_lokasi: lok.value,
                    latitude: lat.value,
                    longitude: lng.value,
                    _token: '{{ csrf_token() }}'
                };
                let Url = "{{ route('lokasipegawai.store') }}";
                // console.log("SEBELUM SIMPAN");
                simpanKeDB([data], Url, btn);
            }

            function simpanKeDB(data, Url, btn) {
                // console.log("MASUK FUNCTION");
                fetch(Url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(async res => {
                        let text = await res.text();
                        try {
                            let json = JSON.parse(text);
                            // console.log(json);
                            if (res.ok) {
                                showNotif("Berhasil menyimpan lokasi", "success");
                                loadPegawai();
                                loadLokasi();
                            } else {
                                showNotif("Gagal menyimpan data", "danger");
                            }
                        } catch (e) {
                            console.error('Bukan JSON!');
                        } finally {
                            // STOP LOADING
                            setLoading(btn, false);
                        }
                    });
            }

            document.addEventListener('click', function(e) {
                // ===== SINGLE MODE =====
                if (!document.getElementById('lokasi')?.contains(e.target)) {
                    const listSingle = document.querySelectorAll('.list-alamat');
                    if (listSingle.length === 1) {
                        listSingle[0].style.display = 'none';
                    }
                }
                // ===== MULTI MODE =====
                document.querySelectorAll('.list-alamat').forEach(el => {
                    if (!el.previousElementSibling?.contains(e.target)) {
                        el.style.display = 'none';
                    }
                });
            });


            function simpanLokasi(btn) {
                let lokasi = document.getElementById('lokasi')?.value.trim();
                let latitude = document.getElementById('latitude')?.value.trim();
                let longitude = document.getElementById('longitude')?.value.trim();
                // console.log('pegawaiData:', pegawaiData);
                // console.log(Array.isArray(pegawaiData));
                if (!lokasi || !latitude || !longitude) {
                    alert('Data tidak lengkap!');
                    return;
                }
                setLoading(btn, true);
                let data = {
                    lokasi: lokasi,
                    latitude: latitude,
                    longitude: longitude,
                    _token: '{{ csrf_token() }}'
                };
                let Url = "{{ route('lokasikantor.store') }}";
                simpanKeDB([data], Url, btn);
            }



            function toggle(el) {
                let input = document.getElementById('lokasi');
                if (el.checked) {
                    input.disabled = false;
                    input.focus();
                } else {
                    input.disabled = true;
                    input.value = '';
                }
            }

            loadLokasi();
            async function loadLokasi() {
                let apilok = "{{ route('api.lokasikantor') }}";
                try {
                    const res = await fetch(apilok, {
                        method: 'GET',
                        headers: {
                            "Authorization": "Bearer {{ session('api_token') }}",
                            "Accept": "application/json"
                        }
                    });
                    // console.log("STATUS:", res.status);
                    const text = await res.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error("Response bukan JSON!");
                        return;
                    }
                    document.getElementById('lokasi1').innerText = data.lokasi ?? '-';
                    document.getElementById('latitude1').innerText = data.latitude ?? '-';
                    document.getElementById('longitude1').innerText = data.longitude ?? '-';

                } catch (error) {
                    console.log("FETCH ERROR:", error);
                }
            }

            function setLoading(button, isLoading = true) {
                if (!button) return;
                let text = button.querySelector('.btn-text');
                let spinner = button.querySelector('.spinner-border');

                if (isLoading) {
                    button.disabled = true;
                    // if (text) text.innerText = "Menyimpan...";
                    if (spinner) spinner.classList.remove('d-none');
                } else {
                    button.disabled = false;
                    if (text) text.innerText = "Simpan";
                    if (spinner) spinner.classList.add('d-none');
                }
                // RESET KHUSUS (CEK GLOBAL / ROW)
                // GLOBAL (tidak punya .lokasi-input di row)
                let inputGlobal = document.getElementById('lokasi');
                let cek = document.getElementById('cek');

                if (!button.closest('tr')) {
                    // GLOBAL
                    if (!isLoading) {
                        if (cek) cek.checked = false;
                        if (inputGlobal) {
                            inputGlobal.value = '';
                            inputGlobal.disabled = true;
                        }
                    }
                } else {
                    // ROW
                    let row = button.closest('tr');
                    let input = row.querySelector('.lokasi-input');

                    if (!isLoading && input) {
                        input.value = '';
                    }
                }
            }

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
            document.addEventListener('DOMContentLoaded', function() {
                function simpanLokasiMulti() {
                    console.log("ini tidak global");
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                function simpanLokasi() {
                    console.log("ini tidak global");
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                loadPegawai();
            });


            document.getElementById('searchNama').addEventListener('keyup', function() {
                let keyword = this.value;
                let searchUrl = "{{ route('lokasi.search') }}";
                fetch(`${searchUrl}?keyword=${encodeURIComponent(keyword)}`)
                    .then(res => res.json())
                    .then(data => {
                        // console.log('DATA:', data);
                        let list = Array.isArray(data) ? data : [];

                        let html = '';

                        if (list.length === 0) {
                            html = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            Data tidak ditemukan
                        </td>
                    </tr>
                `;
                        } else {
                            list.forEach((p, index) => {
                                // ===== AMBIL LOKASI (SUDAH AMAN DARI BACKEND) =====
                                let lokasiText = '';

                                if (p.lokasi_text) {
                                    lokasiText = p.lokasi_text
                                        .split(',')
                                        .slice(0, 3)
                                        .map(x => x.trim())
                                        .filter(Boolean)
                                        .join(', ');
                                }
                                html += `
                        <tr>
                            <td class="text-center">${index + 1}</td>

                            <td class="nama">
                                ${p.nama ?? '-'}
                            </td>

                            <td class="text-center" style="width: 370px">
                                ${lokasiText}
                            </td>

                            <td class="position-relative">
                                <input type="text"
                                    class="form-control lokasi-input"
                                    placeholder="Jl. / Desa"
                                    autocomplete="off"
                                    style="text-transform: capitalize;"
                                    data-id="${p.id}">

                                <div class="list-alamat list-group position-absolute w-100"
                                    style="z-index:999; display:none;">
                                </div>
                            </td>

                            <td>
                                <button type="button"
                                    class="btn btn-primary btn-sm"
                                    onclick="simpanLokasiMulti(this)">

                                    <span class="btn-text">Simpan</span>
                                    <span class="spinner-border spinner-border-sm d-none"></span>
                                </button>
                            </td>
                        </tr>
                    `;
                            });
                        }
                        document.getElementById('tbodyPegawai').innerHTML = html;
                    })
                    .catch(err => {
                        console.error('ERROR:', err);
                    });
            });



            function loadPegawai() {
                let apip = "{{ route('api.lokasipegawai') }}";
                fetch(apip, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            "Authorization": "Bearer {{ session('api_token') }}"
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        if (!data || data.length === 0) {
                            html = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            Data tidak ditemukan
                        </td>
                    </tr>
                `;
                        } else {
                            data.forEach((p, i) => {
                                html += `
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td class="nama">
                                ${p.nama ?? '-'}
                            </td>
                            <td class="text-center" style="width: 370px">
                                ${p.lokasi ?? '-'}
                            </td>
                            <td class="position-relative flex-grow-1">
                                <input type="text"
                                    class="form-control lokasi-input"
                                    placeholder="Jl. / Desa"
                                    autocomplete="off"
                                    style="text-transform: capitalize;"
                                    data-id="${p.id}">
                                <div class="list-alamat list-group position-absolute w-100"
                                    style="z-index:999; display:none;">
                                </div>
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-primary btn-sm"
                                    onclick="simpanLokasiMulti(this)">
                                    <span class="btn-text">Simpan</span>
                                    <span class="spinner-border spinner-border-sm d-none"></span>
                                </button>
                            </td>
                        </tr>
                    `;
                            });
                        }
                        document.getElementById('tbodyPegawai').innerHTML = html;
                    })
                    .catch(err => console.error(err));
            }
        </script>
    @endpush
@endsection
