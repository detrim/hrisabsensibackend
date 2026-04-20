@extends('layouts.app')

@section('title', 'User')

@section('content')
    <div class="container mt-4">



        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-3">Data User</h4>
            @if (auth()->user()->isSuperadmin())
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">+ Tambah User</a>
            @endif
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive mt-3">
            <table id="userTable" class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $i => $user)
                        @php
                            $isSuperAdmin = $user->employee_id === auth()->user()->employee_id;
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->username }}</td>

                            <td>
                                @if ($user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('user.detail', $user->id) }}" class="btn btn-info btn-sm">
                                    Detail
                                </a>

                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm" @disabled($isSuperAdmin)>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Data tidak tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-2">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
    @include('user.ajax')
@endsection
