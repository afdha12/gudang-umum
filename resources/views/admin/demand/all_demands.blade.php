@extends('layouts.main')

@section('title', 'Daftar Permintaan Barang')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal Permintaan</th>
                        <th class="py-3 px-4 text-left">Nama User</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($demands as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ date('d M Y', strtotime($item->dos)) }}</td>
                            <td class="text-capitalize py-3 px-4">{{ $item->user->name ?? 'Barang tidak ditemukan' }}</td>
                            <td class="text-capitalize py-3 px-4">{{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td class="text-capitalize py-3 px-4">{{ $item->amount.' '.$item->stationery->satuan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('partials.pagination', ['data' => $demands])

        </div>
    </div>

@endsection
