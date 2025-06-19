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
                    <div class="relative">
                        <label for="searchBarang" class="block mb-2 font-medium text-sm text-gray-700">Nama Barang</label>
                        <input type="text" id="searchBarang" class="form-control mb-2" placeholder="Cari nama barang..."
                            autocomplete="off">
                        <div id="dropdownBarang" class="bg-white border rounded shadow absolute z-10 w-full"
                            style="display:none; max-height:200px; overflow-y:auto;"></div>
                        <input type="hidden" id="stationery_id" name="stationery_id">
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
        let allBarang = [];

        $(document).ready(function() {
            // Ambil semua barang saat halaman dimuat
            $.ajax({
                url: "{{ url('user/get-stationery') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    allBarang = response;
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data barang'
                    });
                }
            });

            // Pencarian manual dengan dropdown custom
            $('#searchBarang').on('input', function() {
                const keyword = $(this).val().toLowerCase();
                let filtered = [];

                if (!keyword) {
                    // Jika input kosong, tampilkan semua barang
                    filtered = allBarang;
                } else {
                    // Jika ada keyword, filter barang
                    filtered = allBarang.filter(item => item.nama_barang.toLowerCase().includes(keyword));
                }

                if (filtered.length === 0) {
                    $('#dropdownBarang').hide();
                    return;
                }

                let html = '';
                filtered.forEach(item => {
                    html += `<div class="px-3 py-2 hover:bg-gray-200 cursor-pointer" 
                        data-id="${item.id}" 
                        data-nama="${item.nama_barang.toUpperCase()}" 
                        data-stok="${item.stok}">
                        ${item.nama_barang.toUpperCase()}
                    </div>`;
                });
                $('#dropdownBarang').html(html).show();
            });

            // Pilih barang dari dropdown
            $('#dropdownBarang').on('click', 'div', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const stok = $(this).data('stok');
                $('#searchBarang').val(nama);
                $('#stationery_id').val(id);
                $('#stok').val(stok);
                $('#dropdownBarang').hide();
            });

            // Sembunyikan dropdown jika klik di luar
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#searchBarang, #dropdownBarang').length) {
                    $('#dropdownBarang').hide();
                }
            });
        });

        $('#tambahBarang').click(function() {
            const id = $('#stationery_id').val();
            const nama = $('#searchBarang').val();
            const jumlah = parseInt($('#jumlah').val());
            const stok = parseInt($('#stok').val());

            if (!id || !jumlah) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Barang dan jumlah harus diisi'
                });
                return;
            }

            if (jumlah > stok) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah yang diminta melebihi stok tersedia!'
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
            $('#searchBarang').val('');
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
