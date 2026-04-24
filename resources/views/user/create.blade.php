@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

    <div class="container mt-4">

        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 position-relative">
                                <label class="form-label">Nama Pengguna</label>
                                <input type="text" id="nama_pengguna" class="form-control"
                                    placeholder="Ketik nama pegawai...">
                                <input type="hidden" name="pegawai_id" id="pegawai_id">

                                <div id="suggestion-box" class="list-group position-absolute w-100" style="z-index:999">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" minlength="6">
                                <small id="username_msg" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password (Auto Generate)</label>
                                <div class="input-group">
                                    <input type="text" id="password" class="form-control">

                                    <button type="button" class="btn btn-secondary" id="btn-generate">
                                        Generate
                                    </button>
                                </div>
                                <small id="password_msg" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ketik Ulang Password</label>
                                <input type="password" required name="password" id="password_confirm" class="form-control">
                                <small id="confirm_msg" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="">Pilih Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">manajer HRD</option>
                                    <option value="3">Staf HRD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="status" class="form-check-input" id="status" checked>
                                <label class="form-check-label">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm">Simpan</button>

                </form>

            </div>
        </div>
    </div>
    @include('user.ajax')

@endsection
