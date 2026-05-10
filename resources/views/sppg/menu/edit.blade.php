@extends('layouts.app')
@section('title', 'Edit Menu')
@section('page_title', 'Edit Menu Hari Ini')

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
            <h2 class="text-lg sm:text-xl font-semibold mb-1 break-words">Edit Menu &mdash; {{ $menu->tanggal->translatedFormat('l, d F Y') }}</h2>
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
                               placeholder="{{ $placeholder }}"
                               data-slot="{{ $name }}">
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

                <div class="pt-4 border-t border-bordered dark:border-[#2A332C]">
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" name="has_alternatif" value="1" id="has_alternatif"
                               {{ old('has_alternatif', $menu->has_alternatif) ? 'checked' : '' }}
                               class="mt-0.5 rounded border-bordered text-primary focus:ring-primary">
                        <span>
                            <span class="text-sm font-semibold">Sediakan menu alternatif untuk siswa dengan alergi</span>
                            <span class="block text-xs text-muted mt-0.5">Centang jika ada siswa dengan alergi yang butuh pengganti komponen menu.</span>
                        </span>
                    </label>

                    @php
                        $altOn = old('has_alternatif', $menu->has_alternatif);
                        $altSlots = [
                            'alt_slot_1' => ['Alt Karbohidrat (Nasi)', 'slot_1'],
                            'alt_slot_2' => ['Alt Buah', 'slot_2'],
                            'alt_slot_3' => ['Alt Protein Nabati', 'slot_3'],
                            'alt_slot_4' => ['Alt Protein Hewani', 'slot_4'],
                            'alt_slot_5' => ['Alt Minuman (Susu)', 'slot_5'],
                        ];
                    @endphp
                    <div id="alt_section" class="{{ $altOn ? '' : 'hidden' }} mt-4 p-4 rounded-xl bg-primary-light dark:bg-[#1A2E23] border-l-4 border-primary space-y-3">
                        <div>
                            <p class="text-sm font-semibold">Menu Alternatif</p>
                            <p class="text-xs text-muted">Isi slot yang berbeda dari menu utama, kosongkan jika sama.</p>
                        </div>
                        @foreach ($altSlots as $name => [$label, $mirror])
                            <div>
                                <label class="label">{{ $label }}</label>
                                <input type="text" name="{{ $name }}" value="{{ old($name, $menu->$name) }}"
                                       data-mirror="{{ $mirror }}"
                                       class="input"
                                       placeholder="Sama seperti menu utama">
                            </div>
                        @endforeach
                        <div>
                            <label class="label">Keterangan Alternatif</label>
                            <input type="text" name="alt_keterangan"
                                   value="{{ old('alt_keterangan', $menu->alt_keterangan) }}"
                                   maxlength="255"
                                   placeholder="Contoh: Pengganti susu untuk siswa alergi"
                                   class="input">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 pt-2">
                    <button type="submit" class="btn-primary w-full sm:w-auto">Simpan Perubahan</button>
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
            const hapus = document.getElementById('hapus_foto');
            if (input) {
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
            }
            if (hapus) {
                hapus.addEventListener('change', () => {
                    if (hapus.checked) {
                        input.value = '';
                        wrapper.classList.add('hidden');
                    }
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
