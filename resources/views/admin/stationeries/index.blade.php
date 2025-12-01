@extends('layouts.main')
@section('title', 'Data Stok Barang')
@section('content')

    @include('components.export-excel-modal', [
        'availablePeriods' => $availablePeriods,
        'action' => route('stationeries.export'),
    ])
    @include('components.export-excel-script', ['availablePeriods' => $availablePeriods])

    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="d-flex m-3">
            <div>
                <a class="btn btn-primary" href="{{ route('stationeries.create', ['type' => '1']) }}">Tambah Data Barang</a>
            </div>
            <div class="mx-2">
                @include('components.export-excel-button', ['btn' => 'Export Data Barang'])
            </div>
            <div class="ms-auto">
                <input type="text" class="border rounded" placeholder="Cari barang..." id="search">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Kode Barang</th>
                        <th class="p-3 text-left">Nama Barang</th>
                        <th class="p-3 text-left">Harga Barang</th>
                        <th class="p-3 text-left">Satuan</th>
                        {{-- <th class="p-3 text-left">Masuk</th>
                        <th class="p-3">Keluar</th> --}}
                        <th class="p-3">Stok</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($data as $key => $item)
                        <tr>
                            <td class="p-3">{{ $data->firstItem() + $key }}</td>
                            <td class="p-3">{{ $item->kode_barang }}</td>
                            <td class="text-capitalize p-3">{{ $item->nama_barang }}</td>
                            <td class="p-3">{{ $item->formatted_harga }}</td>
                            <td class="p-3">{{ $item->satuan }}</td>
                            {{-- <td class="p-3">{{ $item->masuk }}</td>
                            <td class="p-3">{{ $item->keluar }}</td> --}}
                            <td class="p-3">{{ $item->stok }}</td>
                            <td class="p-3">
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('stationeries.edit', ['stationery' => $item->id, 'type' => $type]) }}"
                                        class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>

                                    <a href="{{ route('stationeries.show', ['stationery' => $item->id, 'type' => $type]) }}"
                                        class="btn btn-outline-info btn-sm"><i class="bi bi-list-task"></i></a>

                                    <a href="{{ route('stationeries.destroy', ['stationery' => $item->id, 'type' => $type]) }}"
                                        class="btn btn-outline-danger btn-sm" data-confirm-delete="true"><i
                                            class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('partials.pagination', ['data' => $data])
        </div>
    </div>

    <script src="{{ asset('js/search.js') }}"></script>

@endsection
