@extends('layouts.app')
@section('title', 'User Online')
@section('content')

    <div class="container">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th style="width:150px">NIP</th>
                    <th style="width:250px">Nama</th>
                    <th style="width:200px">Departemen</th>
                    <th style="width:150px">Status</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:450px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table">
                <tbody id="onlineTable">
                    <!-- AJAX load here -->
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('online.index')
    <script>
        function loadOnlinePegawai() {
            let online = "{{ route('online') }}";

            $.ajax({
                url: online,
                type: "GET",
                headers: {
                    'Accept': 'application/json',
                    "Authorization": "Bearer {{ session('api_token') }}"
                },
                success: function(data) {
                    let rows = '';

                    data.forEach(function(item) {
                        rows += `
                    <tr>
                        <td style="width:150px">${item.pegawai ? item.pegawai.nip : '-'}</td>
                        <td style="width:250px">${item.pegawai ? item.pegawai.nama : '-'}</td>
                        <td style="width:200px">${item.pegawai ? item.pegawai.departemen : '-'}</td>
                        <td style="width:135px">
                            <span class="badge bg-success">Online</span>
                        </td>
                    </tr>
                    `;
                    });

                    $('#onlineTable').html(rows);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        // load pertama kali
        loadOnlinePegawai();

        // reload tiap 60 detik
        setInterval(loadOnlinePegawai, 60000);
    </script>
@endpush
