@extends('layouts.main')

@section('title', 'Data Stok Barang')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="d-flex m-3">
            <div>
                <a class="btn btn-primary" href="{{ route('stationeries.create', ['type' => '1']) }}">Tambah Data Barang</a>
            </div>
            <div class="ms-auto">
                <input type="text" class="form-control ml-3" placeholder="Cari barang..." id="search">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Kode Barang</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Harga Barang</th>
                        <th class="py-3 px-4 text-left">Satuan</th>
                        <th class="py-3 px-4 text-left">Masuk</th>
                        <th class="py-3 px-4">Keluar</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($data as $key => $item)
                        <tr>
                            <td class="py-3 px-4">{{ $data->firstItem() + $key }}</td>
                            <td class="py-3 px-4">{{ $item->kode_barang }}</td>
                            <td class="py-3 px-4">{{ $item->nama_barang }}</td>
                            <td class="py-3 px-4">{{ $item->formatted_harga }}</td>
                            <td class="py-3 px-4">{{ $item->satuan }}</td>
                            <td class="py-3 px-4">{{ $item->masuk }}</td>
                            <td class="py-3 px-4">{{ $item->keluar }}</td>
                            <td class="py-3 px-4">{{ $item->stok }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="row">
                                    <a href="{{ route('stationeries.edit', ['stationery' => $item->id, 'type' => $type]) }}"
                                        class="btn btn-outline-primary btn-sm col mr-2"><i class="bi bi-pencil"></i></a>
                                    <a href="{{ route('stationeries.show', ['stationery' => $item->id, 'type' => $type]) }}"
                                        class="btn btn-outline-info btn-sm col mr-2"><i class="bi bi-list-task"></i></a>
                                    <a href="{{ route('stationeries.destroy', ['stationery' => $item->id, 'type' => $type]) }}"
                                        class="btn btn-outline-danger btn-sm col" data-confirm-delete="true"><i
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
