@extends('layouts.app')
@section('title', 'Tambah User')
@section('page_title', 'Tambah User Baru')

@section('content')
    <div class="card-white max-w-3xl">
        <p class="text-sm text-muted mb-4">Username akan di-generate otomatis dari nama depan + sekolah + 3 digit angka.</p>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @include('admin.users._form')
        </form>
    </div>
@endsection
