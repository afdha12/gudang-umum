@extends('layouts.main')

@section('title', 'Cetak Data Permintaan Barang')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="d-flex m-3">
            <div>
                <form id="print-form" action="{{ route('list_demands.store') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="selected" id="selected-items">
                    <button type="submit" id="print-selected" class="btn btn-success"><i class="bi bi-file-earmark-pdf"></i>
                        Cetak yang Dipilih</button>
                </form>
                {{-- <a href="{{ route('print.create') }}" class="btn btn-primary">cek</a> --}}
            </div>
            <div class="row ms-auto">
                <div class="col">
                    <form action="{{ route('list_demands.index') }}" method="GET" id="filter-form">
                        <select name="division_id" class="form-select"
                            onchange="document.getElementById('filter-form').submit();">
                            <option value="">-- Semua Divisi --</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->division_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col">
                    <input type="text" class="form-control ml-3" placeholder="Cari barang..." id="search">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="p-3"><input type="checkbox" id="select-all"></th>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Pengaju</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Unit/Divisi</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvedItems as $index => $item)
                        <tr>
                            <td class="p-3">
                                <input type="checkbox" class="item-checkbox" value="{{ $item->id }}">
                            </td>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="text-capitalize p-3">{{ $item->user->name ?? 'User tidak ditemukan' }}</td>
                            <td class="text-capitalize p-3">{{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td class="text-uppercase p-3">{{ $item->user->division->division_name }}</td>
                            <td class="p-3">{{ $item->amount }}</td>
                            <td class="p-3">{{ date('d M Y', strtotime($item->dos)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('partials.pagination', ['data' => $approvedItems])
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Select All Checkbox
            $('#select-all').change(function() {
                $('.item-checkbox').prop('checked', this.checked);
            });

            // Kirim data checkbox yang dipilih ke form cetak
            $('#print-form').submit(function(event) {
                let selectedItems = $('.item-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selectedItems.length === 0) {
                    event.preventDefault(); // Batalkan submit jika tidak ada yang dipilih
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Silakan pilih setidaknya satu item untuk dicetak!',
                        confirmButtonColor: '#d33',
                    });
                } else {
                    // Jika ada yang dipilih, masukkan nilai checkbox ke input hidden
                    $('#selected-items').val(selectedItems.join(','));
                }

            });
        });
    </script>

@endsection
