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
                            <td class="py-3 px-4">{{ $item->stationery->nama_barang }}</td>
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
                                @if (!$item->status)
                                    @if ($item->manager_approval == 1)
                                        <form action="{{ route('demand.update', $item->id) }}" method="POST"
                                            class="approve-form">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Setujui</button>
                                        </form>
                                    @else
                                        <button class="btn btn-danger btn-sm" disabled>Menunggu Approval</button>
                                    @endif
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Sudah Disetujui</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="sticky-bottom flex border-t border-gray-200 px-4 py-3 sm:px-6">
                <div class="flex flex-1 justify-between sm:hidden">
                    <a href="#"
                        class="relative inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                    <a href="#"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                </div>
                <div class="sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div class="text-sm">
                        Showing
                        <span class="font-medium">{{ $userDemands->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $userDemands->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $userDemands->total() }}</span>
                        results
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                            {{-- Tombol Previous --}}
                            @if ($userDemands->onFirstPage())
                                <span
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset cursor-not-allowed">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $userDemands->previousPageUrl() }}"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            @endif

                            {{-- Nomor Halaman --}}
                            @foreach ($userDemands->links()->elements[0] as $page => $url)
                                @if ($page == $userDemands->currentPage())
                                    <span
                                        class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Tombol Next --}}
                            @if ($userDemands->hasMorePages())
                                <a href="{{ $userDemands->nextPageUrl() }}"
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset cursor-not-allowed">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
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
