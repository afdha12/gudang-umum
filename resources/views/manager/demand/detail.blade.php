@extends('layouts.main')

@section('title', 'Detail Permintaan Barang')

@section('content')

    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal Permintaan</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Subtotal</th>
                        <th class="py-3 px-4 text-left">Catatan</th>
                        {{-- <th class="py-3 px-4 text-left">Persetujuan Manager</th> --}}
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($userDemands as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ date('d M Y', strtotime($item->dos)) }}</td>
                            <td class="text-capitalize py-3 px-4">{{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td class="py-3 px-4">{{ $item->amount }}</td>
                            <td class="py-3 px-4">{{ $item->total_harga_formatted }}</td>
                            <td class="py-3 px-4" style="white-space: pre-line;">{{ $item->notes }}</td>
                            <td class="py-3 px-4">
                                @if ($item->manager_approval)
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-warning">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('item_demands.edit', ['item_demand' => $item->id, 'user_id' => $item->user_id]) }}"
                                    class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-pencil"></i></a>
                                {{-- @if (!$item->manager_approval)
                                    <form action="{{ route('item_demands.update', $item->id) }}" method="POST"
                                        class="approve-form d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" class="btn btn-outline-primary btn-sm approve-btn"><i
                                                class="bi bi-check-lg"></i>Setujui</button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Sudah Disetujui</button>
                                @endif --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('partials.pagination', ['data' => $userDemands])

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

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}'
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session('warning') }}'
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.approve-btn').forEach(button => {
                button.addEventListener('click', function() {
                    let form = this.closest('form'); // Ambil form terdekat

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Setujui Permintaan Barang ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit form jika dikonfirmasi
                        }
                    });
                });
            });
        });
    </script>

@endsection
