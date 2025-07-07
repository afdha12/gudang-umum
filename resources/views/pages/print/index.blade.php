@extends('layouts.main')

@section('title', 'Cetak Data Permintaan Barang')

@section('content')

    @php
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
    @endphp

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
                <button type="button" class="px-3 py-2 rounded bg-cyan-600 text-white hover:bg-cyan-700 transition-colors"
                    onclick="document.getElementById('exportExcelModal').classList.remove('hidden')">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel Bulanan
                </button>

                <!-- Modal Export Excel -->
                <div id="exportExcelModal"
                    class="fixed inset-0 flex items-center justify-center modal-backdrop-blur z-[9999] hidden">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative">
                        <!-- Tombol close -->
                        <button type="button" onclick="document.getElementById('exportExcelModal').classList.add('hidden')"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                        <form method="GET" action="{{ route('export.bulanan') }}" class="p-6" id="exportExcelForm">
                            <h2 class="text-lg font-semibold mb-4 text-center">Pilih Bulan & Tahun</h2>
                            <div class="flex gap-4 mb-6">
                                <div class="flex-1">
                                    <label for="tahunExport" class="block text-sm font-medium mb-1">Tahun</label>
                                    <select name="tahun" id="tahunExport" class="w-full border rounded px-2 py-1"
                                        required>
                                        <option value="">Pilih Tahun</option>
                                        @foreach ($daftarTahun as $tahun)
                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label for="bulanExport" class="block text-sm font-medium mb-1">Bulan</label>
                                    <select name="bulan" id="bulanExport" class="w-full border rounded px-2 py-1"
                                        required>
                                        <option value="">Pilih Bulan</option>
                                        {{-- Bulan akan diisi via JS sesuai tahun yang dipilih --}}
                                    </select>
                                </div>
                            </div>
                            <!-- Hidden input untuk from dan to -->
                            <input type="hidden" name="from" id="fromExport">
                            <input type="hidden" name="to" id="toExport">
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    onclick="document.getElementById('exportExcelModal').classList.add('hidden')"
                                    class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Download</button>
                            </div>
                        </form>
                    </div>
                </div>

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

    <script>
        // Cegah modal tertutup jika klik backdrop
        document.getElementById('exportExcelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                // Tidak melakukan apa-apa (modal tidak tertutup)
            }
        });

        // Data periode dari backend
        const periods = @json($availablePeriods);

        // Saat tahun dipilih, filter bulan yang tersedia
        document.getElementById('tahunExport').addEventListener('change', function() {
            const tahun = this.value;
            const bulanSelect = document.getElementById('bulanExport');
            bulanSelect.innerHTML = '<option value="">Pilih Bulan</option>';
            if (!tahun) return;
            const bulanIndo = [
                '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];
            const bulanTersedia = periods.filter(p => p.tahun == tahun).map(p => p.bulan);
            bulanTersedia.sort((a, b) => a - b);
            bulanTersedia.forEach(b => {
                bulanSelect.innerHTML += `<option value="${b}">${bulanIndo[b]}</option>`;
            });
        });

        // Validasi sebelum submit
        document.getElementById('exportExcelForm').addEventListener('submit', function(e) {
            var tahun = document.getElementById('tahunExport').value;
            var bulan = document.getElementById('bulanExport').value;
            // Cek apakah kombinasi tahun-bulan ada di database
            const valid = periods.some(p => p.tahun == tahun && p.bulan == bulan);
            if (!valid) {
                e.preventDefault();
                alert('Bulan dan tahun yang dipilih tidak tersedia dalam data!');
                return false;
            }
            // Hitung tanggal awal dan akhir bulan
            var from = tahun + '-' + String(bulan).padStart(2, '0') + '-01';
            var lastDay = new Date(tahun, bulan, 0).getDate();
            var to = tahun + '-' + String(bulan).padStart(2, '0') + '-' + lastDay;
            document.getElementById('fromExport').value = from;
            document.getElementById('toExport').value = to;
            // Tutup modal setelah submit
            document.getElementById('exportExcelModal').classList.add('hidden');
        });
    </script>
@endsection
