@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')

@section('content')
    <div class="card-white">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / username / email" class="input w-64">
                <select name="role" class="input w-40">
                    <option value="">Semua role</option>
                    @foreach (['siswa', 'sppg', 'admin'] as $r)
                        <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
                <button class="btn-primary">Cari</button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Tambah User</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Sekolah</th>
                        <th>SPPG</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td class="font-medium">{{ $u->name }}</td>
                            <td><code class="text-xs">{{ $u->username }}</code></td>
                            <td>{{ $u->email }}</td>
                            <td><span class="badge-info">{{ $u->role }}</span></td>
                            <td>{{ $u->sekolah ?? '—' }}</td>
                            <td>{{ $u->sppg?->name ?? '—' }}</td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.users.edit', $u) }}" class="text-primary text-sm hover:underline">Edit</a>
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-danger text-sm hover:underline ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
@endsection
