@extends('layouts.main')

@section('title', 'Detail Permintaan Barang')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal Permintaan</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Persetujuan Manager</th>
                        {{-- <th class="py-3 px-4 text-left">Status</th> --}}
                        <th class="py-3 px-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($userDemands as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ date('d M Y', strtotime($item->dos)) }}</td>
                            <td class="py-3 px-4">{{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td class="py-3 px-4">{{ $item->amount }}</td>
                            <td class="py-3 px-4">
                                @if ($item->manager_approval)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Waiting</span>
                                @endif
                            </td>
                            {{-- <td class="py-3 px-4">
                                @if ($item->status == 0)
                                    <span class="badge bg-warning">Belum Disetujui</span>
                                @else
                                    <span class="badge bg-success">Approved</span>
                                @endif
                            </td> --}}
                            <td class="py-3 px-4">
                                @if (!$item->status)
                                    @if ($item->manager_approval == 1)
                                        <a href="{{ route('demand.update', $item->id) }}"
                                            class="btn btn-outline-primary btn-sm" data-confirm-delete="true"><i
                                                class="bi bi-check-lg"></i> Setujui</a>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled>Menunggu Approval</button>
                                    @endif
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Sudah Disetujui</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('partials.pagination', ['data' => $userDemands])

        </div>
    </div>

@endsection
