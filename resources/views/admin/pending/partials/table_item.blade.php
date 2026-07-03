{{-- Table: Per Barang --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase" style="width: 50px;">No</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">Nama Barang</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Stok Saat Ini</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Jumlah User</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Total Diminta</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Status Pipeline</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Ketersediaan</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $item)
                @php
                    $stok = $item->stationery->stok ?? 0;
                    $rasio = $stok > 0 ? round(($item->total_amount / $stok) * 100) : 100;
                    $isKritis = $item->total_amount > $stok;
                @endphp
                <tr class="{{ $isKritis ? 'table-warning' : '' }}">
                    <td class="py-3 px-4">{{ $data->firstItem() + $key }}</td>
                    <td class="py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 36px; height: 36px; min-width: 36px; background: {{ $isKritis ? '#fef2f2' : '#ecfdf5' }}; color: {{ $isKritis ? '#dc2626' : '#059669' }};">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-uppercase">{{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}</div>
                                <div class="small text-muted">{{ $item->stationery->kode_barang ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="fw-semibold {{ $stok <= 10 ? 'text-danger' : 'text-dark' }}">{{ $stok }}</span>
                        <div class="small text-muted">{{ $item->stationery->satuan ?? 'pcs' }}</div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-people me-1"></i>{{ $item->total_users }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="fw-bold {{ $isKritis ? 'text-danger' : 'text-dark' }}">{{ $item->total_amount }}</span>
                        @if($isKritis)
                            <div class="small text-danger fw-semibold">
                                <i class="bi bi-exclamation-triangle"></i> Melebihi stok!
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            @if($item->waiting_manager > 0)
                                <span class="badge rounded-pill" style="background-color: #f59e0b;" title="Menunggu Manager">
                                    <i class="bi bi-person-badge"></i> {{ $item->waiting_manager }}
                                </span>
                            @endif
                            @if($item->waiting_coo > 0)
                                <span class="badge rounded-pill bg-primary" title="Menunggu Wadirum">
                                    <i class="bi bi-person-check"></i> {{ $item->waiting_coo }}
                                </span>
                            @endif
                            @if($item->waiting_admin > 0)
                                <span class="badge rounded-pill bg-success" title="Menunggu Gudang">
                                    <i class="bi bi-box-seam"></i> {{ $item->waiting_admin }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="progress" style="height: 8px; width: 80px; margin: 0 auto;" title="{{ $rasio }}% dari stok diminta">
                            <div class="progress-bar {{ $rasio > 100 ? 'bg-danger' : ($rasio > 75 ? 'bg-warning' : 'bg-success') }}"
                                style="width: {{ min($rasio, 100) }}%"></div>
                        </div>
                        <div class="small text-muted mt-1">{{ min($rasio, 100) }}%</div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <a href="{{ route('pending.detail.item', $item->stationery_id) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-5 text-center">
                        <div class="text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <span>Tidak ada permintaan pending</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
