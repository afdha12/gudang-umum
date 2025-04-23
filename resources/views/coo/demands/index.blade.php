@extends('layouts.main')

@section('title', 'Data Permintaan Barang')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        {{-- <div class="m-3">
            <a class="btn btn-primary" href="{{ route('stationeries.create') }}">Tambah Data Barang</a>
        </div> --}}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Permintaan Terbaru</th>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Total Permintaan</th>
                        <th class="py-3 px-4 text-left">Menunggu Persetujuan</th>
                        <th class="py-3 px-4 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($data as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ date('d-m-Y', strtotime($item->last_pengajuan)) }}</td>
                            <td class="py-3 px-4">{{ $item->user->name ?? 'User Tidak Ditemukan' }}</td>
                            <td class="py-3 px-4">{{ $item->total_pengajuan }}</td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $item->item_status > 0 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $item->item_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('user_demands.show', $item->user_id) }}"
                                    class="btn btn-outline-primary btn-sm mr-2">Lihat detail</a>
                                {{-- <a href="{{ route('stationeries.destroy', $item->id) }}"
                                    class="btn btn-outline-danger btn-sm" data-confirm-delete="true"><i
                                        class="bi bi-trash"></i></a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('partials.pagination', ['data' => $data])
            
        </div>
    </div>

@endsection
