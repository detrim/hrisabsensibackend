@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    @if (auth()->user()->isSuperAdmin())
        @include('dashboard.superadmin')
    @elseif (auth()->user()->isAdminHRD())
        @include('dashboard.adminhrd')
    @else
        @include('dashboard.managerhrd')
    @endif
@endsection
