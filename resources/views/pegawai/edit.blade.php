@extends('layouts.app')
@section('title', 'Pegawai')
@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Perbarui Data Pegawai</h5>
            </div>

            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Foto -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto</label>
                            <div class="input-group" style="justify-content: center">
                                <input type="file" name="foto" id="photos"
                                    class=" form-control form-control-border mb-3 rounded-0 inGambar sendText previewImg"
                                    style="display: none;visibility:none;" onchange="toText(this.value)">
                                <img src="{{ asset('img/white-background.jpg') }}"
                                    class="img-placeholder-linen img-thumbnail  rounded-0">
                                <img src="{{ asset('storage/' . $pegawai->foto) }}"
                                    class="img-overlay-linen img-preview img-thumbnail rounded-0" onclick="toGambar()">
                                <label class="center-label-linen">
                                    <a role="button" class="btn btn-md btn-flat btn-outline-success" onclick="toGambar()">
                                        <i class="bi bi-camera"></i>
                                    </a>
                                </label>
                            </div>
                            <input type="hidden" name="oldFoto" value="{{ $pegawai->foto }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control"
                                    value="{{ old('nip', $pegawai->nip) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $pegawai->email) }}">
                            </div>
                            <div class="d-flex align-items-center my-3">
                                <hr class="flex-grow-1">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $pegawai->nama) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis_kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="L" {{ $pegawai->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P" {{ $pegawai->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center my-3">
                                <hr class="flex-grow-1">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="+628xxxx">
                        </div>
                        <div class="col-md-4 mb-3 position-relative">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir"
                                value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}" name="tempat_lahir"
                                class="form-control" autocomplete="off" required>
                            <div id="kab-list" class="list-group position-absolute w-100 shadow"
                                style="z-index:999; max-height:250px; overflow-y:auto;">
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tgl_lahir" class="form-control"
                                value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Usia</label>
                            <input type="text" id="usia_text" class="form-control" value="{{ $pegawai->usia }} Tahun"
                                readonly>
                            <input type="hidden" name="usia" id="usia" value="{{ old('usia', $pegawai->usia) }}">
                        </div>

                        <div class="col-md-4 mb-3 position-relative">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" id="kecamatan"
                                value="{{ old('kecamatan', $pegawai->alamat_kecamatan) }}" name="kecamatan"
                                class="form-control" autocomplete="on" required>
                            <div id="kec-list" value="{{ old('kecamatan', $pegawai->alamat_kecamatan) }}"
                                class="list-group position-absolute w-100 shadow"
                                style="z-index:999; max-height:250px; overflow-y:auto;">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" id="kabupaten"
                                value="{{ old('kabupaten', $pegawai->alamat_kabupaten) }}" disabled class="form-control"
                                required>
                            <input type="hidden" id="kabupaten_val"
                                value="{{ old('kabupaten', $pegawai->alamat_kabupaten) }}" name="kabupaten"
                                class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" id="provinsi"value="{{ old('provinsi', $pegawai->alamat_provinsi) }}"
                                disabled class="form-control" required>
                            <input type="hidden" value="{{ old('provinsi', $pegawai->alamat_provinsi) }}"
                                id="provinsi_val" name="provinsi" class="form-control" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control">{{ old('alamat_lengkap', $pegawai->alamat_lengkap) }}</textarea>
                        </div>

                        <!-- Status Kawin -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status Kawin</label>
                            <select name="status_kawin" class="form-select">
                                <option value="tidak kawin"
                                    {{ $pegawai->status_kawin == 'tidak kawin' ? 'selected' : '' }}>
                                    Tidak Kawin</option>
                                <option value="kawin" {{ $pegawai->status_kawin == 'kawin' ? 'selected' : '' }}>Kawin
                                </option>
                            </select>
                        </div>

                        <!-- Jumlah Anak -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jumlah Anak</label>
                            <input type="number" name="jumlah_anak" class="form-control"
                                value="{{ old('jumlah_anak', $pegawai->jumlah_anak) }}">
                        </div>

                        <!-- Tanggal Masuk -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control"
                                value="{{ old('tanggal_masuk', \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('Y-m-d')) }}">
                        </div>

                        <!-- Jabatan -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" class="form-select">
                                <option {{ $pegawai->jabatan == 'Manager' ? 'selected' : '' }}>Manager</option>
                                <option {{ $pegawai->jabatan == 'Staf' ? 'selected' : '' }}>Staf</option>
                                <option {{ $pegawai->jabatan == 'Magang' ? 'selected' : '' }}>Magang</option>
                            </select>
                        </div>

                        <!-- Departemen -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Departemen</label>
                            <select name="departemen" class="form-select">
                                <option {{ $pegawai->departemen == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option {{ $pegawai->departemen == 'HRD' ? 'selected' : '' }}>HRD</option>
                                <option {{ $pegawai->departemen == 'Production' ? 'selected' : '' }}>Production</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Status Pegawai</label>
                            <select name="status_pegawai" class="form-select">
                                <option value="kontrak" {{ $pegawai->status_pegawai == 'kontrak' ? 'selected' : '' }}>
                                    Kontrak
                                </option>
                                <option value="tetap" {{ $pegawai->status_pegawai == 'tetap' ? 'selected' : '' }}>Tetap
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $pegawai->status == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $pegawai->status == 0 ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>


                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="form-label">Pendidikan</label>

                                <!-- SD -->
                                @foreach ($pendidikan as $i => $p)
                                    <div class="row mb-2 pendidikan-row">

                                        <div class="col-md-1 ">
                                            <select class="form-control" name="pendidikan[{{ $i }}][jenjang]"
                                                required>
                                                <option value="SD" {{ $p['jenjang'] == 'SD' ? 'selected' : '' }}>SD
                                                </option>
                                                <option value="SMP" {{ $p['jenjang'] == 'SMP' ? 'selected' : '' }}>SMP
                                                </option>
                                                <option value="SMA" {{ $p['jenjang'] == 'SMA' ? 'selected' : '' }}>SMA
                                                </option>
                                                <option value="SMK" {{ $p['jenjang'] == 'SMK' ? 'selected' : '' }}>SMK
                                                </option>
                                                <option value="D3" {{ $p['jenjang'] == 'D3' ? 'selected' : '' }}>D3
                                                </option>
                                                <option value="D4" {{ $p['jenjang'] == 'D4' ? 'selected' : '' }}>D4
                                                </option>
                                                <option value="S1" {{ $p['jenjang'] == 'S1' ? 'selected' : '' }}>S1
                                                </option>
                                                <option value="S2" {{ $p['jenjang'] == 'S2' ? 'selected' : '' }}>S2
                                                </option>
                                                <option value="S3" {{ $p['jenjang'] == 'S3' ? 'selected' : '' }}>S3
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="text" name="pendidikan[{{ $i }}][nama]"
                                                class="form-control" value="{{ $p['nama'] }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="pendidikan[{{ $i }}][tahun]"
                                                class="form-control" value="{{ $p['tahun'] }}" required>
                                        </div>

                                        <!-- DELETE BUTTON -->
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm btn-delete">
                                                Hapus
                                            </button>
                                        </div>

                                    </div>
                                @endforeach

                                <!-- List tambahan -->
                                <div id="list-pendidikan"></div>

                                <!-- Tombol tambah -->
                                <button type="button" class="btn btn-primary btn-sm mt-2" onclick="tambahField()">
                                    + List Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-sm">
                            Simpan
                        </button>
                    </div>
            </div>

            </form>
        </div>
    </div>
    </div>
    @include('pegawai.ajax');
    @push('script-edit-pegawai')
        <script>
            /* TAMBAH FIELD */
            let indexPendidikan = {{ $i }} + 1;

            function tambahField() {
                let container = document.getElementById('list-pendidikan');

                let div = document.createElement('div');
                div.classList.add('row', 'mb-2');

                div.innerHTML = `
        <div class="col-md-1 text-end">
            <input type="checkbox" onchange="togglePendidikan(this)" required>
        </div>

        <div class="col-md-1 ">
            <select class="form-control" required disabled name="pendidikan[${indexPendidikan}][jenjang]">
                <option value="">Pilih</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="SMK">SMK</option>
                <option value="D3">D3</option>
                <option value="D4">D4</option>
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3">S3</option>
            </select>
        </div>

        <div class="col-md-7">
            <input type="text"
                name="pendidikan[${indexPendidikan}][nama]"
                class="form-control"
                placeholder="Nama Sekolah"
                disabled required>
        </div>
        <div class="col-md-2">
            <input type="number"
                name="pendidikan[${indexPendidikan}][tahun]"
                class="form-control"
                placeholder="Tahun Lulus"
                disabled required>
        </div>

        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
                Hapus
            </button>
        </div>
    `;

                container.appendChild(div);
                indexPendidikan++;
            }
        </script>
    @endpush

@endsection
