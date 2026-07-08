{{-- resources/views/backend/rekomendasi/cetak.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekomendasi - {{ $rekomendasi->hasilSurvei->lokasi->nama_jalan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 18px;
            color: #666;
        }
        .title {
            text-align: center;
            margin: 30px 0;
        }
        .title h3 {
            font-size: 20px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }
        table th {
            background: #f5f5f5;
            font-weight: bold;
        }
        .info-table td:first-child {
            width: 30%;
            font-weight: bold;
            background: #f9f9f9;
        }
        .info-table td:last-child {
            width: 70%;
        }
        .signature {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #ccc;
        }
        .signature-left {
            float: left;
            width: 50%;
        }
        .signature-right {
            float: right;
            width: 45%;
            text-align: center;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .badge-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
        }
        .badge-draft { background: #6c757d; }
        .badge-diajukan { background: #ffc107; color: #333; }
        .badge-disetujui { background: #28a745; }
        .badge-ditolak { background: #dc3545; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-20 { margin-top: 20px; }
        .mb-10 { margin-bottom: 10px; }
        .fw-bold { font-weight: bold; }
        .text-success { color: #28a745; }
        .text-warning { color: #ffc107; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #007bff; }
    </style>
</head>
<body>

    <div class="header">
        <h1>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG</h1>
        <h2>Kota Jayapura, Papua</h2>
        <p>Jalan Raya Abepura No. 1, Jayapura - Papua</p>
    </div>

    <div class="title">
        <h3>SURAT REKOMENDASI</h3>
        <p>Pemasangan Lampu Penerangan Jalan Umum (LPJU)</p>
        <p><strong>Nomor: {{ 'LPJU/' . date('Y') . '/' . str_pad($rekomendasi->id, 4, '0', STR_PAD_LEFT) }}</strong></p>
    </div>

    <!-- Informasi Lokasi -->
    <table class="info-table">
        <tr>
            <td>Nama Jalan</td>
            <td><strong>{{ $rekomendasi->hasilSurvei->lokasi->nama_jalan }}</strong></td>
        </tr>
        <tr>
            <td>Distrik</td>
            <td>{{ $rekomendasi->hasilSurvei->lokasi->distrik }}</td>
        </tr>
        <tr>
            <td>Koordinat</td>
            <td>
                Latitude: {{ number_format($rekomendasi->hasilSurvei->lokasi->latitude, 7) }}<br>
                Longitude: {{ number_format($rekomendasi->hasilSurvei->lokasi->longitude, 7) }}
            </td>
        </tr>
        <tr>
            <td>Surveyor</td>
            <td>{{ $rekomendasi->hasilSurvei->user->name }}</td>
        </tr>
        <tr>
            <td>Tanggal Survei</td>
            <td>{{ $rekomendasi->hasilSurvei->tanggal_survei->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>Status Rekomendasi</td>
            <td>
                <span class="badge-status badge-{{ $rekomendasi->status }}">
                    {{ strtoupper($rekomendasi->status) }}
                </span>
                @if($rekomendasi->status == 'disetujui')
                    <br><small>Disetujui pada: {{ $rekomendasi->tanggal_disetujui->format('d F Y') }}</small>
                @endif
            </td>
        </tr>
    </table>

    <!-- Hasil Perhitungan -->
    <h4 class="mt-20">A. Hasil Perhitungan SAW</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kriteria</th>
                <th>Bobot</th>
                <th>Atribut</th>
                <th>Nilai</th>
                <th>Normalisasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekomendasi->hasilSurvei->rankingSaw as $index => $ranking)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $ranking->kriteria->nama_kriteria)) }}</td>
                    <td class="text-center">{{ $ranking->kriteria->bobot }}%</td>
                    <td class="text-center">
                        <span style="color: {{ $ranking->kriteria->atribut == 'benefit' ? '#28a745' : '#dc3545' }}">
                            {{ ucfirst($ranking->kriteria->atribut) }}
                        </span>
                    </td>
                    <td class="text-center">
                        {{ $rekomendasi->hasilSurvei->{$ranking->kriteria->nama_kriteria} }}
                    </td>
                    <td class="text-center">{{ number_format($ranking->nilai_normalisasi, 4) }}</td>
                </tr>
            @endforeach
            <tr style="background: #f0f8ff; font-weight: bold;">
                <td colspan="5" class="text-right">Nilai Preferensi (V)</td>
                <td class="text-center text-success">{{ number_format($rekomendasi->hasilSurvei->nilai_preferensi, 4) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Rekomendasi LPJU -->
    <h4 class="mt-20">B. Rekomendasi Kebutuhan LPJU</h4>
    <table>
        <tr>
            <td style="width: 40%; font-weight: bold;">Panjang Jalan</td>
            <td style="width: 60%;">{{ number_format($rekomendasi->hasilSurvei->panjang_jalan, 0) }} meter</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Lebar Jalan</td>
            <td>{{ number_format($rekomendasi->hasilSurvei->lebar_jalan, 0) }} meter</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Tinggi Tiang</td>
            <td>{{ number_format($rekomendasi->hasilSurvei->tinggi_tiang, 0) }} meter</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jarak Antar Lampu</td>
            <td>{{ number_format($rekomendasi->jarak_antar_lampu, 0) }} meter <br>
                <small>(4 × Tinggi Tiang)</small>
            </td>
        </tr>
        <tr style="background: #f0f8ff; font-weight: bold;">
            <td style="font-weight: bold; color: #007bff;">Jumlah Lampu yang Dibutuhkan</td>
            <td style="color: #007bff; font-size: 18px;">{{ $rekomendasi->jumlah_lampu }} Unit</td>
        </tr>
        <tr style="background: #f0fff0; font-weight: bold;">
            <td style="font-weight: bold; color: #28a745;">Total Estimasi Biaya</td>
            <td style="color: #28a745; font-size: 18px;">Rp {{ number_format($rekomendasi->total_biaya, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Catatan -->
    @if($rekomendasi->catatan)
        <div class="mt-20">
            <h4>C. Catatan</h4>
            <p style="padding: 15px; background: #f9f9f9; border-left: 4px solid #ffc107;">
                {{ $rekomendasi->catatan }}
            </p>
        </div>
    @endif

    <!-- Tanda Tangan -->
    <div class="signature clearfix">
        <div class="signature-left">
            <p><strong>Mengetahui,</strong></p>
            <p style="margin-top: 60px;">
                <strong>Kepala Dinas PUPR</strong><br>
                Kota Jayapura
            </p>
        </div>
        <div class="signature-right">
            <p><strong>Jayapura, {{ date('d F Y') }}</strong></p>
            <p style="margin-top: 60px;">
                <strong>Kepala Bidang</strong><br>
                <u>{{ auth()->user()->name }}</u>
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini adalah rekomendasi resmi dari Dinas Pekerjaan Umum dan Penataan Ruang Kota Jayapura</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>