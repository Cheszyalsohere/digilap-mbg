@extends('layouts.app')
@section('title', 'Input Menu')
@section('page_title', 'Input Menu Hari Ini')

@section('content')
    <div class="max-w-2xl space-y-5">
        @if ($allergyStats->isNotEmpty())
            <div class="card-white border-l-4 border-primary">
                <div class="flex items-start gap-2">
                    <span aria-hidden="true" class="text-lg">ℹ️</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold mb-2">Siswa di sekolah ini memiliki alergi:</p>
                        <ul class="text-sm space-y-1">
                            @foreach ($allergyStats as $stat)
                                <li class="flex items-center justify-between">
                                    <span>{{ $stat->name }}</span>
                                    <span class="badge-info">{{ $stat->siswa_count }} siswa</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-white">
            <h2 class="text-lg sm:text-xl font-semibold mb-1 break-words">Input Menu — {{ now()->translatedFormat('l, d F Y') }}</h2>
            <p class="text-sm text-muted mb-5">Lengkapi 5 slot komponen makanan untuk hari ini.</p>

            <form method="POST" action="{{ route('sppg.menu.store') }}" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @php
                    $slots = [
                        'slot_1' => ['Slot 1', 'Isi menu slot 1...'],
                        'slot_2' => ['Slot 2', 'Isi menu slot 2...'],
                        'slot_3' => ['Slot 3', 'Isi menu slot 3...'],
                        'slot_4' => ['Slot 4', 'Isi menu slot 4...'],
                        'slot_5' => ['Slot 5', 'Isi menu slot 5...'],
                    ];
                @endphp
                @foreach ($slots as $name => [$label, $placeholder])
                    <div>
                        <label class="label">{{ $label }}</label>
                        <input type="text" name="{{ $name }}" value="{{ old($name) }}"
                               class="input @error($name) border-danger @enderror"
                               placeholder="{{ $placeholder }}"
                               data-slot="{{ $name }}">
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

                <div class="pt-4 border-t border-bordered dark:border-[#2A332C]">
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" name="has_alternatif" value="1" id="has_alternatif"
                               {{ old('has_alternatif') ? 'checked' : '' }}
                               class="mt-0.5 rounded border-bordered text-primary focus:ring-primary">
                        <span>
                            <span class="text-sm font-semibold">Sediakan menu alternatif untuk siswa dengan alergi</span>
                            <span class="block text-xs text-muted mt-0.5">Centang jika ada siswa dengan alergi yang butuh pengganti komponen menu.</span>
                        </span>
                    </label>

                    <div id="alt_section" class="{{ old('has_alternatif') ? '' : 'hidden' }} mt-4 p-4 rounded-xl bg-primary-light dark:bg-[#1A2E23] border-l-4 border-primary space-y-3">
                        <div>
                            <p class="text-sm font-semibold">Menu Alternatif</p>
                            <p class="text-xs text-muted">Isi slot yang berbeda dari menu utama, kosongkan jika sama.</p>
                        </div>
                        @php
                            $altSlots = [
                                'alt_slot_1' => ['Alt Slot 1', 'slot_1'],
                                'alt_slot_2' => ['Alt Slot 2', 'slot_2'],
                                'alt_slot_3' => ['Alt Slot 3', 'slot_3'],
                                'alt_slot_4' => ['Alt Slot 4', 'slot_4'],
                                'alt_slot_5' => ['Alt Slot 5', 'slot_5'],
                            ];
                        @endphp
                        @foreach ($altSlots as $name => [$label, $mirror])
                            <div>
                                <label class="label">{{ $label }}</label>
                                <input type="text" name="{{ $name }}" value="{{ old($name) }}"
                                       data-mirror="{{ $mirror }}"
                                       class="input"
                                       placeholder="Sama seperti menu utama">
                            </div>
                        @endforeach
                        <div>
                            <label class="label">Keterangan Alternatif</label>
                            <input type="text" name="alt_keterangan" value="{{ old('alt_keterangan') }}"
                                   maxlength="255"
                                   placeholder="Contoh: Pengganti susu untuk siswa alergi"
                                   class="input">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 pt-2">
                    <button type="submit" class="btn-primary w-full sm:w-auto">Simpan Menu</button>
                    <a href="{{ route('sppg.dashboard') }}" class="btn-secondary w-full sm:w-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const input = document.getElementById('foto_menu_input');
            const wrapper = document.getElementById('foto_preview_wrapper');
            const preview = document.getElementById('foto_preview');
            if (input) {
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
            }

            const altToggle = document.getElementById('has_alternatif');
            const altSection = document.getElementById('alt_section');
            if (altToggle && altSection) {
                altToggle.addEventListener('change', () => {
                    altSection.classList.toggle('hidden', !altToggle.checked);
                });
            }

            document.querySelectorAll('[data-mirror]').forEach(altInput => {
                const main = document.querySelector(`[data-slot="${altInput.dataset.mirror}"]`);
                if (!main) return;
                const sync = () => { altInput.placeholder = main.value || altInput.placeholder; };
                main.addEventListener('input', sync);
                sync();
            });
        })();
    </script>
@endpush
