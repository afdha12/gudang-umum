<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengajuan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #ddd;
        }

        .ttd {
            margin-top: 50px;
            width: 100%;
            overflow: hidden;
            /* Untuk mengatasi float */
        }

        .ttd div {
            width: 40%;
            text-align: center;
            float: left;
            /* Gunakan float agar CSS2 tetap kompatibel */
            margin-left: 5%;
        }

        .signature {
            width: 150px;
            height: auto;
            display: block;
            margin: 10px auto;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Laporan Pengajuan Barang</h2>
    <p><strong>Tanggal Cetak:</strong> {{ date('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($approvedData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->stationery->nama_barang }}</td>
                    <td>{{ $item->amount }}</td>
                    <td>{{ date('d M Y', strtotime($item->dos)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd">
        <div>
            <p class="mb-5">Manager Divisi</p>
            @if ($manager && $manager->getFirstMediaUrl('signature'))
                <img src="{{ public_path($manager->getFirstMediaUrl('signature')) }}" alt="Tanda Tangan Manager"
                    class="signature">
            @endif
            <p><strong>{{ $manager->name }}</strong></p>
        </div>
        <div>
            <p class="mb-5">Admin Gudang</p>
            @if ($admin && $admin->getFirstMediaUrl('signature'))
                <img src="{{ public_path($admin->getFirstMediaUrl('signature')) }}" alt="Tanda Tangan Admin"
                    class="signature">
            @endif
            <p><strong>{{ $admin->name }}</strong></p>
        </div>
    </div>

</body>

</html>
