@extends('layouts.app')
@section('title', 'Input Menu')
@section('page_title', 'Input Menu Hari Ini')

@section('content')
    <div class="card-white max-w-2xl">
        <h2 class="text-lg sm:text-xl font-semibold mb-1 break-words">Input Menu — {{ now()->translatedFormat('l, d F Y') }}</h2>
        <p class="text-sm text-muted mb-5">Lengkapi 5 slot komponen makanan untuk hari ini.</p>

        <form method="POST" action="{{ route('sppg.menu.store') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
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
                    <input type="text" name="{{ $name }}" value="{{ old($name) }}"
                           class="input @error($name) border-danger @enderror"
                           placeholder="{{ $placeholder }}">
                    @error($name) <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>
            @endforeach

            <div>
                <label class="label">Foto Menu Hari Ini <span class="text-muted font-normal">(opsional)</span></label>
                <input type="file" name="foto_menu" id="foto_menu_input"
                       accept="image/jpeg,image/png"
                       class="input @error('foto_menu') border-danger @enderror">
                <p class="text-xs text-muted mt-1">Format JPG/PNG, maksimal 2MB.</p>
                @error('foto_menu') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror

                <div id="foto_preview_wrapper" class="hidden mt-3">
                    <p class="text-xs text-muted mb-1">Preview:</p>
                    <img id="foto_preview" alt="Preview foto menu"
                         class="rounded-2xl border border-bordered max-h-72 object-cover">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 pt-2">
                <button type="submit" class="btn-primary w-full sm:w-auto">Simpan Menu</button>
                <a href="{{ route('sppg.dashboard') }}" class="btn-secondary w-full sm:w-auto">Batal</a>
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
            if (!input) return;
            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (!file) { wrapper.classList.add('hidden'); return; }
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    wrapper.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
        })();
    </script>
@endpush
