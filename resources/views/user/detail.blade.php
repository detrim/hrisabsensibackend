@extends('layouts.app')

@section('title', 'Detail User')

@section('content')

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row">


                    {{-- Nama Pengguna --}}
                    <div class="mb-3 col-md-6">
                        <label class="fw-bold">Nama Pengguna</label>
                        <div class="form-control">
                            {{ $user->pegawai->nama ?? '-' }}
                        </div>
                    </div>

                    {{-- Username --}}
                    <div class="mb-3 col-md-6">
                        <label class="fw-bold">Username</label>
                        <div class="form-control">
                            {{ $user->username }}
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3 col-md-6">
                        <label class="fw-bold">Email</label>
                        <div class="form-control">
                            {{ $user->email }}
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3 col-md-6">
                        <label class="fw-bold">NIP</label>
                        <div class="form-control">
                            {{ $user->employee_id ?? '-' }}
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="mb-3 col-md-6">
                        <label class="fw-bold">Role</label>
                        <div class="form-control">
                            @if ($user->role_id == 1)
                                Admin
                            @elseif ($user->role_id == 2)
                                Manajer HRD
                            @elseif ($user->role_id == 3)
                                Staf HRD
                            @elseif($user->role_id == 4)
                                Staf
                            @endif
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3 col-md-6">
                        <label class="fw-bold">Status</label>
                        <div class="form-control">
                            @if ($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Tombol --}}
                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                        ← Back
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection
