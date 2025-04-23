@extends('layouts.main')

@section('title', 'Admin Page')

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
                        @forelse($logAktivitas as $log)
                            <li class="list-group-item">{{ $log->message }} ({{ $log->created_at->diffForHumans() }})
                            </li>

                        @empty
                            <li>Belum ada aktivitas.</li>
                        @endforelse
                    </ul>
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
                    data: [{{ $pengajuanDisetujui }}, {{ $pengajuanBelumDisetujui }}],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            }
        });
    </script>
@endsection
