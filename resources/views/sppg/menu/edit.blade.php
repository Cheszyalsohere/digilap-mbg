@extends('layouts.app')
@section('title', 'Edit Menu')
@section('page_title', 'Edit Menu Hari Ini')

@section('content')
    <div class="card-white max-w-2xl">
        <h2 class="text-xl font-semibold mb-1">Edit Menu &mdash; {{ $menu->tanggal->translatedFormat('l, d F Y') }}</h2>
        <p class="text-sm text-muted mb-1">Perbarui komposisi makanan jika ada perubahan mendadak.</p>
        <p class="text-xs text-muted mb-5">
            Terakhir diubah: {{ $menu->updated_at->translatedFormat('d F Y, H:i') }}
            <span class="text-muted/70">({{ $menu->updated_at->diffForHumans() }})</span>
        </p>

        <form method="POST" action="{{ route('sppg.menu.update', $menu) }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label class="label">Tanggal</label>
                <input type="text" value="{{ $menu->tanggal->translatedFormat('d F Y') }}" class="input bg-surface text-muted" readonly>
                <p class="text-xs text-muted mt-1">Tanggal menu tidak dapat diubah.</p>
            </div>

            @php
                $slots = [
                    'slot_1' => ['Nasi', 'Contoh: Nasi putih 200gr'],
                    'slot_2' => ['Buah', 'Contoh: Pisang ambon 1 buah'],
                    'slot_3' => ['Protein Nabati', 'Contoh: Tempe goreng 2 potong'],
                    'slot_4' => ['Protein Hewani', 'Contoh: Ayam goreng 1 potong'],
                    'slot_5' => ['Susu', 'Contoh: Susu UHT 200ml'],
                ];
            @endphp
            @foreach ($slots as $name => [$label, $placeholder])
                <div>
                    <label class="label">{{ $label }}</label>
                    <input type="text" name="{{ $name }}" value="{{ old($name, $menu->$name) }}"
                           class="input @error($name) border-danger @enderror"
                           placeholder="{{ $placeholder }}">
                    @error($name) <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            @endforeach

            <div>
                <label class="label">Foto Menu Hari Ini <span class="text-muted font-normal">(opsional)</span></label>

                @if ($menu->foto_menu)
                    <div id="foto_lama_wrapper" class="mb-3">
                        <p class="text-xs text-muted mb-1">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $menu->foto_menu) }}" alt="Foto menu"
                             class="rounded-2xl border border-bordered max-h-72 object-cover">
                        <label class="flex items-center gap-2 text-sm text-danger mt-2">
                            <input type="checkbox" name="hapus_foto" value="1" id="hapus_foto"
                                   class="rounded border-bordered text-danger focus:ring-danger">
                            Hapus foto saat ini
                        </label>
                    </div>
                @endif

                <input type="file" name="foto_menu" id="foto_menu_input"
                       accept="image/jpeg,image/png"
                       class="input @error('foto_menu') border-danger @enderror">
                <p class="text-xs text-muted mt-1">
                    {{ $menu->foto_menu ? 'Upload file baru untuk mengganti foto saat ini.' : 'Format JPG/PNG, maksimal 2MB.' }}
                </p>
                @error('foto_menu') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror

                <div id="foto_preview_wrapper" class="hidden mt-3">
                    <p class="text-xs text-muted mb-1">Preview foto baru:</p>
                    <img id="foto_preview" alt="Preview foto menu"
                         class="rounded-2xl border border-bordered max-h-72 object-cover">
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('sppg.dashboard') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const input = document.getElementById('foto_menu_input');
            const wrapper = document.getElementById('foto_preview_wrapper');
            const preview = document.getElementById('foto_preview');
            const hapus = document.getElementById('hapus_foto');
            if (!input) return;
            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (!file) { wrapper.classList.add('hidden'); return; }
                if (hapus) hapus.checked = false;
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    wrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
            if (hapus) {
                hapus.addEventListener('change', () => {
                    if (hapus.checked) {
                        input.value = '';
                        wrapper.classList.add('hidden');
                    }
                });
            }
        })();
    </script>
@endpush
