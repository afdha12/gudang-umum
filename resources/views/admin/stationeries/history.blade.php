@extends('layouts.main')

@section('title', 'History Keluar Masuk Barang')

@section('content')

    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="d-flex m-3">
            <div class="text-lg text-capitalize font-bold text-gray-700">
                Riwayat {{ $detailedItem->first()->stationery->nama_barang }}
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($detailedItem as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $item->tanggal }}</td>
                            <td class="py-3 px-4">{{ $item->jenis }}</td>
                            <td class="py-3 px-4">{{ $item->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('partials.pagination', ['data' => $detailedItem])
        </div>
    </div>

@endsection
