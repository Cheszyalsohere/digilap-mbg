<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Feedback - {{ $periode }}</title>
    <style>
        @page { margin: 1.5cm; }
        * { font-family: Arial, Helvetica, sans-serif; }
        body { color: #2C2C2A; font-size: 10.5px; line-height: 1.5; margin: 0; padding: 0; }

        .header {
            background-color: #5C8B6E;
            color: #FFFFFF;
            padding: 18px 20px;
            margin-bottom: 18px;
        }
        .header .brand {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 4px 0 6px 0;
        }
        .header .meta { font-size: 11px; margin: 0; opacity: 0.95; }
        .header .meta strong { font-weight: bold; }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #4A7259;
            border-bottom: 2px solid #5C8B6E;
            padding-bottom: 4px;
            margin-bottom: 8px;
            margin-top: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10px;
        }
        thead th {
            background-color: #5C8B6E;
            color: #FFFFFF;
            text-align: left;
            padding: 8px 9px;
            font-weight: bold;
            font-size: 10px;
        }
        tbody td {
            padding: 6px 9px;
            border-bottom: 1px solid #E2DDD6;
            vertical-align: top;
        }
        tbody tr:nth-child(odd) td { background-color: #FFFFFF; }
        tbody tr:nth-child(even) td { background-color: #F0EDE6; }

        .rating { color: #D4A853; font-weight: bold; white-space: nowrap; }

        .ringkasan {
            background-color: #EBF2ED;
            border-left: 3px solid #5C8B6E;
            padding: 10px 14px;
            margin-top: 6px;
        }
        .ringkasan p { margin: 3px 0; }
        .ringkasan .label { color: #7A7670; }
        .ringkasan .val { font-weight: bold; color: #2C2C2A; }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #7A7670;
            border-top: 1px solid #E2DDD6;
            padding-top: 6px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-muted { color: #7A7670; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="header">
        <p class="brand">DIGILAP MBG</p>
        <h1>Laporan Feedback Siswa MBG</h1>
        <p class="meta"><strong>SPPG:</strong> {{ $sppgName }} &nbsp;|&nbsp; <strong>Sekolah:</strong> {{ $sekolah }} &nbsp;|&nbsp; <strong>Periode:</strong> {{ $periode }}</p>
    </div>

    <div class="section-title">Ringkasan</div>
    <div class="ringkasan">
        <p><span class="label">Total feedback:</span> <span class="val">{{ $count }}</span></p>
        <p><span class="label">Rata-rata rating:</span> <span class="val">{{ $avg !== null ? $avg . ' / 5' : '—' }}</span></p>
        <p><span class="label">% Kepuasan (rating ≥ 3):</span> <span class="val">{{ $puas !== null ? $puas . '%' : '—' }}</span></p>
    </div>

    <div class="section-title">Detail Feedback</div>
    <table>
        <thead>
            <tr>
                <th style="width: 14%">Tanggal</th>
                <th style="width: 20%">Siswa</th>
                <th style="width: 16%">Sekolah</th>
                <th style="width: 12%" class="text-center">Rating</th>
                <th style="width: 38%">Komentar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($feedbacks as $f)
                <tr>
                    <td class="nowrap">{{ $f->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td>{{ $f->user?->name ?? '—' }}</td>
                    <td>{{ $f->user?->sekolah ?? '—' }}</td>
                    <td class="text-center rating">
                        {{ str_repeat('*', $f->rating) }} <span class="text-muted">({{ $f->rating }}/5)</span>
                    </td>
                    <td>{{ $f->komentar ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">Belum ada feedback pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ $tanggalCetak }} | DIGILAP MBG &mdash; OPSI 2026
    </div>
</body>
</html>
