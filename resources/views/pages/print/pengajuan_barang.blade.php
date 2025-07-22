<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pengajuan Barang</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon/favicon-96x96.png') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 1.5cm;
        }

        .header {
            position: relative;
            margin-bottom: 10px;
            min-height: 100px;
            display: flex;
            align-items: center;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 90px;
            height: auto;
        }

        .kop {
            flex: 1;
            text-align: center;
            padding: 0 100px;
            /* Memberikan ruang untuk logo */
        }

        .kop h1,
        .kop h2 {
            margin: 0;
            padding: 0;
        }

        .kop h1 {
            font-size: 16px;
        }

        .kop h2 {
            font-size: 20px;
        }

        .kop p {
            margin: 2px 0;
            font-size: 12px;
        }

        .line {
            border-top: 2px solid black;
            margin-top: 10px;
            margin-bottom: 20px;
            clear: both;
        }

        .info {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        /* PERBAIKAN UNTUK TANDA TANGAN - HORIZONTAL */
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-row {
            display: table-row;
        }

        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 10px;
            vertical-align: top;
        }

        .signature-box p {
            margin: 5px 0;
            font-size: 12px;
        }

        .signature-box img {
            width: 80px;
            height: 60px;
            object-fit: contain;
            margin: 10px 0;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Fallback untuk browser yang tidak mendukung table-cell */
        @media print {
            .signature-section {
                display: block;
                overflow: hidden;
            }

            .signature-box {
                float: left;
                width: 33.33%;
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('assets/favicon/web-app-manifest-192x192.png') }}" class="logo">
        <div class="kop">
            <h1>PT MEDIKA LOKA LAMPUNG</h1>
            <h2>RUMAH SAKIT HERMINA LAMPUNG</h2>
            <p>Jl. Tulang Bawang No. 21 - 23, Kel. Enggal, Kec. Enggal, Kota Bandar Lampung</p>
            <p>Telp. (0721) 242525 (Hunting), Fax. (0721) 268561</p>
            <p>Website: www.herminahospitals.com</p>
        </div>
    </div>

    <div class="line"></div>

    <p class="info">Tanggal Cetak: {{ date('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pengajuan</th>
                <th>Nama Pengaju</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalHarga = 0; @endphp
            @foreach ($approvedData as $index => $item)
                @php
                    $hargaSatuan = $item->stationery->harga_barang ?? 0;
                    $subtotal = $item->amount * $hargaSatuan;
                    $totalHarga += $subtotal;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ date('d M Y', strtotime($item->dos)) }}</td>
                    <td class="text-capitalize">{{ strtolower($item->user->name) }}</td>
                    <td class="text-uppercase">{{ optional($item->stationery)->nama_barang ?? 'Barang sudah dihapus' }}</td>
                    <td>{{ $item->amount }}</td>
                    <td>Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"><strong>Total</strong></td>
                <td><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Tanda Tangan - DIPERBAIKI HORIZONTAL -->
    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-box">
                <p class="text-capitalize">Manager {{ $item->user->division->division_name }}</p>
                <img src="{{ $manager->getFirstMediaPath('signature') }}" alt="Tanda Tangan Manager">
                <p class="text-capitalize"><strong>{{ strtolower($manager->name) }}</strong></p>
            </div>
            <div class="signature-box">
                <p>Admin Gudang</p>
                <img src="{{ $admin->getFirstMediaPath('signature') }}" alt="Tanda Tangan Admin">
                <p class="text-capitalize"><strong>{{ $admin->name }}</strong></p>
            </div>
            <div class="signature-box">
                <p>Wadir</p>
                <img src="{{ $coo->getFirstMediaPath('signature') }}" alt="Tanda Tangan Wadir">
                <p class="text-capitalize"><strong>{{ $coo->name }}</strong></p>
            </div>
        </div>
    </div>

</body>

</html>