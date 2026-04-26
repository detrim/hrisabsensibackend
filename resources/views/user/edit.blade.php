@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

    <div class="container mt-4">
        <div class="card">
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

                <form action="{{ route('user.update.' . auth()->user()->role_name, $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Nama Pengguna --}}
                            <div class="mb-3 position-relative">
                                <label class="form-label">Nama Pengguna</label>
                                <input type="text" id="nama_pengguna" class="form-control"
                                    value="{{ $user->pegawai->nama ?? '' }}" placeholder="Ketik nama pegawai..." required>
                                <input type="hidden" name="pegawai_id" id="pegawai_id" value="{{ $user->pegawai_id }}">
                                <div id="suggestion-box" class="list-group position-absolute w-100" style="z-index:999">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Username --}}
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control"
                                    value="{{ $user->username }}" required>
                                <small id="username_msg" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Password (optional saat edit) --}}
                            <div class="mb-3">
                                <label class="form-label">Password</label>
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
                            {{-- Confirm Password --}}
                            <div class="mb-3">
                                <label class="form-label">Ketik Ulang Password (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" id="password_confirm" class="form-control">
                                <small id="confirm_msg" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Role --}}
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Admin</option>
                                    <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Manajer HRD</option>
                                    <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Staf HRD</option>
                                    <option value="3" {{ $user->role_id == 4 ? 'selected' : '' }}>Staf</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Status --}}
                            <label class="form-label">Status</label>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="status" class="form-check-input" id="status"
                                    {{ $user->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-sm">Update</button>

                </form>

            </div>
        </div>
    </div>
    @include('user.ajax')
@endsection
