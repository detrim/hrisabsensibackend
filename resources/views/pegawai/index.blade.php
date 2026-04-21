@extends('layouts.app')
@section('title', 'Pegawai')
@section('content')
    <div class="container mt-4">
        <!-- HEADER BUTTONS -->
        <div class="row mb-3 align-items-center">
            <div class="col-md-8">
                @if (Auth()->user()->isAdminHRD())
                    <div>
                        <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm">+ Data Baru</a>
                        <a href="{{ route('pegawai.export.pdf') }}" class="btn btn-danger btn-sm">Download PDF</a>
                        <a href="{{ route('pegawai.export.excel') }}" class="btn btn-success btn-sm">Download Excel</a>

                        <button class="btn btn-warning btn-sm" id="btn-delete-selected">Hapus Data</button>
                        <select class="btn btn-secondary btn-sm" id="bulk-status">
                            <option value="">Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <!-- SEARCH -->
                <form method="GET" action="{{ route('pegawai.cari') }}" class="d-flex ">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari NIP / Nama / Jabatan" value="{{ request('search') }}">
                    <button class="btn btn-primary btn-sm ms-1">Search</button>
                </form>
            </div>
        </div>

        <!-- FILTER -->
        <form method="GET" action="{{ route('pegawai.filter') }}">
            <div class="card mb-3">
                <div class="card-body row d-flex justify-content-end gap-3">
                    <div class="col-md-6">
                        <!-- FILTER JABATAN MULTI SELECT -->
                        <select name="jabatan[]" required multiple class="form-select form-select-sm jabatan-select w-100"
                            size="3">
                            <option value="Manager" {{ in_array('Manager', (array) request('jabatan')) ? 'selected' : '' }}>
                                Manager
                            </option>
                            <option value="Staf" {{ in_array('Staf', (array) request('jabatan')) ? 'selected' : '' }}>
                                Staf
                            </option>
                            <option value="Magang" {{ in_array('Magang', (array) request('jabatan')) ? 'selected' : '' }}>
                                Magang
                            </option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <!-- MASA KERJA -->
                        <div class="gap-2 align-items-start">
                            <select name="operator" class="form-select form-select-sm" style="width:100%;" required>
                                <option {{ request('operator') == '>' ? 'selected' : '' }} value=">">></option>
                                <option {{ request('operator') == '=' ? 'selected' : '' }} value="=">=</option>
                                <option {{ request('operator') == '<' ? 'selected' : '' }} value="<">
                                    < </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="number" required value="{{ request('masa_kerja') }}" name="masa_kerja"
                            class="form-control form-control-sm" placeholder="Tahun">

                    </div>
                    <div class="col-md-2 align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="{{ route('pegawai.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>

                </div>
            </div>

        </form>

        <!-- TABLE -->
        <form id="bulk-form">
            <div class="table-responsive">
                <table id="pegawaiTable" class="table  table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="check-all"></th>
                            <th>No</th>
                            <th><a>NIP ↕</a></th>
                            <th><a>Nama ↕</a></th>
                            <th><a>Jabatan ↕</a></th>
                            <th><a>Tanggal Masuk ↕</a></th>
                            <th><a>Masa Kerja ↕</a></th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pegawai as $i => $p)
                            <tr>
                                <td>
                                    {{-- @php
                                        $isSuperAdmin = $p->user?->role?->name === 'Superadmin';
                                        $isSelf = auth()->user()->id === $p->user?->id;
                                    @endphp
                                    @if (Auth()->user()->isAdminHRD())
                                        @if ($isSuperAdmin || $isSelf)
                                            <input type="checkbox" disabled />
                                        @else
                                            <input type="checkbox" name="selected[]" value="{{ $p->id }}" />
                                        @endif
                                    @else
                                        <input type="checkbox" name="selected[]" value="{{ $p->id }}" />
                                    @endif --}}
                                    @php
                                        $user = auth()->user();

                                        $isSuperAdmin = $p->user?->role?->name === 'Superadmin';
                                        $isSelf = $user->id === $p->user?->id;

                                        $isDisabled = $user->isAdminHRD() && ($isSuperAdmin || $isSelf);
                                    @endphp

                                    <input type="checkbox" name="selected[]" value="{{ $p->id }}"
                                        @if ($isDisabled) disabled @endif />
                                </td>

                                <td>{{ $pegawai->firstItem() + $i }} </td>
                                <td>{{ $p->nip }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->jabatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_masuk)->format('d/m/Y') }}</td>
                                @php
                                    $diff = \Carbon\Carbon::parse($p->tanggal_masuk)->diff(now());
                                @endphp

                                <td>
                                    {{ $diff->y }} tahun {{ $diff->m }} bulan
                                </td>

                                <td>
                                    <a href="{{ route('pegawai.detail', $p->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    @if (Auth()->user()->isAdminHRD())
                                        <a href="{{ route('pegawai.edit', $p->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('pegawai.pdf', $p->id) }}" class="btn btn-danger btn-sm">PDF</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </form>

        <div class="row justify-content-end ">
            <div class="btn-sm mt-2">
                <small>{{ $pegawai->links('pagination::bootstrap-5') }}</small>
            </div>
        </div>

    </div>
    @include('pegawai.ajax')
    @push('script-index-pegawai')
        <script>
            $(document).ready(function() {
                $('.jabatan-select').select2({
                    placeholder: "Pilih Jabatan",
                    width: '100%',
                    allowClear: true
                });
            });
            $(document).ready(function() {
                $('#pegawaiTable').DataTable({
                    paging: false,
                    searching: false,
                    info: false,
                    ordering: true
                });
            });
            document.getElementById('check-all').addEventListener('click', function() {
                let checks = document.querySelectorAll('input[name="selected[]"]');
                checks.forEach(cb => cb.checked = this.checked);
            });
            /* BULK DELETE */
            $('#btn-delete-selected').on('click', function(e) {
                e.preventDefault();
                let ids = [];
                $('input[name="selected[]"]:checked').each(function() {
                    ids.push($(this).val());
                });
                if (ids.length === 0) {
                    alert('Pilih data terlebih dahulu');
                    return;
                }
                if (!confirm('Yakin mau hapus data?')) return;
                $.ajax({
                    url: "{{ url('adminhrd/pegawai/delete') }}",
                    type: "DELETE",
                    data: {
                        ids: ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        alert(res.message);
                        if (res.status) location.reload();
                    }
                });
            });

            $(document).on('change', '#bulk-status', function() {
                let status = $(this).val();
                if (!status) return;
                let ids = [];
                $('input[name="selected[]"]:checked').each(function() {
                    ids.push($(this).val());
                });
                if (ids.length === 0) {
                    alert('Pilih user dulu!');
                    return;
                }
                console.log(ids, status)
                if (!confirm('Yakin ubah status?')) return;
                let url = "{{ url('adminhrd/pegawai/bulkstatus') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        ids: ids,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        console.log(res); //  cek isi JSON
                        alert(res.message + " (" + res.total_user + " data)");
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // kalau error
                        alert('Terjadi error!');
                    }
                });

            });
        </script>
    @endpush
@endsection
