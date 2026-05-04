@extends('layouts.app')
@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')
    <div class="card-white max-w-3xl">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            @include('admin.users._form')
        </form>
    </div>
@endsection
