@extends('layouts.app')
@section('title', 'Pegawai')
@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detail Data Pegawai</h5>
            </div>

            <div class="card-body">
                <form>
                    <div class="row">
                        <!-- Foto -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto</label>
                            <div class="input-group" style="justify-content: center">

                                <img src="{{ asset('img/white-background.jpg') }}"
                                    class="img-placeholder-linen img-thumbnail  rounded-0 bg-white" disabled>
                                <img src="{{ asset('storage/' . $pegawai->foto) ?? asset('storage/app/public/' . $pegawai->foto) }}"
                                    class="img-overlay-linen img-preview img-thumbnail rounded-0 bg-white" disabled>

                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control bg-white" disabled
                                    value="{{ old('nip', $pegawai->nip) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control bg-white" disabled
                                    value="{{ old('email', $pegawai->email) }}">
                            </div>
                            <div class="d-flex align-items-center my-3">
                                <hr class="flex-grow-1">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control bg-white" disabled
                                    value="{{ old('nama', $pegawai->nama) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis_kelamin</label>
                                <input type="text" class="form-control bg-white"
                                    value="{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" disabled>
                            </div>
                            <div class="d-flex align-items-center my-3">
                                <hr class="flex-grow-1">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control bg-white" disabled
                                value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="+628xxxx">
                        </div>
                        <div class="col-md-4 mb-3 position-relative">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir"
                                value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}" name="tempat_lahir"
                                class="form-control bg-white" disabled autocomplete="off" required>
                            <div id="kab-list" class="list-group position-absolute w-100 shadow"
                                style="z-index:999; max-height:250px; overflow-y:auto;">
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tgl_lahir" class="form-control bg-white" disabled
                                value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Usia</label>
                            <input type="text" id="usia_text" class="form-control bg-white" disabled
                                value="{{ $pegawai->usia }} Tahun" readonly>
                            <input type="hidden" name="usia" id="usia"
                                value="{{ old('usia', $pegawai->usia) }} bg-white" disabled>
                        </div>

                        <!-- Wilayah -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control bg-white" disabled
                                value="{{ old('kecamatan', $pegawai->alamat_kecamatan) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control bg-white" disabled
                                value="{{ old('kabupaten', $pegawai->alamat_kabupaten) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control bg-white" disabled
                                value="{{ old('provinsi', $pegawai->alamat_provinsi) }}">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control bg-white" disabled>{{ old('alamat_lengkap', $pegawai->alamat_lengkap) }}</textarea>
                        </div>

                        <!-- Status Kawin -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status Kawin</label>
                            <input type="text" class="form-control bg-white" disabled
                                value="{{ $pegawai->status_kawin }}">
                        </div>

                        <!-- Jumlah Anak -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jumlah Anak</label>
                            <input type="number" name="jumlah_anak" class="form-control bg-white" disabled
                                value="{{ old('jumlah_anak', $pegawai->jumlah_anak) }}">
                        </div>

                        <!-- Tanggal Masuk -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control bg-white" disabled
                                value="{{ old('tanggal_masuk', \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('Y-m-d')) }}">
                        </div>

                        <!-- Jabatan -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" class="form-control bg-white" disabled
                                value="{{ $pegawai->jabatan }}">

                        </div>

                        <!-- Departemen -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Departemen</label>
                            <input type="text" class="form-control bg-white" disabled
                                value="{{ $pegawai->departemen }}">
                        </div>

                        <!-- Status -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Status Pegawai</label>
                            <input type="text" class="form-control bg-white" disabled
                                value="{{ $pegawai->status_pegawai == 'kontrak' ? 'Kontrak' : 'Tetap' }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control bg-white" disabled
                                value="{{ $pegawai->status == 1 ? 'Aktif' : 'Non Aktif' }}">
                        </div>


                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="form-label">Pendidikan</label>
                                <!-- SD -->
                                @foreach ($pendidikan as $i => $p)
                                    <div class="row mb-2 pendidikan-row">
                                        <div class="col-md-1 ">
                                            <input type="text" class="form-control bg-white" disabled
                                                value="{{ $p['jenjang'] }}">
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="pendidikan[{{ $i }}][nama]"
                                                class="form-control bg-white" disabled value="{{ $p['nama'] }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="pendidikan[{{ $i }}][tahun]"
                                                class="form-control bg-white" disabled value="{{ $p['tahun'] }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Pendidikan -->


                    </div>

                    <div class="mb-3 text-end">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                            ← Back
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
