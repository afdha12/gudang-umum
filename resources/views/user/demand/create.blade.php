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
                        <label class="mb-2">Stok Tersedia</label>
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

                    <x-success-button type="button" id="tambahBarang">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Tambah Barang
                    </x-success-button>
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
                <x-primary-button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                    </svg>
                    Ajukan
                </x-primary-button>
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
                    // Hitung stok virtual: available_stock (sudah dikurangi pending user lain) - yang ada di keranjang saat ini
                    const inCart = daftarBarang.filter(b => b.id == item.id).reduce((sum, b) => sum + b.jumlah, 0);
                    const virtualStok = item.available_stock - inCart;
                    const isHabis = virtualStok <= 0;

                    html += `<div class="px-3 py-2 ${isHabis ? 'bg-red-100 text-red-400 cursor-not-allowed' : 'hover:bg-gray-200 cursor-pointer'}"
                    data-id="${item.id}"
                    data-nama="${item.nama_barang.toUpperCase()}"
                    data-harga="${item.harga_barang}"
                    data-stok="${virtualStok}"
                    data-habis="${isHabis}">
                    ${item.nama_barang.toUpperCase()} (Stok: ${isHabis ? '<span class="text-red-500 font-bold">Habis</span>' : virtualStok}) (Rp${formatRupiah(item.harga_barang)})
                </div>`;
                });
                $('#dropdownBarang').html(html).show();
            });

            $('#dropdownBarang').on('click', 'div', function () {
                // Cegah memilih barang yang stoknya habis
                if ($(this).data('habis') === true) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Habis',
                        text: 'Barang ini sedang tidak tersedia karena stok sudah habis atau sedang diajukan oleh user lain.'
                    });
                    return;
                }
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

            if (jumlah <= 0) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Jumlah harus lebih dari 0' });
                return;
            }

            if (stok <= 0) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Barang ini sudah habis!' });
                return;
            }

            if (jumlah > stok) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Jumlah melebihi stok yang tersedia!' });
                return;
            }

            const existingIndex = daftarBarang.findIndex(item => item.id == id);
            if (existingIndex !== -1) {
                if ((daftarBarang[existingIndex].jumlah + jumlah) > stok) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Total jumlah melebihi stok yang tersedia!' });
                    return;
                }
                daftarBarang[existingIndex].jumlah += jumlah;
            } else {
                daftarBarang.push({ id, nama, jumlah, harga });
            }

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
                                    <button type="button" onclick="hapusItem(${index})" class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-red-600 to-rose-600 border border-transparent rounded-lg font-semibold text-xs text-white tracking-wide shadow-md hover:from-red-500 hover:to-rose-500 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:scale-95 transition-all ease-in-out duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg>
                                        Hapus
                                    </button>
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
