@extends('layouts.main')

@section('title', 'Monitoring Permintaan Pending')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">
                <i class="bi bi-clock-history me-2"></i>Monitoring Permintaan Pending
            </h4>
            <p class="text-muted mb-0">Pantau seluruh permintaan barang yang sedang dalam proses persetujuan</p>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase">Total Pending</div>
                                <div class="fs-3 fw-bold text-dark">{{ $totalPending }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                <i class="bi bi-hourglass-split text-white fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b !important;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase">Menunggu Manager</div>
                                <div class="fs-3 fw-bold text-dark">{{ $waitingManager }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="bi bi-person-badge text-white fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3b82f6 !important;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase">Menunggu Wadirum</div>
                                <div class="fs-3 fw-bold text-dark">{{ $waitingCoo }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                <i class="bi bi-person-check text-white fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase">Menunggu Gudang</div>
                                <div class="fs-3 fw-bold text-dark">{{ $waitingAdmin }}</div>
                            </div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="bi bi-box-seam text-white fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pipeline Visual --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                        <div class="text-center flex-fill">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-1"
                                style="width: 36px; height: 36px; background: #fef3c7; color: #d97706;">
                                <i class="bi bi-1-circle-fill"></i>
                            </div>
                            <div class="small fw-semibold text-muted">Manager</div>
                            <span class="badge bg-warning text-dark">{{ $waitingManager }}</span>
                        </div>
                        <div class="text-muted"><i class="bi bi-arrow-right"></i></div>
                        <div class="text-center flex-fill">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-1"
                                style="width: 36px; height: 36px; background: #dbeafe; color: #2563eb;">
                                <i class="bi bi-2-circle-fill"></i>
                            </div>
                            <div class="small fw-semibold text-muted">Wadirum</div>
                            <span class="badge bg-primary">{{ $waitingCoo }}</span>
                        </div>
                        <div class="text-muted"><i class="bi bi-arrow-right"></i></div>
                        <div class="text-center flex-fill">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-1"
                                style="width: 36px; height: 36px; background: #d1fae5; color: #059669;">
                                <i class="bi bi-3-circle-fill"></i>
                            </div>
                            <div class="small fw-semibold text-muted">Gudang</div>
                            <span class="badge bg-success">{{ $waitingAdmin }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Navigation & Search --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-1 py-3">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    {{-- View Tabs --}}
                    <ul class="nav nav-pills gap-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $view === 'user' ? 'active' : '' }} px-3 py-2 rounded-pill"
                                href="{{ route('pending.index', ['view' => 'user', 'search' => $search]) }}"
                                style="{{ $view === 'user' ? 'background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none;' : 'color: #6366f1;' }}">
                                <i class="bi bi-people me-1"></i> Per User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $view === 'date' ? 'active' : '' }} px-3 py-2 rounded-pill"
                                href="{{ route('pending.index', ['view' => 'date', 'search' => $search]) }}"
                                style="{{ $view === 'date' ? 'background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none;' : 'color: #6366f1;' }}">
                                <i class="bi bi-calendar3 me-1"></i> Per Tanggal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $view === 'item' ? 'active' : '' }} px-3 py-2 rounded-pill"
                                href="{{ route('pending.index', ['view' => 'item', 'search' => $search]) }}"
                                style="{{ $view === 'item' ? 'background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none;' : 'color: #6366f1;' }}">
                                <i class="bi bi-box me-1"></i> Per Barang
                            </a>
                        </li>
                    </ul>

                    {{-- Search --}}
                    <form method="GET" action="{{ route('pending.index') }}" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="view" value="{{ $view }}">
                        <div class="position-relative" style="min-width: 260px;">
                            <i class="bi bi-search text-muted position-absolute"
                                style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 14px;"></i>
                            <input type="text" name="search" class="form-control rounded-pill shadow-sm"
                                style="padding-left: 36px; padding-right: {{ $search ? '36px' : '12px' }}; border: 1px solid #e2e8f0;"
                                placeholder="{{ $view === 'user' ? 'Cari nama user...' : ($view === 'date' ? 'Cari tanggal (YYYY-MM-DD)...' : 'Cari nama barang...') }}"
                                value="{{ $search }}">
                            @if ($search)
                                <a href="{{ route('pending.index', ['view' => $view]) }}"
                                    class="position-absolute text-muted"
                                    style="right: 12px; top: 50%; transform: translateY(-50%); text-decoration: none;">
                                    <i class="bi bi-x-lg" style="font-size: 12px;"></i>
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-3 shadow-sm">Cari</button>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                {{-- Table Content Based on View --}}
                @if ($view === 'user')
                    @include('admin.pending.partials.table_user')
                @elseif($view === 'date')
                    @include('admin.pending.partials.table_date')
                @elseif($view === 'item')
                    @include('admin.pending.partials.table_item')
                @endif
            </div>

            @if ($data->hasPages())
                <div class="card-footer bg-white border-top-0">
                    @include('partials.pagination', ['data' => $data])
                </div>
            @endif
        </div>
    </div>
@endsection
