@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('page_title', 'Log Aktivitas')

@php
    $rowBg = [
        'create'  => 'bg-[#D5F0E0]',
        'edit'    => 'bg-[#FDF3DC]',
        'destroy' => 'bg-[#FADADD]',
        'default' => '',
    ];
    $roleBadge = [
        'admin' => 'badge-info',
        'sppg'  => 'badge-warning',
        'siswa' => 'badge-success',
    ];
@endphp

@section('content')
    <div class="card-white mb-5">
        <form method="GET" class="grid sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="label">Role</label>
                <select name="role" class="input">
                    <option value="">Semua</option>
                    @foreach (['admin', 'sppg', 'siswa'] as $r)
                        <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="input">
            </div>
            <div>
                <label class="label">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="input">
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <button class="btn-primary w-full sm:w-auto">Filter</button>
                <a href="{{ route('admin.activity-logs') }}" class="btn-secondary w-full sm:w-auto">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-white">
        @if ($logs->isEmpty())
            <p class="text-sm text-muted py-8 text-center">Belum ada aktivitas.</p>
        @else
            <div class="overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            @php $bg = $rowBg[$log->category] ?? ''; @endphp
                            <tr class="{{ $bg }}">
                                <td class="whitespace-nowrap text-xs">
                                    {{ $log->created_at->translatedFormat('d M Y') }}<br>
                                    <span class="text-muted">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="whitespace-nowrap">
                                    @if ($log->user)
                                        <div class="font-medium">{{ $log->user->name }}</div>
                                        <span class="{{ $roleBadge[$log->user->role] ?? 'badge-info' }}">
                                            {{ ucfirst($log->user->role) }}
                                        </span>
                                    @else
                                        <span class="text-muted text-xs">Sistem</span>
                                    @endif
                                </td>
                                <td class="font-medium">{{ $log->action }}</td>
                                <td class="text-sm text-muted">{{ $log->description ?: '—' }}</td>
                                <td class="text-xs text-muted whitespace-nowrap font-mono">{{ $log->ip_address ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
