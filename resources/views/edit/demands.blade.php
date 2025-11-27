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
                            $statusInfo = $item->getStatusDisplay();
                            $approvedItem = $item->isFullyApproved();
                            $approvalStatus = $item->getApprovalStatus();
                            $canProcess = $item->canBeProcessedBy($role);
                            $isRejected = $item->isRejected(); // Simpan status rejection

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

                        {{-- PENTING: Tambahkan data attributes untuk JavaScript --}}
                        <div class="mb-4 p-4 border rounded" data-item-id="{{ $item->id }}"
                            data-is-rejected="{{ $isRejected ? '1' : '0' }}"
                            data-manager-approval="{{ $item->manager_approval ?? 'null' }}"
                            data-coo-approval="{{ $item->coo_approval ?? 'null' }}"
                            data-admin-status="{{ $item->status ?? 'null' }}">
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

                                        {{-- Status display untuk user --}}
                                        @if ($role === 'user')
                                            <span
                                                class="inline-block {{ $statusInfo['class'] }} text-white text-xs px-2 py-1 rounded">
                                                {{ $statusInfo['text'] }}
                                            </span>
                                        @endif

                                        {{-- Progress bar untuk user (hanya jika tidak direject) --}}
                                        @if ($role === 'user' && !$statusInfo['rejected'])
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

                                        {{-- Status display untuk role lain --}}
                                        @if ($role !== 'user')
                                            <span
                                                class="inline-block {{ $statusInfo['class'] }} text-white text-xs px-2 py-1.5 rounded">
                                                {{ $statusInfo['text'] }}
                                            </span>
                                        @endif

                                        @if ($approvalStatus['status'] == 1 && !$item->isCancelled())
                                            <button type="button"
                                                class="mt-2 px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded cancel-btn"
                                                data-id="{{ $item->id }}">
                                                <i class="bi bi-x-circle"></i> Cancel
                                            </button>
                                        @endif

                                    </div>
                                </div>

                                <input type="hidden" name="is_rejected[{{ $item->id }}]" value="0"
                                    id="rejected-{{ $item->id }}">

                                <div class="md:w-4/6 mt-4 md:mt-0">
                                    <label class="block text-sm font-medium mb-1">Jumlah Permintaan</label>
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
                                        {{-- Tombol reject hanya jika bisa diproses --}}
                                        @if ($canProcess)
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
                                @php
                                    $grandTotal = 0;

                                    foreach ($items as $item) {
                                        // Cek status reject dari database
                                        $isRejected = $item->isRejected();

                                        // Cek status reject dari form submission
                                        $isRejectedFromForm = old("is_rejected.{$item->id}") === '1';
                                        $statusFromForm = old("status.{$item->id}");
                                        $isRejectedStatus = $statusFromForm === '0';

                                        // Skip perhitungan jika item direject
                                        if ($isRejected || $isRejectedFromForm || $isRejectedStatus) {
                                            continue;
                                        }

                                        // Tambahkan ke grand total jika tidak direject
                                        $itemTotal = $item->amount * $item->stationery->harga_barang;
                                        $grandTotal += $itemTotal;

                                        // Debug info
                                        if (config('app.debug')) {
                                            \Log::info("Item {$item->id} calculation:", [
                                                'amount' => $item->amount,
                                                'price' => $item->stationery->harga_barang,
                                                'total' => $itemTotal,
                                                'isRejected' => $isRejected,
                                                'isRejectedFromForm' => $isRejectedFromForm,
                                                'isRejectedStatus' => $isRejectedStatus,
                                            ]);
                                        }
                                    }
                                @endphp
                                Rp{{ number_format($grandTotal, 0, ',', '.') }}
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

                    // Skip jika container tersembunyi
                    if (itemContainer && itemContainer.style.display === 'none') {
                        console.log('Skipping hidden item:', id);
                        return;
                    }

                    // Check semua kemungkinan status reject
                    let isRejected = false;

                    // 1. Cek dari data attribute server-side
                    if (itemContainer.getAttribute('data-is-rejected') === '1') {
                        isRejected = true;
                        console.log('Item rejected from server data:', id);
                    }

                    // 2. Cek dari form inputs
                    const statusInput = document.querySelector(`input.status-input[data-id="${id}"]`);
                    const isRejectedInput = document.querySelector(`input[name="is_rejected[${id}]"]`);

                    if (statusInput && statusInput.value === "0") {
                        isRejected = true;
                        console.log('Item rejected from status input:', id);
                    }

                    if (isRejectedInput && isRejectedInput.value === "1") {
                        isRejected = true;
                        console.log('Item rejected from rejected flag:', id);
                    }

                    // 3. Cek dari UI elements
                    if (itemContainer.querySelector('.rejected-badge:not(.d-none)')) {
                        isRejected = true;
                        console.log('Item rejected from UI badge:', id);
                    }

                    if (itemContainer.classList.contains('rejected-item')) {
                        isRejected = true;
                        console.log('Item rejected from CSS class:', id);
                    }

                    // Skip jika item direject
                    if (isRejected) {
                        console.log('Skipping rejected item:', id);
                        return;
                    }

                    // Hitung total jika tidak direject
                    const jumlah = parseInt(input.value) || 0;
                    const harga = parseInt(input.dataset.harga) || 0;
                    const itemTotal = jumlah * harga;

                    console.log('Adding to total:', {
                        id,
                        jumlah,
                        harga,
                        total: itemTotal
                    });

                    grandTotal += itemTotal;
                });

                // Update tampilan grand total
                const grandTotalElement = document.getElementById('grand-total');
                if (grandTotalElement) {
                    grandTotalElement.textContent = formatRupiah(grandTotal);
                }

                console.log('Final grand total:', grandTotal);
            }

            inputs.forEach(input => {
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

            // PENTING: Jangan panggil updateGrandTotal() pada initial load
            // Biarkan nilai dari server-side yang ditampilkan
            // updateGrandTotal() hanya dipanggil saat ada perubahan input atau aksi user

            // Uncomment baris di bawah jika ingin debug JavaScript calculation vs Server calculation
            // setTimeout(() => {
            //     console.log('=== DEBUGGING GRAND TOTAL ===');
            //     console.log('Server-side grand total element:', document.getElementById('grand-total').textContent);
            //     updateGrandTotal();
            //     console.log('JavaScript calculated grand total:', document.getElementById('grand-total').textContent);
            // }, 100);

            // Handle reject button untuk manager/coo/admin
            document.querySelectorAll('.reject-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.dataset.id;
                    Swal.fire({
                        title: 'Tolak permintaan ini?',
                        text: "Item yang ditolak tidak akan masuk dalam perhitungan total.",
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
                            const isRejectedInput = document.querySelector(
                                `input[name="is_rejected[${id}]"]`);
                            const amountInput = document.querySelector(
                                `input[name="amount[${id}]"]`);
                            const noteInput = document.querySelector(
                                `textarea[name="notes[${id}]"]`);

                            // Set status ke rejected DI FORM INPUT
                            if (statusInput) {
                                statusInput.value = "0";
                            }

                            // Set is_rejected flag ke 1
                            if (isRejectedInput) {
                                isRejectedInput.value = "1";
                            }

                            if (amountInput) {
                                amountInput.readOnly = true;
                            }

                            if (noteInput) {
                                noteInput.readOnly = true;
                            }

                            btn.classList.add('d-none');
                            const rejectedBadge = document.querySelector(
                                `.rejected-badge[data-id="${id}"]`);
                            if (rejectedBadge) {
                                rejectedBadge.classList.remove('d-none');
                            }

                            // Tambahkan class rejected untuk penanda
                            const itemContainer = amountInput.closest(
                                '.mb-4.p-4.border.rounded');
                            if (itemContainer) {
                                itemContainer.classList.add('rejected-item');
                                // Update data attribute juga
                                itemContainer.setAttribute('data-is-rejected', '1');
                            }

                            updateGrandTotal(); // Update grand total

                            Swal.fire(
                                'Ditolak!',
                                'Item berhasil ditolak dan tidak akan masuk perhitungan.',
                                'success'
                            );
                        }
                    });
                });
            });

            // Handle cancel button (set item kembali ke pending)
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const url = cancelRouteTemplate.replace('__ID__', id);

                    Swal.fire({
                        title: 'Batalkan permintaan?',
                        text: 'Barang akan dikembalikan ke stok.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, batalkan',
                        cancelButtonText: 'Tidak'
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({})
                            })
                            .then(res => res.json())
                            .then(res => {
                                if (res.success) {
                                    Swal.fire('Dibatalkan!', res.message, 'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('Error', res.message, 'error');
                                }
                            });
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
            });
        });
    </script>

    <script>
        const cancelRouteTemplate = "{{ route('demand.cancel', ['id' => '__ID__']) }}";
    </script>

@endsection
