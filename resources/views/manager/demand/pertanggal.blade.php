@extends('layouts.main')

@section('title', 'Detail Pengajuan - ' . $user->name)

@section('content')
    <div class="mb-4">
        <h4 class="text-xl font-semibold">Daftar Permintaan oleh {{ $user->name }}</h4>
    </div>

    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Tanggal Permintaan</th>
                        <th class="py-3 px-4">Total Item</th>
                        <th class="py-3 px-4">Menunggu Persetujuan</th>
                        <th class="py-3 px-4">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($data as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->dos)->format('d-m-Y') }}</td>
                            <td class="py-3 px-4">{{ $item->total_pengajuan }}</td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $item->item_status > 0 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $item->item_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('item_demands.edit_by_date', ['user' => $user->id, 'date' => $item->dos]) }}"
                                    class="btn btn-outline-primary btn-sm">Lihat detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('partials.pagination', ['data' => $data])
        </div>
    </div>
@endsection
