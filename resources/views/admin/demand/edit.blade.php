@extends('layouts.main')

@section('title', 'Edit Permintaan Barang')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detail & Persetujuan Permintaan</h5>
                <small>{{ $user->name }} - {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</small>
            </div>
            <div class="card-body">
                <form action="{{ route('item_demands.update_by_date', ['user' => $user->id, 'date' => $date]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    @foreach ($items as $item)
                        <div class="mb-4 p-3 border rounded">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <div>
                                        <h6 class="text-uppercase">{{ $item->stationery->nama_barang }}</h6>
                                        <p class="my-2">Stok: <strong>{{ $item->stationery->stok }}</strong>
                                            {{ $item->stationery->satuan }}</p>
                                        <p class="mb-2">Harga/item:
                                            <strong>Rp{{ number_format($item->stationery->harga_barang, 0, ',', '.') }}</strong>
                                        </p>
                                        <p class="mb-2">
                                            Total Harga:
                                            <strong class="total-harga" data-id="{{ $item->id }}">
                                                Rp{{ number_format($item->amount * $item->stationery->harga_barang, 0, ',', '.') }}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-label">Jumlah Permintaan</label>
                                    <input type="number" name="amount[{{ $item->id }}]" value="{{ $item->amount }}"
                                        class="form-control form-control-sm jumlah-permintaan" min="1"
                                        data-id="{{ $item->id }}" data-harga="{{ $item->stationery->harga_barang }}">

                                    <label class="form-label mt-2">Catatan Tambahan</label>
                                    <textarea name="notes[{{ $item->id }}]" class="form-control" rows="1">{{ old("notes.$item->id") }}</textarea>
                                </div>
                            </div>

                            @if ($item->notes)
                                <div class="mt-2">
                                    <label class="form-label">Riwayat Catatan</label>
                                    <div class="bg-light p-2 border rounded" style="white-space: pre-line;">
                                        {{ $item->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    {{-- Total Harga Keseluruhan --}}
                    <div class="mb-3">
                        <h5>Total Semua: <span id="grand-total">
                                Rp{{ number_format($items->sum(fn($item) => $item->amount * $item->stationery->harga_barang), 0, ',', '.') }}
                            </span></h5>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" name="action" value="approve" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Setujui Semua
                        </button>
                        <a href="{{ route('item_demands.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.jumlah-permintaan');

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format.(angka);
            }

            function updateGrandTotal() {
                let grandTotal = 0;
                document.querySelectorAll('.jumlah-permintaan').forEach(input => {
                    const jumlah = parseInt(input.value) || 0;
                    const harga = parseInt(input.dataset.harga) || 0;
                    grandTotal += jumlah * harga;
                });
                document.getElementById('grand-total').textContent = formatRupiah(grandTotal);
            }

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const jumlah = parseInt(this.value) || 0;
                    const harga = parseInt(this.dataset.harga) || 0;
                    const id = this.dataset.id;
                    const totalElement = document.querySelector('.total-harga[data-id="' + id +
                        '"]');

                    if (totalElement) {
                        const total = jumlah * harga;
                        totalElement.textContent = formatRupiah(total);
                    }

                    updateGrandTotal();
                });
            });

            // Hitung grand total saat awal halaman dimuat
            updateGrandTotal();
        });
    </script>
@endsection
