@extends('layouts.main')

@section('title', 'Cetak Data Permintaan Barang')

@section('content')

    {{-- @php
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $daftarTahun = $availablePeriods->pluck('tahun')->unique()->sortDesc();
    @endphp --}}

    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="flex flex-row gap-2 m-3">
            <div>
                <form id="print-form" action="{{ route('list_demands.store') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    <input type="hidden" name="from" value="{{ request('from') }}">
                    <input type="hidden" name="to" value="{{ request('to') }}">
                    <button type="submit" id="print-selected"
                        class="px-3 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak Data
                    </button>
                </form>
            </div>
            <div class="me-auto">
                <!-- Tombol trigger modal -->
                @include('components.export-excel-button', ['btn' => 'Export Data'])
                <!-- Modal Export Excel -->
                @include('components.export-excel-modal', [
                    'availablePeriods' => $availablePeriods,
                    'action' => route('export.bulanan'),
                ])

            </div>
            <form action="{{ route('list_demands.index') }}" method="GET" id="filter-form" class="d-flex gap-2">
                <div>
                    <select name="user_id" class="w-full border rounded" onchange="this.form.submit();">
                        <option value="">Semua Unit</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="text" name="from" class="w-full border rounded datepicker"
                        placeholder="Pilih Tanggal Dari" value="{{ request('from') }}">
                </div>
                <div>
                    <input type="text" name="to" class="w-full border rounded datepicker"
                        placeholder="Pilih Tanggal Sampai" value="{{ request('to') }}">
                </div>
                <div>
                    <button type="submit"
                        class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Filter</button>
                </div>
                <div>
                    @if (request('user_id') || request('from') || request('to'))
                        <a href="{{ route('list_demands.index') }}" class="btn btn-secondary">Hapus Filter</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Pengaju</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Unit/Divisi</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvedItems as $index => $item)
                        <tr>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="text-capitalize p-3">{{ $item->user->name ?? 'User tidak ditemukan' }}</td>
                            <td class="text-capitalize p-3">
                                {{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}
                            </td>
                            <td class="text-uppercase p-3">
                                {{ $item->user->division->division_name ?? 'Divisi tidak ditemukan' }}</td>
                            <td class="p-3">{{ $item->amount }}</td>
                            <td class="p-3">{{ date('d M Y', strtotime($item->dos)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('partials.pagination', ['data' => $approvedItems])
        </div>
    </div>
    
    @include('components.export-excel-script', ['availablePeriods' => $availablePeriods])

@endsection
