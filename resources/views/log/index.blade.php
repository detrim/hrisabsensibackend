@extends('layouts.app')

@section('title', 'Log Activity')

@section('content')

    <div class="container">

        <table class="table mt-4">
            <thead class="table-dark">
                <tr>
                    <th style="width:50px;">No</th>
                    <th style="width:150px;">Nama</th>
                    <th style="width:200px;">Activity</th>
                    <th style="width:255px;">Deskripsi</th>
                    <th style="width:330px;">Properties</th>
                    <th style="width:230px;">Waktu</th>
                </tr>
            </thead>
        </table>
        <div class="table-responsive" style="max-height:450px; overflow-y:auto;max-width:100%; margin-top:-16px">
            <table class="table table-striped">
                <tbody>
                    @forelse ($logs as $key => $log)
                        <tr>
                            <td style="width:50px;">{{ $logs->firstItem() + $key }}</td>
                            <td style="width:150px;">
                                {{ $log->causer->name ?? 'System' }}
                            </td>
                            <td style="width:200px;">{{ $log->log_name }}</td>
                            <td style="width:250px;">{{ $log->description }}</td>
                            <td style="width:350px;">
                                <pre style="white-space: pre-wrap;">
                                 @php
                                     $props = collect(json_decode($log->properties, true))
                                         ->filter(function ($value) {
                                             if (is_array($value)) {
                                                 return count($value) > 0;
                                             }
                                             return !is_null($value) && $value !== '';
                                         })
                                         ->toArray();
                                 @endphp

{{ count($props) ? json_encode($props, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '-' }}
                                </pre>
                            </td>
                            <td style="width:200px;">{{ $log->created_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row mt-3 align-items-center justify-content-between">
            <div class="col-auto">
                <small>
                    Showing {{ $logs->firstItem() }}
                    to {{ $logs->lastItem() }}
                    of {{ $logs->total() }} results
                </small>
            </div>
            <div class="col-auto">
                {{ $logs->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
