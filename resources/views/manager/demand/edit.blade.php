@extends('layouts.main')

@section('title', 'Edit Permintaan Barang')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detail & Persetujuan Permintaan</h5>
            </div>
            <div class="card-body">
                @php
                    $isUser = auth()->user()->role === 'user';
                    $editableByUser = $isUser && ($data->manager_approval === null || $data->manager_approval == 0);

                    $rolePrefix = match (auth()->user()->role) {
                        'manager' => 'item_demands',
                        'coo' => 'user_demands',
                        'admin' => 'demand',
                        default => 'item-demand', // user biasa
                    };
                @endphp

                <form action="{{ route($rolePrefix . '.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- <input type="hidden" name="dos" value="{{ old('dos', $data->dos) }}">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}"> --}}

                    {{-- Jenis Barang (untuk user) --}}
                    @if ($editableByUser)
                        <div class="mb-3">
                            <label for="jenis_barang" class="form-label">Jenis Barang</label>
                            <select name="jenis_barang" id="jenis_barang" class="form-control">
                                <option value="1" {{ $data->stationery->jenis_barang == 1 ? 'selected' : '' }}>Alat
                                    Tulis</option>
                                <option value="2" {{ $data->stationery->jenis_barang == 2 ? 'selected' : '' }}>
                                    Perlengkapan Lainnya</option>
                            </select>
                        </div>
                    @endif

                    {{-- Nama Barang --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        @if ($editableByUser)
                            <select name="stationery_id" id="stationery_id" class="form-control">
                                <option value="{{ $data->stationery_id }}">{{ $data->stationery->nama_barang }}</option>
                                {{-- kamu bisa load dynamic options pakai JS --}}
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $data->stationery->nama_barang }}"
                                disabled>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="text" class="form-control" id="stok" value="{{ $data->stationery->stok }}"
                            readonly>
                    </div>

                    {{-- Jumlah --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah Permintaan</label>
                        @if ($editableByUser || auth()->user()->role === 'manager')
                            <input type="number" name="amount" id="jumlah" class="form-control"
                                value="{{ $data->amount }}">
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
                        @if (!$isUser)
                            <button name="action" value="approve" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Setujui
                            </button>

                            @if (auth()->user()->role !== 'manager')
                                <button name="action" value="reject" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            @endif
                        @else
                            @if ($editableByUser)
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                            @else
                                <div class="alert alert-warning">Pengajuan telah disetujui dan tidak dapat diedit.</div>
                            @endif
                        @endif

                        <a href="{{ route($rolePrefix . '.show', $data->id) }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JS opsional untuk load barang dinamis --}}
    @if ($editableByUser)
        <script>
            $(document).ready(function() {
                $('#jenis_barang').on('change', function() {
                    const jenis = $(this).val();
                    $('#stationery_id').html('<option>Memuat...</option>');

                    $.get(`{{ url('user/get-stationery') }}?jenis=${jenis}`, function(res) {
                        let html = '';
                        res.forEach(item => {
                            html +=
                                `<option value="${item.id}" data-stok="${item.stok}">${item.nama_barang}</option>`;
                        });
                        $('#stationery_id').html(html);
                    });
                });

                $('#stationery_id').on('change', function() {
                    const stok = $('option:selected', this).data('stok');
                    $('#stok').val(stok || '');
                });

                $('#jumlah').on('input', function() {
                    const jumlah = parseInt($(this).val());
                    const stok = parseInt($('#stok').val());

                    if (jumlah > stok) {
                        Swal.fire('Error', 'Jumlah melebihi stok', 'error');
                        $(this).val('');
                    }
                });
            });
        </script>
    @endif
@endsection
