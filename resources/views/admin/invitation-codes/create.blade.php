@extends('layouts.app')
@section('title', 'Generate Kode Undangan')
@section('page_title', 'Generate Kode Undangan')

@section('content')
    <div class="card-white max-w-xl">
        <h2 class="text-lg font-semibold mb-1">Buat Kode Undangan Baru</h2>
        <p class="text-sm text-muted mb-5">
            Sistem akan otomatis membuat kode unik dengan format
            <code class="text-xs">SEKOLAH-XXXX</code>.
        </p>

        <form method="POST" action="{{ route('admin.invitation-codes.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="label">Sekolah</label>
                <select name="sekolah" class="input @error('sekolah') border-danger @enderror">
                    <option value="">— Pilih sekolah —</option>
                    @foreach (['SMANBA', 'NESABA', 'MANSAPAS'] as $s)
                        <option value="{{ $s }}" @selected(old('sekolah') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-muted mt-1">SPPG akan ditentukan otomatis berdasarkan sekolah.</p>
                @error('sekolah') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Tanggal Expired <span class="text-muted font-normal">(opsional)</span></label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                       class="input @error('expires_at') border-danger @enderror">
                <p class="text-xs text-muted mt-1">Kosongkan jika kode tidak punya tanggal kedaluwarsa.</p>
                @error('expires_at') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-2 pt-2">
                <button type="submit" class="btn-primary w-full sm:w-auto">Generate Kode</button>
                <a href="{{ route('admin.invitation-codes.index') }}" class="btn-secondary w-full sm:w-auto">Batal</a>
            </div>
        </form>
    </div>
@endsection
