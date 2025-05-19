@extends('layouts.main')

@section('title', 'Pengajuan Barang')

@section('content')
    <div class="container max-w-2xl mx-auto">
        {{-- <form id="pengajuanForm" action="{{ route('item-demand.storeMultiple') }}" method="POST"> --}}
        <form action="{{ route('item-demand.store') }}" method="POST">
            @csrf

            <!-- Hidden -->
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

            <!-- Form Pencarian Barang -->
            <div class="bg-white shadow p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="mb-2">Nama Barang</label>
                        <select id="stationery_id" class="form-control">
                            <option value="">Pilih Barang</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2">Stok</label>
                        <input type="number" id="stok" class="form-control" readonly>
                    </div>

                    <div>
                        <label class="mb-2">Jumlah</label>
                        <input type="number" id="jumlah" class="form-control">
                    </div>

                    <button type="button" id="tambahBarang" class="btn btn-success">Tambah Barang</button>
                </div>
            </div>

            <!-- List Barang yang Ditambahkan -->
            <div class="bg-white shadow p-4 rounded-lg">
                <h4 class="font-bold mb-3">Daftar Barang Diajukan</h4>
                <table class="table table-bordered" id="tabelBarang">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- List item akan ditambahkan dengan JS -->
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Ajukan</button>
            </div>
        </form>
    </div>

    <!-- Script -->
    <script>
        let daftarBarang = [];

        $(document).ready(function() {
            // Saat halaman dimuat, langsung ambil semua barang
            $.ajax({
                url: "{{ url('user/get-stationery') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.length > 0) {
                        var options = '<option value="">Pilih Nama Barang</option>';
                        $.each(response, function(index, item) {
                            options += '<option value="' + item.id +
                                '" data-stok="' + item.stok + '" data-nama_barang="' + item
                                .nama_barang + '">' + item.nama_barang + '</option>';

                        });
                        $('#stationery_id').html(options);
                    } else {
                        $('#stationery_id').html('<option value="">Tidak ada barang tersedia</option>');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data barang'
                    });
                }
            });

            // Saat barang dipilih, tampilkan stok
            $('#stationery_id').change(function() {
                var stok = $('option:selected', this).data('stok');
                $('#stok').val(stok ? stok : '');
            });
        });


        $('#stationery_id').change(function() {
            const stok = $('option:selected', this).data('stok');
            $('#stok').val(stok || '');
        });

        $('#tambahBarang').click(function() {
            const id = $('#stationery_id').val();
            const nama = $('#stationery_id option:selected').data('nama_barang');
            const jumlah = $('#jumlah').val();

            if (!id || !jumlah) {
                Swal.fire({
                    icon: 'error',
                    text: 'Barang dan jumlah harus diisi'
                });
                return;
            }

            daftarBarang.push({
                id,
                nama,
                jumlah
            });

            renderTabel();

            // reset form atas
            $('#stationery_id').val('');
            $('#stok').val('');
            $('#jumlah').val('');
        });

        function renderTabel() {
            let html = '';
            $('#tabelBarang tbody').empty();
            daftarBarang.forEach((item, index) => {
                html += `
                <tr>
                    <td>
                        ${item.nama}
                        <input type="hidden" name="items[${index}][stationery_id]" value="${item.id}">
                    </td>
                    <td>
                        ${item.jumlah}
                        <input type="hidden" name="items[${index}][amount]" value="${item.jumlah}">
                    </td>
                    <td><button type="button" onclick="hapusItem(${index})" class="btn btn-danger btn-sm">Hapus</button></td>
                </tr>
            `;
            });
            $('#tabelBarang tbody').html(html);
        }

        function hapusItem(index) {
            daftarBarang.splice(index, 1);
            renderTabel();
        }
    </script>
@endsection
