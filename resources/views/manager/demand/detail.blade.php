@extends('layouts.main')

@section('title', 'Detail Permintaan Barang')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Tanggal Permintaan</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Persetujuan Manager</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($userDemands as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ date('d M Y', strtotime($item->dos)) }}</td>
                            <td class="py-3 px-4">{{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                            <td class="py-3 px-4">{{ $item->amount }}</td>
                            <td class="py-3 px-4">
                                @if ($item->manager_approval)
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-warning">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if ($item->status == 0)
                                    <span class="badge bg-warning">Belum Disetujui</span>
                                @else
                                    <span class="badge bg-success">Disetujui</span>
                                @endif
                            <td class="py-3 px-4">
                                @if (!$item->manager_approval)
                                    <form action="{{ route('item_demands.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Setujui</button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Sudah Disetujui</button>
                                @endif
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
            document.querySelectorAll(".approve-form").forEach(form => {
                form.addEventListener("submit", function(event) {
                    event.preventDefault(); // Hentikan submit default

                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Setelah disetujui, stok akan berkurang!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, setujui!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Lanjutkan submit jika dikonfirmasi
                        }
                    });
                });
            });
        });
    </script>

@endsection
