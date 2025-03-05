@extends('layouts.main')

@section('title', 'Data Stok Barang')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="m-3">
            <a class="btn btn-primary" href="{{ route('stationeries.create', ['type' => '1']) }}">Tambah Data Barang</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Kode Barang</th>
                        <th class="py-3 px-4 text-left">Nama Barang</th>
                        <th class="py-3 px-4 text-left">Harga Barang</th>
                        <th class="py-3 px-4 text-left">Satuan</th>
                        <th class="py-3 px-4 text-left">Masuk</th>
                        <th class="py-3 px-4">Keluar</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($data as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $item->kode_barang }}</td>
                            <td class="py-3 px-4">{{ $item->nama_barang }}</td>
                            <td class="py-3 px-4">{{ $item->formatted_harga }}</td>
                            <td class="py-3 px-4">{{ $item->satuan }}</td>
                            <td class="py-3 px-4">{{ $item->masuk }}</td>
                            <td class="py-3 px-4">{{ $item->keluar }}</td>
                            <td class="py-3 px-4">{{ $item->stok }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('stationeries.edit', ['stationery' => $item->id, 'type' => $type]) }}"
                                    class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-pencil"></i></i></a>
                                <a href="{{ route('stationeries.destroy', $item->id) }}" class="btn btn-outline-danger btn-sm"
                                    data-confirm-delete="true"><i class="bi bi-trash"></i></a>
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
                        <span class="font-medium">{{ $data->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $data->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $data->total() }}</span>
                        results
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                            {{-- Tombol Previous --}}
                            @if ($data->onFirstPage())
                                <span
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset cursor-not-allowed">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $data->previousPageUrl() }}"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            @endif

                            {{-- Nomor Halaman --}}
                            @foreach ($data->links()->elements[0] as $page => $url)
                                @if ($page == $data->currentPage())
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
                            @if ($data->hasMorePages())
                                <a href="{{ $data->nextPageUrl() }}"
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
        <div class="mx-auto max-w-none">
        </div>
    </div>

@endsection
