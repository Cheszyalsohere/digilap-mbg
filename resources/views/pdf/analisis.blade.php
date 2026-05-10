<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Analisis Penyimpangan - {{ $periode }}</title>
    <style>
        @page { margin: 1.5cm; }
        * { font-family: Arial, Helvetica, sans-serif; }
        body { color: #2C2C2A; font-size: 11px; line-height: 1.5; margin: 0; padding: 0; }

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
            margin: 4px 0 2px 0;
        }
        .header .periode {
            font-size: 11px;
            margin: 0;
            opacity: 0.95;
        }

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
            font-size: 10.5px;
        }
        thead th {
            background-color: #5C8B6E;
            color: #FFFFFF;
            text-align: left;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 10.5px;
        }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #E2DDD6;
        }
        tbody tr:nth-child(odd) td { background-color: #FFFFFF; }
        tbody tr:nth-child(even) td { background-color: #F0EDE6; }

        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 10px;
            font-size: 9.5px;
            font-weight: bold;
        }
        .badge-normal {
            background-color: #D5F0E0;
            color: #1E7A45;
        }
        .badge-penyimpangan {
            background-color: #FADADD;
            color: #922B21;
        }
        .badge-kosong {
            background-color: #EBF2ED;
            color: #4A7259;
        }

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
    </style>
</head>
<body>
    <div class="header">
        <p class="brand">DIGILAP MBG</p>
        <h1>Laporan Analisis Penyimpangan MBG</h1>
        <p class="periode">Periode: {{ $periode }}</p>
    </div>

    <div class="section-title">Ringkasan per SPPG</div>
    <table>
        <thead>
            <tr>
                <th>SPPG</th>
                <th>Lokasi</th>
                <th class="text-center">Total Feedback</th>
                <th class="text-center">Rata-rata Rating</th>
                <th class="text-center">% Kepuasan</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td><strong>{{ $row['sppg']->name }}</strong></td>
                    <td>{{ $row['sppg']->lokasi }}</td>
                    <td class="text-center">{{ $row['count'] }}</td>
                    <td class="text-center">{{ $row['avg'] ?? '—' }}</td>
                    <td class="text-center">{{ $row['puas'] !== null ? $row['puas'].'%' : '—' }}</td>
                    <td class="text-center">
                        @if ($row['status'] === 'penyimpangan')
                            <span class="badge badge-penyimpangan">Penyimpangan</span>
                        @elseif ($row['status'] === 'normal')
                            <span class="badge badge-normal">Normal</span>
                        @else
                            <span class="badge badge-kosong">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data SPPG.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Ringkasan Keseluruhan</div>
    <div class="ringkasan">
        <p><span class="label">Total feedback bulan ini:</span> <span class="val">{{ $totalFeedback }}</span></p>
        @if ($tertinggi)
            <p>
                <span class="label">SPPG kepuasan tertinggi:</span>
                <span class="val">{{ $tertinggi['sppg']->name }} ({{ $tertinggi['puas'] }}%)</span>
            </p>
        @endif
        @if ($terendah)
            <p>
                <span class="label">SPPG kepuasan terendah:</span>
                <span class="val">{{ $terendah['sppg']->name }} ({{ $terendah['puas'] }}%)</span>
            </p>
        @endif
        @if (!$tertinggi && !$terendah)
            <p class="text-muted">Belum ada data feedback pada periode ini.</p>
        @endif
    </div>

    <div class="footer">
        Dicetak pada {{ $tanggalCetak }} | DIGILAP MBG &mdash; OPSI 2026
    </div>
</body>
</html>
