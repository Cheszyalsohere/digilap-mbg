@php $isEdit = isset($user); @endphp

@if ($isEdit)
    <div class="mb-4 p-3 rounded-xl bg-primary-light text-sm">
        <span class="text-muted">Username:</span> <code class="font-semibold">{{ $user->username }}</code>
    </div>
@endif

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="label">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="input @error('name') border-danger @enderror">
        @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="input @error('email') border-danger @enderror">
        @error('email') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
    </div>

    @if (! $isEdit)
        <div>
            <label class="label">Role</label>
            <select name="role" class="input @error('role') border-danger @enderror" id="role-select">
                @foreach (['siswa', 'sppg', 'admin'] as $r)
                    <option value="{{ $r }}" @selected(old('role') === $r)>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            @error('role') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
        </div>
    @endif

    <div>
        <label class="label">Sekolah <span class="text-muted text-xs">(siswa)</span></label>
        <select name="sekolah" class="input @error('sekolah') border-danger @enderror">
            <option value="">—</option>
            @foreach (['SMANBA', 'NESABA', 'MANSAPAS'] as $s)
                <option value="{{ $s }}" @selected(old('sekolah', $user->sekolah ?? '') === $s)>{{ $s }}</option>
            @endforeach
        </select>
        @error('sekolah') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="label">SPPG <span class="text-muted text-xs">(siswa & sppg)</span></label>
        <select name="sppg_id" class="input">
            <option value="">—</option>
            @foreach ($sppgs as $s)
                <option value="{{ $s->id }}" @selected(old('sppg_id', $user->sppg_id ?? '') == $s->id)>{{ $s->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="label">{{ $isEdit ? 'Password Baru (opsional)' : 'Password' }}</label>
        <input type="password" name="password" class="input @error('password') border-danger @enderror">
        @error('password') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="input">
    </div>
</div>

<div class="mt-5 flex gap-2">
    <button type="submit" class="btn-primary">Simpan</button>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
</div>
