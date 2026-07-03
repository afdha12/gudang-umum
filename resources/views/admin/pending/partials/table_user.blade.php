{{-- Table: Per User --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase" style="width: 50px;">No</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">User</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Total Item</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Total Jumlah</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Status Pipeline</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase">Permintaan Terakhir</th>
                <th class="py-3 px-4 text-muted small fw-semibold text-uppercase text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $item)
                <tr>
                    <td class="py-3 px-4">{{ $data->firstItem() + $key }}</td>
                    <td class="py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-light"
                                style="width: 36px; height: 36px; min-width: 36px;">
                                <i class="bi bi-person text-muted"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-capitalize">{{ $item->user->name ?? 'User tidak ditemukan' }}</div>
                                <div class="small text-muted">{{ $item->user->division->nama_divisi ?? '-' }}</div>
                            </div>
                        </div>
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
                    <td class="py-3 px-4">
                        <span class="small text-muted">
                            {{ \Carbon\Carbon::parse($item->last_request)->format('d M Y H:i') }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <a href="{{ route('pending.detail.user', $item->user_id) }}"
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
