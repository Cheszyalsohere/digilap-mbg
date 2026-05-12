@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')

@section('content')
    <div class="card-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / username / email" class="input sm:w-64">
                <select name="role" class="input sm:w-40">
                    <option value="">Semua role</option>
                    @foreach (['siswa', 'sppg', 'admin'] as $r)
                        <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
                <button class="btn-primary w-full sm:w-auto">Cari</button>
            </form>
            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                <a href="{{ route('admin.users.export-csv') }}"
                   class="btn-secondary w-full sm:w-auto inline-flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Export Siswa (CSV)
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn-primary w-full sm:w-auto">+ Tambah User</a>
            </div>
        </div>

        <div class="overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
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
                                <button type="button"
                                        class="text-danger text-sm hover:underline ml-2"
                                        onclick="confirmDelete('{{ route('admin.users.destroy', $u) }}', 'Hapus akun {{ $u->name }} ({{ $u->username }})? Data feedback terkait akan ikut terhapus.')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
@endsection
