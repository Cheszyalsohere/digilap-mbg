<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang - DIGILAP MBG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bg text-ink antialiased">
    <header class="bg-white border-b border-bordered">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white font-bold">D</div>
                <div>
                    <div class="font-bold text-ink leading-none">DIGILAP</div>
                    <div class="text-[11px] text-muted leading-none mt-0.5">MBG Monitoring</div>
                </div>
            </a>
            @auth
                <a href="/" class="text-sm text-primary hover:underline">Kembali ke Dashboard &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-primary hover:underline">Masuk &rarr;</a>
            @endauth
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-8 py-10">
        <section class="text-center mb-12">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-primary flex items-center justify-center text-white text-4xl font-bold mb-4 shadow-sm">D</div>
            <h1 class="text-3xl sm:text-4xl font-bold text-ink">DIGILAP MBG</h1>
            <p class="text-base text-muted mt-2 max-w-2xl mx-auto">
                Digitalisasi Laporan Makan Bergizi Gratis &mdash; sistem pelaporan digital untuk
                mendukung pengawasan Program MBG secara transparan dan akuntabel.
            </p>
        </section>

        <section class="card-white mb-8">
            <h2 class="text-lg font-semibold text-ink mb-3">Tentang Platform</h2>
            <div class="space-y-3 text-sm text-ink/85 leading-relaxed">
                <p>
                    DIGILAP MBG merupakan platform berbasis web yang dikembangkan sebagai sistem pelaporan
                    digital untuk mendukung pengawasan Program Makan Bergizi Gratis (MBG). Platform ini
                    memungkinkan siswa melaporkan kondisi makanan secara real-time melalui bukti foto dan
                    penilaian terstruktur, sehingga dapat mendeteksi secara dini indikasi penyimpangan
                    seperti penurunan kualitas gizi, pengurangan porsi, maupun praktik yang tidak sesuai standar.
                </p>
                <p>
                    Data yang terkumpul diolah menjadi informasi komparatif antar penyedia untuk mendukung
                    evaluasi yang objektif dan pengambilan keputusan yang tepat. DIGILAP MBG bertujuan
                    meningkatkan transparansi, akuntabilitas, serta efektivitas distribusi gizi agar
                    pelaksanaan program berjalan sesuai ketentuan.
                </p>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-lg font-semibold text-ink mb-3">Penelitian</h2>
            <div class="rounded-2xl bg-gradient-to-br from-primary to-[#4A7559] text-white p-6 shadow-sm">
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-[11px] font-semibold uppercase tracking-wide mb-3">
                    OPSI 2026
                </span>
                <h3 class="text-lg sm:text-xl font-bold leading-snug">
                    DIGILAP MBG WebApp: Sistem Pelapor Digital Siswa SMA sebagai Deteksi Dini Penyimpangan
                    Penyaluran MBG Berbasis Teori Fraud Triangle
                </h3>
                <div class="mt-4 grid sm:grid-cols-2 gap-3 text-sm">
                    <div class="bg-white/10 rounded-xl px-4 py-3">
                        <div class="text-white/70 text-[11px] uppercase tracking-wide">Ajang</div>
                        <div class="font-semibold mt-0.5">Olimpiade Penelitian Siswa Indonesia (OPSI) 2026</div>
                    </div>
                    <div class="bg-white/10 rounded-xl px-4 py-3">
                        <div class="text-white/70 text-[11px] uppercase tracking-wide">Penyelenggara</div>
                        <div class="font-semibold mt-0.5">Pusat Prestasi Nasional (Puspresnas)</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-lg font-semibold text-ink mb-3">Tim Pengembang</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach ([['name' => 'Husnia Wardaul Ula', 'initial' => 'HW'], ['name' => 'Zaenab Kamila', 'initial' => 'ZK']] as $dev)
                    <div class="card-white flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-lg font-bold shrink-0">
                            {{ $dev['initial'] }}
                        </div>
                        <div>
                            <div class="font-semibold text-ink">{{ $dev['name'] }}</div>
                            <div class="text-xs text-muted mt-0.5">Peneliti &amp; Pengembang</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-lg font-semibold text-ink mb-3">Sampel &amp; Periode Penelitian</h2>
            <div class="grid sm:grid-cols-3 gap-4 mb-4">
                @foreach ([
                    ['sppg' => 'SPPG Kalirejo', 'sekolah' => 'SMANBA'],
                    ['sppg' => 'SPPG Rembang',  'sekolah' => 'NESABA'],
                    ['sppg' => 'SPPG Bangil',   'sekolah' => 'MANSAPAS'],
                ] as $s)
                    <div class="card-white">
                        <div class="text-[11px] uppercase tracking-wide text-muted">Penyedia</div>
                        <div class="font-semibold text-ink mt-1">{{ $s['sppg'] }}</div>
                        <div class="mt-3 inline-block px-2.5 py-1 rounded-lg bg-primary/10 text-primary text-xs font-semibold">
                            {{ $s['sekolah'] }}
                        </div>
                        <div class="text-xs text-muted mt-2">50 siswa responden</div>
                    </div>
                @endforeach
            </div>
            <div class="card-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="text-[11px] uppercase tracking-wide text-muted">Total Responden</div>
                    <div class="text-xl font-bold text-ink">150 siswa</div>
                </div>
                <div class="hidden sm:block w-px h-10 bg-bordered"></div>
                <div>
                    <div class="text-[11px] uppercase tracking-wide text-muted">Periode Penelitian</div>
                    <div class="text-xl font-bold text-ink">Mei &ndash; Agustus 2025</div>
                </div>
            </div>
        </section>

        <footer class="text-center text-xs text-muted pt-6 border-t border-bordered">
            &copy; {{ date('Y') }} DIGILAP MBG &mdash; OPSI Puspresnas
        </footer>
    </main>
</body>
</html>
