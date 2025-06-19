@extends('layouts.main')

@section('title', 'Tambah Data Stok Barang')

@section('content')
    <div class="flex justify-center">
        <div class="w-full max-w-lg overflow-y-auto border shadow-lg rounded-lg p-5">
            <form action="{{ route('item-demand.store') }}" method="POST">
                @csrf
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Input Tanggal (Hidden) -->
                <input type="hidden" name="dos" value="{{ date('Y-m-d') }}">

                <!-- Input User ID (Hidden) -->
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                <div class="mb-4">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <select id="jenis_barang" name="jenis_barang" class="form-control">
                        <option value="">Pilih Jenis Barang</option>
                        <option value="1">Alat Tulis & Perlengkapan</option>
                        {{-- <option value="2">Perlengkapan Lainnya</option> --}}
                    </select>
                    @error('jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <select id="stationery_id" name="stationery_id" class="form-control">
                        <option value="">Pilih Nama Barang</option>
                    </select>
                    @error('stationery_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" id="stok" class="form-control" readonly>
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="satuan" class="form-label">Jumlah</label>
                    <input type="number" name="amount" id="jumlah" class="form-control" required>
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
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
            $('#jenis_barang').change(function() {
                var jenis = $(this).val();
                $('#stationery_id').html('<option value="">Memuat...</option>');
                $('#stok').val('');

                if (jenis) {
                    $.ajax({
                        url: "{{ url('user/get-stationery') }}", // Pastikan ini sesuai dengan prefix route
                        type: "GET",
                        data: {
                            jenis: jenis
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.length > 0) {
                                var options = '<option value="">Pilih Nama Barang</option>';
                                $.each(response, function(index, item) {
                                    options += '<option value="' + item.id +
                                        '" data-stok="' + item.stok + '">' + item
                                        .nama_barang + '</option>';
                                });
                                $('#stationery_id').html(options);
                            } else {
                                $('#stationery_id').html(
                                    '<option value="">Tidak ada barang tersedia</option>');
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr);
                            // alert('Gagal mengambil data barang');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal mengambil data barang'
                            });
                        }
                    });
                } else {
                    $('#stationery_id').html('<option value="">Pilih Nama Barang</option>');
                }
            });

            $('#stationery_id').change(function() {
                var stok = $('option:selected', this).data('stok');
                $('#stok').val(stok ? stok : '');
            });
        });


        $('#jumlah').on('input', function() {
            var jumlah = parseInt($(this).val());
            var stok = parseInt($('#stok').val());

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
            var jumlah = $('#jumlah').val();
            var stok = $('#stok').val();

            if (!jumlah || jumlah <= 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Masukkan jumlah yang valid!'
                });
            } else if (parseInt(jumlah) > parseInt(stok)) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah tidak boleh lebih dari stok!'
                });
            }
        });
    </script>
@endsection
