@extends('layouts.main')

@section('title', 'Users Management')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="m-3">
            <a class="btn btn-primary" href="{{ route('item-demand.create') }}">Buat Permintaan</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal Pengajuan</th>
                        <th class="py-3 px-4 text-left">Kode Barang</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Satuan</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Catatan</th>
                        <th class="py-3 px-4">Status</th>
                        {{-- <th class="py-3 px-4">Stok</th> --}}
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($data as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ date('d M Y', strtotime($item->dos)) }}</td>
                            <td class="py-3 px-4">{{ $item->stationery['kode_barang'] ?? 'Barang tidak ditemukan' }}</td>
                            <td class="py-3 px-4 text-capitalize">
                                {{ $item->stationery['nama_barang'] ?? 'Barang tidak ditemukan' }}</td>
                            <td class="py-3 px-4 text-uppercase">
                                {{ $item->stationery['satuan'] ?? 'Barang tidak ditemukan' }}</td>
                            <td class="py-3 px-4">{{ $item->amount }}</td>
                            <td class="py-3 px-4">{{ $item->notes }}</td>
                            <td class="py-3 px-4">
                                @if ($item->status == 0)
                                    <span class="badge bg-warning">Belum Disetujui</span>
                                @else
                                    <span class="badge bg-success">Disetujui</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($item->manager_approval == 0)
                                    <a href="{{ route('item-demand.edit', $item->id) }}"
                                        class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-pencil"></i></a>
                                    <a href="{{ route('item-demand.destroy', $item->id) }}"
                                        class="btn btn-outline-danger btn-sm" data-confirm-delete="true"><i
                                            class="bi bi-trash"></i></a>
                                @else
                                    <span class="text-muted">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('partials.pagination', ['data' => $data])
        </div>
    </div>

@endsection
