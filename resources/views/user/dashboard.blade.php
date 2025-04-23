@extends('layouts.main')

@section('title', 'User Page')

@section('content')

    <!-- Status Pengajuan Barang -->
    <div class="row mt-4">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Status Pengajuan Barang</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pengajuanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Aktivitas -->
        <div class="col-md">
            <div class="card">
                <div class="card-header">Log Aktivitas Terbaru</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($aktivitas as $log)
                            <li class="list-group-item">
                                Mengajukan <strong>{{ $log['nama_barang'] }}</strong>
                                sebanyak <strong>{{ $log['jumlah'] . ' ' . $log['satuan'] }}</strong>,
                                {{ $log['waktu'] }}
                                - Status:
                                {{ $log['disetujui'] ? 'Disetujui' : 'Menunggu persetujuan' }}
                            </li>
                        @empty
                            <li>Belum ada aktivitas.</li>
                        @endforelse
                    </ul>

                    {{-- <h3>Aktivitas Terbaru</h3>
                    <ul>
                        @forelse($aktivitas as $log)
                            <li>
                                Mengajukan <strong>{{ $log['nama_barang'] }}</strong>
                                sebanyak <strong>{{ $log['jumlah'] }}</strong>,
                                {{ $log['waktu'] }}
                                - Status:
                                {{ $log['disetujui'] ? 'Disetujui' : 'Menunggu persetujuan' }}
                            </li>
                        @empty
                            <li>Belum ada aktivitas.</li>
                        @endforelse
                    </ul> --}}

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('pengajuanChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Disetujui', 'Belum Disetujui'],
                datasets: [{
                    label: 'Status Pengajuan',
                    data: [{{ $disetujui }}, {{ $menunggu }}],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            }
        });
    </script>

@endsection
