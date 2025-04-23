@extends('layouts.main')

@section('title', 'Edit Permintaan Barang')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detail & Persetujuan Permintaan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('item_demands.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Info barang --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" value="{{ $data->stationery->nama_barang }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="text" class="form-control" value="{{ $data->stationery->stok }}" disabled>
                    </div>

                    {{-- Jumlah (editable only by manager) --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah Permintaan</label>
                        @if (auth()->user()->role === 'manager')
                            <input type="number" name="amount" class="form-control" value="{{ $data->amount }}">
                        @else
                            <input type="text" class="form-control" value="{{ $data->amount }}" disabled>
                        @endif
                    </div>

                    {{-- Tambah Catatan --}}
                    <div class="mb-3">
                        <label for="notes" class="form-label">Tambah Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Tuliskan catatan tambahan..."></textarea>
                    </div>

                    {{-- Riwayat Catatan --}}
                    @if ($data->notes)
                        <div class="mb-4">
                            <label class="form-label">Riwayat Catatan</label>
                            <div class="border rounded bg-light p-3" style="white-space: pre-line;">
                                {{ $data->notes }}
                            </div>
                        </div>
                    @endif

                    {{-- Tombol aksi --}}
                    <div class="d-flex gap-2">
                        <button name="action" value="approve" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Setujui
                        </button>

                        @if (auth()->user()->role !== 'manager')
                            <button name="action" value="reject" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Tolak
                            </button>
                        @endif

                        <a href="{{ route('item_demands.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
