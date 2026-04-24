@extends('layouts.app')
@section('title', 'Kelola User')
@section('content')
    <div class="container mt-4">
        <div class="card-header mb-3 d-flex justify-content-between align-items-center">
            @if (auth()->user()->isSuperadmin())
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">+ Tambah User</a>
                <input type="text" id="search" class="form-control form-control-sm w-25" placeholder="Nama Pegawai...">
            @endif

        </div>
        @include('session.session')
        <table class="table mt-3">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 120px;">Role</th>
                    <th style="width: 220px;">Username</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 220px;">Aksi</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:450px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table table-striped w-100 ">
                <tbody id="user">
                    @forelse ($users as $i => $user)
                        {{-- @php
                            $isSuperAdmin = $user->employee_id === auth()->user()->employee_id;
                        @endphp --}}
                        @php
                            $authUser = auth()->user();
                            // tidak boleh pilih diri sendiri
                            $isSelf = $user->employee_id === $authUser->employee_id;
                            // ID yang dibatasi
                            $restrictedIds = [1, 2, 3];
                            $isRestricted = in_array($user->id, $restrictedIds);
                            // gabungan kondisi
                            $isDisabled = $isSelf || $isRestricted;
                        @endphp

                        <tr>
                            <td style="width: 50px;">{{ $i + 1 }}</td>
                            <td style="width: 120px;">{{ $user->name }}</td>
                            <td style="width: 220px;">{{ $user->username }}</td>
                            <td style="width: 120px;">
                                @if ($user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>

                            <td style="width: 220px;">
                                <a href="{{ route('user.detail.' . auth()->user()->role_name, $user->id) }}"
                                    class="btn btn-info btn-sm">
                                    Detail
                                </a>
                                <a href="{{ $isRestricted ? '#' : route('user.edit.' . auth()->user()->role_name, $user->id) }}"
                                    class="btn btn-warning btn-sm {{ $isRestricted ? 'disabled' : '' }}"
                                    style="{{ $isRestricted ? 'pointer-events: none; opacity: 0.6;' : '' }}">
                                    Edit
                                </a>

                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm" @disabled($isRestricted)>
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
        </div>
        <div class="row mt-3 align-items-center justify-content-between">
            <div class="col-auto">
                <small>
                    Showing {{ $users->firstItem() }}
                    to {{ $users->lastItem() }}
                    of {{ $users->total() }} results
                </small>
            </div>
            <div class="col-auto">
                <small>{{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}</small>
            </div>
        </div>
    </div>

    @push('user')
        <script>
            const routeDetail = "{{ route('user.detail.' . auth()->user()->role_name, ':id') }}";
            const routeEdit = "{{ route('user.edit.' . auth()->user()->role_name, ':id') }}";
            const routeDelete = "{{ route('user.destroy', ':id') }}";

            document.addEventListener('DOMContentLoaded', function() {

                const search = document.getElementById('search');
                const tbody = document.getElementById('user');
                if (!search || !tbody) return;
                window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                //  debounce function
                let timeout = null;

                search.addEventListener('keyup', function() {

                    clearTimeout(timeout);

                    timeout = setTimeout(() => {

                        let keyword = this.value;
                        let searchUrl = "{{ route('user.search') }}";

                        fetch(`${searchUrl}?keyword=${encodeURIComponent(keyword)}`, {
                                method: "GET",
                                headers: {
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(res => {

                                let list = res.data || [];
                                let key = res.keyword || '';
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

                                    list.forEach((user, index) => {

                                        let detailUrl = routeDetail.replace(':id', user.id);
                                        let editUrl = routeEdit.replace(':id', user.id);
                                        let deleteUrl = routeDelete.replace(':id', user.id);

                                        let highlight = (text) => {
                                            if (!key) return text;
                                            return text.replace(new RegExp(key, 'gi'),
                                                match => `<mark>${match}</mark>`);
                                        };

                                        let statusBadge = user.is_active ?
                                            `<span class="badge bg-success">Aktif</span>` :
                                            `<span class="badge bg-danger">Nonaktif</span>`;

                                        html += `
                            <tr>
                                <td style="width:50px;">${index + 1}</td>

                                <td style="width:120px;">
                                    ${highlight(user.name ?? '-')}
                                </td>

                                <td style="width:220px;">
                                    ${highlight(user.username ?? '-')}
                                </td>

                                <td style="width:120px;">
                                    ${statusBadge}
                                </td>

                                <td style="width:220px;">

                                    <a href="${detailUrl}" class="btn btn-info btn-sm">
                                        Detail
                                    </a>

                                    <a href="${user.is_restricted ? '#' : editUrl}"
                                        class="btn btn-warning btn-sm ${user.is_restricted ? 'disabled' : ''}"
                                        style="${user.is_restricted ? 'pointer-events:none; opacity:0.6;' : ''}">
                                        Edit
                                    </a>

                                    <form action="${deleteUrl}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin hapus user ini?')">

                                        <input type="hidden" name="_token" value="${window.csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">

                                        <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            ${user.is_restricted ? 'disabled' : ''}>
                                            Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        `;
                                    });
                                }

                                tbody.innerHTML = html;

                            })
                            .catch(err => console.error(err));

                    }, 300); // debounce 300ms

                });

            });
        </script>
    @endpush

@endsection
