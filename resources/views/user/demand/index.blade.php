@extends('layouts.main')

@section('title', 'Daftar Pengajuan - ' . $currentUser->name)

@section('content')
    <div class="mb-4">
        <h4 class="text-xl font-semibold">Daftar Permintaan Berdasarkan Tanggal</h4>
    </div>

    @php
        $rolePrefix = match (auth()->user()->role) {
            'manager' => 'item_demands',
            'coo' => 'user_demands',
            'admin' => 'demand',
            default => 'item-demand', // user biasa
        };
    @endphp

    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="flex flex-row m-3">
            <a class="btn btn-primary me-auto" href="{{ route('item-demand.create') }}">Buat Permintaan</a>

            <div >
                <span class="font-semibold">Total Permintaan bulan ini:</span>
                <span class="{{ $totalHargaBulanIni > $limitBulanIni ? 'text-red-600 font-bold' : 'text-green-600' }}">
                    Rp {{ number_format($totalHargaBulanIni, 0, ',', '.') }}
                </span>
                @if ($totalHargaBulanIni > $limitBulanIni)
                    <span class="ml-2 text-red-500 font-semibold">⚠️ Melebihi batas bulanan!</span>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Tanggal Permintaan</th>
                        <th class="py-3 px-4">Total Item</th>
                        <th class="py-3 px-4">Status</th>
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
                                @php
                                    $status = '';
                                    $badgeClass = '';
                                    
                                    if ($item->rejected_items > 0) {
                                        $status = 'Beberapa Item Ditolak';
                                        $badgeClass = 'bg-danger';
                                    } 
                                    elseif ($item->pending_admin > 0) {
                                        $status = 'Menunggu Persetujuan Admin';
                                        $badgeClass = 'bg-warning text-dark';
                                    }
                                    elseif ($item->pending_coo > 0) {
                                        $status = 'Menunggu Persetujuan Wadirum';
                                        $badgeClass = 'bg-warning text-dark';
                                    }
                                    elseif ($item->pending_manager > 0) {
                                        $status = 'Menunggu Persetujuan Manager';
                                        $badgeClass = 'bg-warning text-dark';
                                    }
                                    elseif ($item->total_pengajuan == $item->approved_items) {
                                        $status = 'Semua Disetujui';
                                        $badgeClass = 'bg-success';
                                    }
                                @endphp
                                
                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                            </td>

                            <td class="py-3 px-4">
                                <a href="{{ route($rolePrefix . '.edit_by_date', ['user' => $currentUser->id, 'date' => $item->dos]) }}"
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
