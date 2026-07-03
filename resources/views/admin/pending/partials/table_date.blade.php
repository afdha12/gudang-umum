{{-- Table: Per Tanggal --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase" style="width: 50px;">No</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">Tanggal Pengajuan</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Jumlah User</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Total Item</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Total Jumlah</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Status Pipeline</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $item)
                <tr>
                    <td class="py-3 px-4">{{ $data->firstItem() + $key }}</td>
                    <td class="py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 36px; height: 36px; min-width: 36px; background: #ede9fe; color: #7c3aed;">
                                <i class="bi bi-calendar-date"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($item->dos)->format('d M Y') }}</div>
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($item->dos)->translatedFormat('l') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-people me-1"></i>{{ $item->total_users }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="badge bg-light text-dark border">{{ $item->total_items }} item</span>
                    </td>
                    <td class="py-3 px-4 text-center fw-semibold">{{ $item->total_amount }}</td>
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
                        <a href="{{ route('pending.detail.date', $item->dos) }}"
                            class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-5 text-center">
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
