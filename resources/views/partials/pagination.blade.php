<div class="sticky-bottom flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 mt-4 rounded-b-lg">
    <div class="flex flex-1 justify-between sm:hidden">
        @if ($data->onFirstPage())
            <span class="relative inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Previous</span>
        @else
            <a href="{{ $data->previousPageUrl() }}" class="relative inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-all">Previous</a>
        @endif

        @if ($data->hasMorePages())
            <a href="{{ $data->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-all">Next</a>
        @else
            <span class="relative ml-3 inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Next</span>
        @endif
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ $data->firstItem() ?? 0 }}</span>
                to
                <span class="font-medium">{{ $data->lastItem() ?? 0 }}</span>
                of
                <span class="font-medium">{{ $data->total() }}</span>
                results
            </p>
        </div>
        <div>
            <nav class="isolate inline-flex gap-1.5" aria-label="Pagination">
                {{-- Tombol Previous --}}
                @if ($data->onFirstPage())
                    <span class="relative inline-flex items-center rounded-lg px-2.5 py-2 text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed">
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="relative inline-flex items-center rounded-lg px-2.5 py-2 text-gray-600 bg-white border border-gray-300 shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                @endif

                @php
                    $currentPage = $data->currentPage();
                    $lastPage = $data->lastPage();
                    $start = max($currentPage - 2, 1);
                    $end = min($currentPage + 2, $lastPage);
                @endphp

                {{-- Halaman 1 jika di luar jangkauan --}}
                @if ($start > 1)
                    <a href="{{ $data->url(1) }}" class="relative inline-flex items-center bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                        1
                    </a>
                    @if ($start > 2)
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-semibold text-gray-500 bg-transparent">
                            ...
                        </span>
                    @endif
                @endif

                {{-- Halaman yang sedang ditampilkan --}}
                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <span class="relative z-10 inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-md border border-transparent">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ $data->url($i) }}" class="relative inline-flex items-center bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                            {{ $i }}
                        </a>
                    @endif
                @endfor

                {{-- Halaman terakhir jika di luar jangkauan --}}
                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-semibold text-gray-500 bg-transparent">
                            ...
                        </span>
                    @endif
                    <a href="{{ $data->url($lastPage) }}" class="relative inline-flex items-center bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                        {{ $lastPage }}
                    </a>
                @endif

                {{-- Tombol Next --}}
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="relative inline-flex items-center rounded-lg px-2.5 py-2 text-gray-600 bg-white border border-gray-300 shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                @else
                    <span class="relative inline-flex items-center rounded-lg px-2.5 py-2 text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed">
                        <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                @endif
            </nav>
        </div>
    </div>
</div>
