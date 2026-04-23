@extends('layouts.app')

@section('title', 'Setting Tunjangan')

@section('content')

    <div class="container py-4">
        @include('session.session')
        <div class="row justify-content-center g-4">
            {{-- CARD KIRI (DISPLAY) --}}
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        Informasi Tunjangan

                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalLokasi">
                            + Tambah Lokasi
                        </button>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ asset('img/map.jpg') }}" alt="Map" class="img-fluid rounded"
                                style="max-height: 230px; object-fit: cover;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Lokasi Kantor</label>
                            <p class="mb-0" id="lokasi1">{{ $lokasi }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Tarif Tunjangan</label>
                            <p class="mb-0" id="tarif">{{ $tunjangan }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Maximal Jarak</label>
                            <p class="mb-0" id="max">{{ $max }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100 d-flex flex-column">
                    <div class="card-header bg-primary text-white">
                        Input Tunjangan
                    </div>
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <img src="{{ asset('img/transport.jpg') }}" alt="Transport" class="img-fluid rounded"
                                style="max-height: 180px; object-fit: cover;">
                        </div>
                        <form action="{{ route('setting.store') }}" method="POST" id="formSetting">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nominal Tunjangan</label>
                                <input type="number" class="form-control" name="tarif_per_km" id="tarif"
                                    placeholder="Masukkan nominal">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Maximal Jarak</label>
                                <input type="number" class="form-control" name="jarak_km" id="max"
                                    placeholder="Masukkan maximal jarak">
                                <small class="text-info">Jika kosong default 25</small>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success" form="formSetting" id="btnSubmit">
                                <span id="btnLoading" class="spinner-border spinner-border-sm me-2 d-none"
                                    role="status"></span>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalLokasi" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="#" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Lokasi Kantor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lokasi</label>
                            <div class="position-relative flex-grow-1">
                                <input type="text" id="lokasi" class="form-control" placeholder="Jl. / Desa"
                                    autocomplete="off" style="text-transform: capitalize;">
                                <div class="list-alamat list-group position-absolute w-100"
                                    style="z-index:999; display:none;"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="lokasi">
                    <input type="hidden" id="latitude">
                    <input type="hidden" id="longitude">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm" onclick="simpanLokasi(this)">
                            <span id="btnText">Simpan</span>
                            <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('setting')
        <script>
            let timer;

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

                                    list.style.display = 'none';
                                };
                                list.appendChild(div);
                            });
                            list.style.display = 'block';
                        })
                        .catch(err => console.log("ERROR:", err));

                }, 300);
            });

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

            function simpanLokasi(btn) {
                let lokasi = document.getElementById('lokasi')?.value.trim();
                let latitude = document.getElementById('latitude')?.value.trim();
                let longitude = document.getElementById('longitude')?.value.trim();
                if (!lokasi || !latitude || !longitude || lokasi < 3) {
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
            $('#formSetting').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let btn = $('#btnSubmit');
                let spinner = $('#btnLoading');
                let url = form.attr('action');
                let data = form.serialize();

                // show loading
                btn.prop('disabled', true);
                spinner.removeClass('d-none');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        body: data
                    })
                    .then(async response => {
                        let text = await response.text();
                        let res;
                        try {
                            res = JSON.parse(text);
                        } catch (e) {
                            console.error("Bukan JSON:", text);
                            throw new Error("Invalid JSON");
                        }
                        return {
                            ok: response.ok,
                            data: res
                        };
                    })
                    .then(({
                        ok,
                        data
                    }) => {
                        if (ok && data.status) {
                            showNotif(data.message || "Berhasil menyimpan", "success");
                            form[0].reset();
                            loadTunjangan();
                        } else {
                            showNotif(data.message || "Gagal menyimpan", "danger");
                        }
                    })
                    .catch(err => {
                        console.log(err.message);
                        showNotif("Terjadi kesalahan", "danger");
                    })
                    .finally(() => {
                        btn.prop('disabled', false);
                        spinner.addClass('d-none');
                    });
            });

            function simpanKeDB(data, Url, btn) {
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
                            if (res.ok) {
                                let modalEl = document.getElementById('modalLokasi');
                                let modal = bootstrap.Modal.getInstance(modalEl);
                                modal.hide();

                                showNotif("Berhasil menyimpan lokasi", "success");
                            } else {
                                showNotif("Gagal menyimpan data", "danger");
                            }
                        } catch (e) {
                            console.error('Bukan JSON!');
                        } finally {
                            loadLokasi();
                            setLoading(btn, false);
                        }
                    });
            }

            function setLoading(button, isLoading = true) {
                if (!button) return;
                let text = button.querySelector('.btn-text');
                let spinner = button.querySelector('.spinner-border');
                if (isLoading) {
                    button.disabled = true;
                    if (spinner) spinner.classList.remove('d-none');
                } else {
                    button.disabled = false;
                    if (spinner) spinner.classList.add('d-none');
                }
                let inputGlobal = document.getElementById('lokasi');
                let cek = document.getElementById('cek');
                if (!isLoading) {
                    inputGlobal.value = '';
                }
            }
            async function loadLokasi() {
                let apilok = "{{ route('api.lokasikantor') }}";
                try {
                    const res = await fetch(apilok, {
                        method: 'GET',
                        headers: {
                            "Authorization": "Bearer {{ session('api_token') }}",
                            "Accept": "application/json",
                        }
                    });
                    const text = await res.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                        console.log(data)
                    } catch (e) {
                        console.error("Response bukan JSON!");
                        return;
                    }
                    document.getElementById('lokasi1').innerText = data.lokasi ?? '-';

                } catch (error) {
                    console.log("FETCH ERROR:", error);
                }
            }

            async function loadTunjangan() {
                let api = "{{ route('api.setting.data') }}";
                try {
                    const res = await fetch(api, {
                        method: 'GET',
                        headers: {
                            "Authorization": "Bearer {{ session('api_token') }}",
                            "Accept": "application/json"
                        }
                    });
                    const text = await res.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error("Response bukan JSON!");
                        return;
                    }
                    console.log(data);
                    document.getElementById('tarif').innerText = data.tarif_per_km ?? '-';
                    document.getElementById('max').innerText = data.max_jarak ?? '-';
                } catch (error) {
                    console.log("FETCH ERROR:", error);
                }
            }
        </script>
    @endpush
@endsection
