@extends('layouts.main')

@section('title', 'Edit Permintaan Barang')

@php
    $rolePrefix = match (auth()->user()->role) {
        'manager' => 'item_demands',
        'coo' => 'user_demands',
        'admin' => 'demand',
        default => 'item-demand',
    };

    $adaBelumDisetujui = $items->contains(fn($item) => $item->status === null);
@endphp

@section('content')
    <div class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 text-white px-4 py-3">
                <h5 class="text-lg font-semibold mb-0">Detail & Persetujuan Permintaan</h5>
                <small class="block">{{ $user->name }} - {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</small>
            </div>
            <div class="p-4">
                <form action="{{ route($rolePrefix . '.update_by_date', ['user' => $user->id, 'date' => $date]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    @foreach ($items as $item)
                        @php
                            $readOnly = false;
                            if ($item->status === 1) {
                                $readOnly = true;
                            } elseif (
                                $item->status === 0 ||
                                $item->manager_approval === 0 ||
                                $item->coo_approval === 0
                            ) {
                                $readOnly = true;
                            } elseif ($role === 'coo' && ($item->status === 1 || $item->coo_approval === 1)) {
                                $readOnly = true;
                            } elseif (
                                $role === 'manager' &&
                                ($item->status === 1 || $item->coo_approval === 1 || $item->manager_approval === 1)
                            ) {
                                $readOnly = true;
                            }
                        @endphp

                        <div class="mb-4 p-4 border rounded">
                            <div class="md:flex md:gap-4 mb-2">
                                <div class="md:w-2/6">
                                    <div>
                                        <h6 class="uppercase font-semibold">
                                            {{ $item->stationery->nama_barang }}
                                        </h6>
                                        <p class="my-2">Stok: <strong>{{ $item->stationery->stok }}</strong>
                                            {{ $item->stationery->satuan }}</p>
                                        <p class="mb-2">Harga/item:
                                            <strong>Rp{{ number_format($item->stationery->harga_barang, 0, ',', '.') }}</strong>
                                        </p>
                                        <p class="mb-2">
                                            Total Harga:
                                            <strong class="total-harga" data-id="{{ $item->id }}">
                                                Rp{{ number_format($item->amount * $item->stationery->harga_barang, 0, ',', '.') }}
                                            </strong>
                                        </p>

                                        {{-- @if ($item->manager_approval === 1 && $item->coo_approval === 1 && $item->status === 1)
                                            <span
                                                class="inline-block bg-green-600 text-white text-xs px-2 py-1 rounded">Disetujui
                                                Gudang</span>
                                        @endif --}}

                                        @if ($role === 'user')
                                            @if ($item->status === 0 || $item->manager_approval === 0 || $item->coo_approval === 0)
                                                <span class="inline-block bg-red-600 text-white text-xs px-2 py-1 rounded">
                                                    Ditolak oleh {{ $item->rejected_by ?? '-' }}
                                                </span>
                                            @elseif ($item->status === 1)
                                                <span
                                                    class="inline-block bg-green-600 text-white text-xs px-2 py-1 rounded">
                                                    Disetujui Semua Pihak
                                                </span>
                                            @elseif ($item->coo_approval === 1)
                                                <span class="inline-block bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                                    Disetujui Wadirum
                                                </span>
                                            @elseif ($item->manager_approval === 1)
                                                <span
                                                    class="inline-block bg-yellow-600 text-white text-xs px-2 py-1 rounded">
                                                    Disetujui Manager
                                                </span>
                                            @else
                                                <span class="inline-block bg-gray-600 text-white text-xs px-2 py-1 rounded">
                                                    Menunggu Persetujuan
                                                </span>
                                            @endif
                                        @endif

                                        {{-- Progress bar untuk visualisasi --}}
                                        @if ($role === 'user' && !$item->isRejected())
                                            <div class="mt-2">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    @php
                                                        $progress = 0;
                                                        if ($item->manager_approval === 1) {
                                                            $progress += 33.33;
                                                        }
                                                        if ($item->coo_approval === 1) {
                                                            $progress += 33.33;
                                                        }
                                                        if ($item->status === 1) {
                                                            $progress += 33.34;
                                                        }
                                                    @endphp
                                                    <div class="bg-blue-600 h-2.5 rounded-full"
                                                        style="width: {{ $progress }}%"></div>
                                                </div>
                                                <div class="flex justify-between text-xs mt-1">
                                                    <span>Manager</span>
                                                    <span>Wadirum</span>
                                                    <span>Gudang</span>
                                                </div>
                                            </div>
                                        @endif

                                        @php
                                            $isRejected = false;
                                            $approved = false;

                                            if ($role === 'manager') {
                                                $isRejected = $item->manager_approval === 0;
                                                $approved = $item->manager_approval === 1;
                                            } elseif ($role === 'coo') {
                                                $isRejected = $item->coo_approval === 0;
                                                $approved = $item->coo_approval === 1;
                                            } elseif ($role === 'admin') {
                                                $isRejected =
                                                    $item->status === 0 ||
                                                    $item->manager_approval === 0 ||
                                                    $item->coo_approval === 0;
                                                $approved = $item->status === 1;
                                            }
                                        @endphp

                                        @if ($isRejected)
                                            <span class="inline-block bg-red-600 text-white text-xs px-2 py-1 rounded">
                                                Ditolak oleh {{ $item->rejected_by ?? '-' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="md:w-4/6 mt-4 md:mt-0">
                                    <label class="block text-sm font-medium mb-1">Jumlah Permintaan</label>
                                    {{-- Fixed: Remove typo, add data attributes --}}
                                    <input type="number" name="amount[{{ $item->id }}]" value="{{ $item->amount }}"
                                        class="w-full px-3 py-1.5 border rounded text-sm jumlah-permintaan" min="1"
                                        data-id="{{ $item->id }}" data-harga="{{ $item->stationery->harga_barang }}"
                                        @if ($readOnly || $item->status == 1) readonly @endif>

                                    <input type="hidden" name="status[{{ $item->id }}]"
                                        value="{{ $item->status ?? '' }}" class="status-input"
                                        data-id="{{ $item->id }}">

                                    @if ($role != 'user')
                                        <label class="block text-sm font-medium mt-3">Catatan Tambahan</label>
                                        <textarea name="notes[{{ $item->id }}]" class="w-full px-3 py-2 border rounded text-sm" rows="1"
                                            @if ($readOnly || $item->status == 1) readonly @endif>{{ old("notes.$item->id") }}</textarea>
                                    @endif

                                    @if ($role === 'user')
                                        {{-- Tombol hapus untuk user --}}
                                        @if (is_null($item->status) && !$readOnly)
                                            <button type="button"
                                                class="mt-2 px-3 py-1.5 bg-red-600 text-white text-sm rounded delete-btn"
                                                data-id="{{ $item->id }}">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        @endif
                                    @else
                                        {{-- Tombol reject untuk manager/coo/admin --}}
                                        @if (is_null($item->status) && !$readOnly)
                                            <button type="button"
                                                class="mt-2 px-3 py-1.5 bg-red-600 text-white text-sm rounded reject-btn"
                                                data-id="{{ $item->id }}">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                            <span
                                                class="ml-2 bg-red-600 text-white text-xs px-2 py-1 rounded d-none rejected-badge"
                                                data-id="{{ $item->id }}">Rejected</span>
                                        @endif
                                    @endif

                                    @if ($isRejected)
                                        <span
                                            class="inline-block mt-2 bg-red-600 text-white text-xs px-2 py-1 rounded">Rejected</span>
                                    @elseif ($approved)
                                        <span
                                            class="inline-block mt-2 bg-green-600 text-white text-xs px-2 py-1 rounded">Approved</span>
                                    @endif
                                </div>
                            </div>

                            @if ($item->notes)
                                <div class="mt-2">
                                    <label class="block text-sm font-medium mb-1">Riwayat Catatan</label>
                                    <div class="bg-gray-100 p-2 border rounded whitespace-pre-line text-sm">
                                        {{ $item->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="mb-4">
                        <h5 class="text-lg font-semibold">Total Semua:
                            <span id="grand-total">
                                Rp{{ number_format($items->filter(fn($item) => $item->status !== 0)->sum(fn($item) => $item->amount * $item->stationery->harga_barang), 0, ',', '.') }}
                            </span>
                        </h5>
                    </div>

                    <div class="flex justify-between items-center">
                        @if ($adaBelumDisetujui)
                            <button type="submit" name="action" value="{{ $role === 'user' ? 'update' : 'approve' }}"
                                class="px-4 py-2 {{ $role === 'user' ? 'bg-blue-600' : 'bg-green-600' }} text-white rounded hover:{{ $role === 'user' ? 'bg-blue-700' : 'bg-green-700' }} text-sm">
                                <i class="bi {{ $role === 'user' ? 'bi-save' : 'bi-check-circle' }}"></i>
                                {{ $role === 'user' ? 'Simpan Perubahan' : 'Setujui Semua' }}
                            </button>
                        @endif
                        <a href="{{ route($rolePrefix . '.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.jumlah-permintaan');

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            function updateGrandTotal() {
                let grandTotal = 0;
                inputs.forEach(input => {
                    const id = input.dataset.id;
                    const itemContainer = input.closest('.mb-4.p-4.border.rounded');

                    // Skip jika container tersembunyi (item dihapus)
                    if (itemContainer && itemContainer.style.display === 'none') {
                        return;
                    }

                    // Perbaikan pengecekan status reject - lebih akurat
                    const statusInput = document.querySelector(`input.status-input[data-id="${id}"]`);
                    const isRejected =
                        // Cek dari status input hidden
                        (statusInput && statusInput.value === "0") ||
                        // Cek dari badge reject yang visible
                        itemContainer.querySelector('.bg-red-600.text-white:not(.d-none)') !== null ||
                        itemContainer.querySelector('.rejected-badge:not(.d-none)') !== null ||
                        // Cek jika input readonly karena direject
                        (input.readOnly && input.value === "0");

                    // Skip jika item direject
                    if (isRejected) {
                        console.log('Skipping rejected item:', id, 'Reason: rejected');
                        return;
                    }

                    const jumlah = parseInt(input.value) || 0;
                    const harga = parseInt(input.dataset.harga) || 0;
                    const itemTotal = jumlah * harga;

                    console.log('Adding to total:', {
                        id,
                        jumlah,
                        harga,
                        itemTotal
                    });

                    grandTotal += itemTotal;
                });

                // Update tampilan grand total
                const grandTotalElement = document.getElementById('grand-total');
                grandTotalElement.textContent = formatRupiah(grandTotal);

                console.log('Final grand total:', {
                    total: grandTotal,
                    formatted: formatRupiah(grandTotal)
                });
            }

            inputs.forEach(input => {
                // Add debug logging
                console.log('Input data:', {
                    id: input.dataset.id,
                    harga: input.dataset.harga,
                    value: input.value
                });

                input.addEventListener('input', function() {
                    const jumlah = parseInt(this.value) || 0;
                    const harga = parseInt(this.dataset.harga) || 0;
                    const id = this.dataset.id;
                    const totalElement = document.querySelector(`.total-harga[data-id="${id}"]`);

                    console.log('Updating item:', {
                        id,
                        jumlah,
                        harga,
                        total: jumlah * harga
                    });

                    if (totalElement) {
                        totalElement.textContent = formatRupiah(jumlah * harga);
                    }
                    updateGrandTotal();
                });
            });

            updateGrandTotal(); // initial load

            // Handle reject button untuk manager/coo/admin
            document.querySelectorAll('.reject-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.dataset.id;
                    Swal.fire({
                        title: 'Tolak permintaan ini?',
                        text: "Item yang ditolak tidak dapat diubah atau disetujui.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Tolak!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const statusInput = document.querySelector(
                                `input.status-input[data-id="${id}"]`);
                            const amountInput = document.querySelector(
                                `input[name="amount[${id}]"]`);
                            const noteInput = document.querySelector(
                                `textarea[name="notes[${id}]"]`);

                            if (statusInput) {
                                statusInput.value = "0"; // Set status ke rejected
                            }

                            if (amountInput) {
                                amountInput.readOnly = true;
                                amountInput.value = "0"; // Set jumlah ke 0
                            }

                            if (noteInput) noteInput.readOnly = true;

                            btn.classList.add('d-none');
                            document.querySelector(`.rejected-badge[data-id="${id}"]`)
                                .classList.remove('d-none');

                            updateGrandTotal(); // Panggil update setelah reject

                            Swal.fire(
                                'Ditolak!',
                                'Item berhasil ditolak.',
                                'success'
                            );
                        }
                    });
                });
            });

            // Handle delete button untuk user
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.dataset.id;
                    const itemContainer = btn.closest('.mb-4.p-4.border.rounded');

                    Swal.fire({
                        title: 'Hapus item ini?',
                        text: "Item yang dihapus tidak dapat dikembalikan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menghapus...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Send AJAX request to delete
                            fetch(`/user/item-demand/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Hide the entire item container
                                        if (itemContainer) {
                                            itemContainer.style.display = 'none';
                                            updateGrandTotal();

                                            Swal.fire(
                                                'Dihapus!',
                                                'Item berhasil dihapus.',
                                                'success'
                                            );
                                        }
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            data.message ||
                                            'Terjadi kesalahan saat menghapus item.',
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire(
                                        'Error!',
                                        'Terjadi kesalahan saat menghapus item.',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });

            // Debug form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form being submitted...');
                const formData = new FormData(this);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                // Remove the prevention after debugging
                // e.preventDefault();
            });
        });
    </script>
@endsection
