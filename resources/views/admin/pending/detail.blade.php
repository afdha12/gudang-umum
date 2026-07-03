@extends('layouts.main')

@section('title', 'Detail Pending - ' . $user->name)

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('pending.index', ['view' => 'user']) }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Monitoring
                </a>
                <h4 class="fw-bold text-dark mb-0 mt-1">
                    <i class="bi bi-person-circle me-2"></i>Permintaan Pending: <span class="text-capitalize">{{ $user->name }}</span>
                </h4>
                <p class="text-muted mb-0 small">{{ $user->division->nama_divisi ?? 'Divisi tidak diketahui' }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase" style="width: 50px;">No</th>
                                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">Nama Barang</th>
                                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Jumlah</th>
                                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">Tanggal Pengajuan</th>
                                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Progress</th>
                                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">Status Saat Ini</th>
                                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $key => $item)
                                @php $statusDisplay = $item->getStatusDisplay(); @endphp
                                <tr>
                                    <td class="py-3 px-4">{{ $items->firstItem() + $key }}</td>
                                    <td class="py-3 px-4">
                                        <div class="fw-semibold text-uppercase">{{ $item->stationery->nama_barang ?? '-' }}</div>
                                        <div class="small text-muted">{{ $item->stationery->kode_barang ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center fw-semibold">{{ $item->amount }}</td>
                                    <td class="py-3 px-4">
                                        <div>{{ \Carbon\Carbon::parse($item->dos)->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $item->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        {{-- Progress Steps --}}
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            {{-- Manager --}}
                                            @if($item->manager_approval === 1 || $item->coo_approval === 1 || $item->status === 1)
                                                <span class="badge rounded-circle bg-success" style="width: 24px; height: 24px; line-height: 16px;" title="Manager: Disetujui">
                                                    <i class="bi bi-check" style="font-size: 10px;"></i>
                                                </span>
                                            @elseif($item->manager_approval === null)
                                                <span class="badge rounded-circle" style="width: 24px; height: 24px; line-height: 16px; background: #f59e0b;" title="Menunggu Manager">
                                                    <i class="bi bi-hourglass-split" style="font-size: 10px;"></i>
                                                </span>
                                            @endif
                                            <i class="bi bi-chevron-right text-muted" style="font-size: 8px;"></i>
                                            {{-- COO --}}
                                            @if($item->coo_approval === 1 || $item->status === 1)
                                                <span class="badge rounded-circle bg-success" style="width: 24px; height: 24px; line-height: 16px;" title="Wadirum: Disetujui">
                                                    <i class="bi bi-check" style="font-size: 10px;"></i>
                                                </span>
                                            @elseif(($item->manager_approval === 1 || $item->user->division->managed_by_coo ?? false) && $item->coo_approval === null)
                                                <span class="badge rounded-circle bg-primary" style="width: 24px; height: 24px; line-height: 16px;" title="Menunggu Wadirum">
                                                    <i class="bi bi-hourglass-split" style="font-size: 10px;"></i>
                                                </span>
                                            @else
                                                <span class="badge rounded-circle bg-secondary" style="width: 24px; height: 24px; line-height: 16px; opacity: 0.4;" title="Belum sampai Wadirum">
                                                    <i class="bi bi-dash" style="font-size: 10px;"></i>
                                                </span>
                                            @endif
                                            <i class="bi bi-chevron-right text-muted" style="font-size: 8px;"></i>
                                            {{-- Admin --}}
                                            @if($item->status === 1)
                                                <span class="badge rounded-circle bg-success" style="width: 24px; height: 24px; line-height: 16px;" title="Gudang: Disetujui">
                                                    <i class="bi bi-check" style="font-size: 10px;"></i>
                                                </span>
                                            @elseif($item->coo_approval === 1 && $item->status === null)
                                                <span class="badge rounded-circle" style="width: 24px; height: 24px; line-height: 16px; background: #10b981;" title="Menunggu Gudang">
                                                    <i class="bi bi-hourglass-split" style="font-size: 10px;"></i>
                                                </span>
                                            @else
                                                <span class="badge rounded-circle bg-secondary" style="width: 24px; height: 24px; line-height: 16px; opacity: 0.4;" title="Belum sampai Gudang">
                                                    <i class="bi bi-dash" style="font-size: 10px;"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="badge {{ $statusDisplay['class'] }} rounded-pill px-3">
                                            {{ $statusDisplay['text'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($item->notes)
                                            <div class="small text-muted" style="max-width: 200px; white-space: pre-line;">{{ Str::limit($item->notes, 80) }}</div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <span>Tidak ada permintaan pending untuk user ini</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($items->hasPages())
                <div class="card-footer bg-white border-top-0">
                    @include('partials.pagination', ['data' => $items])
                </div>
            @endif
        </div>
    </div>
@endsection
