<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengajuan Barang</title>
    <link rel="stylesheet" href="{{ asset('modules/bootstrap/css/bootstrap.min.css') }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 1cm;
            /* Tambahkan margin agar rapi saat dicetak */
        }

        .ttd {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .ttd div {
            text-align: center;
            width: 40%;
        }

        .signature {
            width: 150px;
            height: auto;
            /* max-height: 120px; */
        }

        .hr {
            border-top: 2px solid black;
        }
    </style>
</head>

<body>
    <div class="container px-0">
        <div class="row align-items-center">
            <div class="col-2 text-center">
                <img src="{{ asset('assets/favicon/web-app-manifest-192x192.png') }}" alt="Logo RS Hermina"
                    width="120">
            </div>
            <div class="col-9 text-center">
                <div class="fw-bold fs-5">PT MEDIKA LOKA LAMPUNG</div>
                <div class="fw-bold fs-3">RUMAH SAKIT HERMINA LAMPUNG</div>
                <div class="fs-8">Jl. Tulang Bawang No. 21 - 23, Kel. Enggal, Kec. Enggal, Kota Bandar Lampung</div>
                <div class="fs-9">Telp. (0721) 242525 (Hunting), Fax. (0721) 268561</div>
                <div class="fs-9">
                    Website :&nbsp;<a href="https://www.herminahospitals.com">www.herminahospitals.com</a>
                </div>
            </div>
        </div>

        <!-- Garis di bawah kop -->
        <hr class="mt-2 mb-3 border-3 opacity-75">

        <!-- Konten utama -->
        <p class="fw-bold text-start">Tanggal Cetak: {{ date('d-m-Y') }}</p>

        <div class="table-responsive">
            <table class="table table-bordered text-start">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Nama Pengaju</th>
                        <th>Unit/Divisi</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvedData as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d M Y', strtotime($item->dos)) }}</td>
                            <td class="text-capitalize">{{ strtolower($item->user->name) }}</td>
                            <td class="text-uppercase">{{ $item->user->division->division_name }}</td>
                            <td class="text-uppercase">{{ $item->stationery->nama_barang }}</td>
                            <td>{{ $item->amount }}</td>
                        </tr>
                    @endforeach
                    <!-- Baris total -->
                    <tr>
                        {{-- <td colspan="5"></td> --}}
                        <td colspan="5"><strong>Total</strong></td>
                        <td><strong>{{ $totalJumlah }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan -->
        <div class="row text-center mt-5">
            <div class="col-6">
                <p class="text-capitalize mb-2">Manager {{ $item->user->division->division_name }}</p>
                <div class="d-flex justify-content-center align-items-center" style="height: 120px;">
                    <img src="{{ $manager->getFirstMediaUrl('signature') }}" alt="Tanda Tangan Manager" class="signature align-items-center">
                </div>
                <p class="text-capitalize"><strong>{{ strtolower($manager->name) }}</strong></p>
            </div>
            <div class="col-6">
                <p class="mb-2">Admin Gudang</p>
                <div class="d-flex justify-content-center align-items-center" style="height: 120px;">
                    <img src="{{ $admin->getFirstMediaUrl('signature') }}" alt="Tanda Tangan Admin" class="signature align-items-center">
                </div>
                <p class="text-capitalize"><strong>{{ $admin->name }}</strong></p>
            </div>
        </div>
        <div class="row d-flex justify-content-center text-center mt-5">
            <div class="col-6">
                <p class="text-capitalize mb-2">Wadir</p>
                <div class="d-flex justify-content-center align-items-center" style="height: 120px;">
                    <img src="{{ $coo->getFirstMediaUrl('signature') }}" alt="Tanda Tangan Manager" class="signature align-items-center">
                </div>
                <p class="text-capitalize"><strong>{{ $coo->name }}</strong></p>
            </div>
        </div>
    </div>
</body>

</html>
