@extends('layouts.main')

@section('title', 'Edit Pengajuan Barang')

@section('content')
    <div class="flex justify-center">
        <div class="w-full max-w-lg overflow-y-auto border shadow-lg rounded-lg p-5">
            <form action="{{ route('item-demand.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <input type="hidden" name="dos" value="{{ old('dos', $data->dos) }}">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <div class="mb-4">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <select id="jenis_barang" name="jenis_barang" class="form-control">
                        <option value="1"
                            {{ old('jenis_barang', $data->stationery->jenis_barang) == '1' ? 'selected' : '' }}>Alat Tulis
                        </option>
                        <option value="2"
                            {{ old('jenis_barang', $data->stationery->jenis_barang) == '2' ? 'selected' : '' }}>Perlengkapan
                            Lainnya</option>
                    </select>
                    @error('jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stationery_id" class="form-label">Nama Barang</label>
                    <select id="stationery_id" name="stationery_id" class="form-control">
                        <option value="{{ $data->stationery_id }}" selected>
                            {{ $data->stationery->nama_barang ?? 'Barang sebelumnya' }}
                        </option>
                    </select>
                    @error('stationery_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" id="stok" class="form-control" readonly
                        value="{{ $data->stationery->stok }}">
                </div>

                <div class="mb-4">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" name="amount" id="jumlah" class="form-control"
                        value="{{ old('amount', $data->amount) }}">
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" id="submit-btn" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            const selectedStationeryId = "{{ old('stationery_id', $data->stationery_id) }}";

            function loadStationeryList(jenis) {
                $('#stationery_id').html('<option value="">Memuat...</option>');
                $('#stok').val('');

                $.ajax({
                    url: "{{ url('user/get-stationery') }}",
                    type: "GET",
                    data: {
                        jenis: jenis
                    },
                    dataType: "json",
                    success: function(response) {
                        let options = '<option value="">Pilih Nama Barang</option>';
                        $.each(response, function(index, item) {
                            const selected = item.id == selectedStationeryId ? 'selected' : '';
                            options +=
                                `<option value="${item.id}" data-stok="${item.stok}" ${selected}>${item.nama_barang}</option>`;
                        });
                        $('#stationery_id').html(options);

                        const selected = $('#stationery_id option:selected');
                        $('#stok').val(selected.data('stok') || '');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil data barang'
                        });
                    }
                });
            }

            $('#jenis_barang').change(function() {
                const jenis = $(this).val();
                if (jenis) loadStationeryList(jenis);
            });

            $('#stationery_id').change(function() {
                const stok = $('option:selected', this).data('stok');
                $('#stok').val(stok || '');
            });

            $('#jumlah').on('input', function() {
                const jumlah = parseInt($(this).val());
                const stok = parseInt($('#stok').val());

                if (jumlah > stok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Jumlah melebihi stok yang tersedia!'
                    });
                    $(this).val('');
                }
            });

            $('#submit-btn').click(function(event) {
                const jumlah = $('#jumlah').val();
                const stok = $('#stok').val();

                if (!jumlah || jumlah <= 0 || parseInt(jumlah) > parseInt(stok)) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Jumlah tidak valid atau melebihi stok!'
                    });
                }
            });

            // Load barang otomatis saat halaman pertama kali dibuka
            const initialJenis = $('#jenis_barang').val();
            if (initialJenis) {
                loadStationeryList(initialJenis);
            }
        });
    </script>
@endsection
