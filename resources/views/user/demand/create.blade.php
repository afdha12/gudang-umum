@extends('layouts.main')

@section('title', 'Pengajuan Barang')

@section('content')
    <div class="container max-w-7xl mx-auto mt-4 px-4">
        <form action="{{ route('item-demand.store') }}" method="POST">
            @csrf

            <!-- Hidden -->
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

            <!-- Form Pencarian Barang -->
            <div class="bg-white shadow p-4 rounded-lg mb-6">
                <div class="grid grid-cols-1 gap-4">
                    <div class="relative">
                        <label for="searchBarang" class="block mb-2 font-medium text-sm text-gray-700">Nama Barang</label>
                        <input type="text" id="searchBarang" class="w-full px-3 py-1.5 border rounded mb-2"
                            placeholder="Cari nama barang..." autocomplete="off">
                        <div id="dropdownBarang" class="bg-white border rounded shadow absolute z-10 w-full"
                            style="display:none; max-height:200px; overflow-y:auto;"></div>
                        <input type="hidden" id="stationery_id" name="stationery_id">
                    </div>

                    <div>
                        <label class="mb-2">Stok</label>
                        <input type="number" id="stok" class="w-full px-3 py-1.5 border rounded" readonly>
                    </div>

                    {{-- <div>
                        <label class="mb-2">Harga</label>
                        <input type="text" id="harga" class="w-full px-3 py-1.5 border rounded">
                    </div> --}}
                    <input type="hidden" id="harga">

                    <div>
                        <label class="mb-2">Jumlah</label>
                        <input type="number" id="jumlah" class="w-full px-3 py-1.5 border rounded">
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
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Subtotal</th>
                            <th colspan="2" id="subtotal">Rp0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Ajukan</button>
            </div>
        </form>
    </div>

    <script>
        let daftarBarang = [];
        let allBarang = [];
        const stationeriesUrl = "{{ route('req.stationeries') }}";

        $(document).ready(function () {
            $.ajax({
                url: stationeriesUrl,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    allBarang = response;
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data barang'
                    });
                }
            });

            $('#searchBarang').on('input', function () {
                const keyword = $(this).val().toLowerCase();
                const filtered = !keyword ? allBarang : allBarang.filter(item => item.nama_barang.toLowerCase().includes(keyword));

                if (filtered.length === 0) {
                    $('#dropdownBarang').hide();
                    return;
                }

                let html = '';
                filtered.forEach(item => {
                    html += `<div class="px-3 py-2 hover:bg-gray-200 cursor-pointer" 
                    data-id="${item.id}" 
                    data-nama="${item.nama_barang.toUpperCase()}" 
                    data-harga="${item.harga_barang}" 
                    data-stok="${item.stok}">
                    ${item.nama_barang.toUpperCase()} (Rp${formatRupiah(item.harga_barang)})
                </div>`;
                });
                $('#dropdownBarang').html(html).show();
            });

            $('#dropdownBarang').on('click', 'div', function () {
                $('#searchBarang').val($(this).data('nama'));
                $('#stationery_id').val($(this).data('id'));
                $('#harga').val($(this).data('harga'));
                $('#stok').val($(this).data('stok'));
                $('#dropdownBarang').hide();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#searchBarang, #dropdownBarang').length) {
                    $('#dropdownBarang').hide();
                }
            });
        });

        $('#tambahBarang').click(function () {
            const id = $('#stationery_id').val();
            const nama = $('#searchBarang').val();
            const jumlah = parseInt($('#jumlah').val());
            const stok = parseInt($('#stok').val());
            const harga = parseFloat($('#harga').val());

            if (!id || !jumlah) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Barang dan jumlah harus diisi' });
                return;
            }

            if (jumlah > stok) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Jumlah melebihi stok!' });
                return;
            }

            daftarBarang.push({ id, nama, jumlah, harga });
            renderTabel();

            $('#searchBarang').val('');
            $('#stationery_id').val('');
            $('#stok').val('');
            $('#harga').val('');
            $('#jumlah').val('');
        });

        function renderTabel() {
            let html = '';
            let subtotal = 0;
            $('#tabelBarang tbody').empty();

            daftarBarang.forEach((item, index) => {
                const totalHarga = item.harga * item.jumlah;
                subtotal += totalHarga;
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
                                <td>
                                    Rp${item.harga.toLocaleString('id-ID')}
                                    <input type="hidden" name="items[${index}][harga]" value="${item.harga}">
                                </td>
                                <td>
                                    Rp${totalHarga.toLocaleString('id-ID')}
                                </td>
                                <td>
                                    <button type="button" onclick="hapusItem(${index})" class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>`;
            });

            $('#tabelBarang tbody').html(html);
            $('#subtotal').text(`Rp${subtotal.toLocaleString('id-ID')}`);
        }

        function hapusItem(index) {
            daftarBarang.splice(index, 1);
            renderTabel();
        }

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
@endsection