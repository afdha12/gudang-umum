@extends('layouts.main')

@section('title', 'Cetak Data Permintaan Barang')

@section('content')
    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="d-flex m-3">
            <div class="me-auto">
                <form id="print-form" action="{{ route('list_demands.store') }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    <input type="hidden" name="from" value="{{ request('from') }}">
                    <input type="hidden" name="to" value="{{ request('to') }}">
                    <button type="submit" id="print-selected" class="btn btn-success">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak Data
                    </button>
                </form>
            </div>
            {{-- <form action="{{ route('list_demands.index') }}" method="GET" id="filter-form">
                    <div class="col">
                        <select name="division_id" class="form-select"
                            onchange="document.getElementById('filter-form').submit();">
                            <option value="">-- Semua Divisi --</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}"
                                    {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->division_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form> --}}
            <form action="{{ route('list_demands.index') }}" method="GET" id="filter-form" class="d-flex gap-2">
                <div>
                    <select name="user_id" class="form-select" onchange="this.form.submit();">
                        <option value="">-- Semua Pengaju --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                <div>
                    @if (request('user_id') || request('from') || request('to'))
                        <a href="{{ route('list_demands.index') }}" class="btn btn-secondary">Hapus Filter</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Pengaju</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Unit/Divisi</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approvedItems as $index => $item)
                        <tr>
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="text-capitalize p-3">{{ $item->user->name ?? 'User tidak ditemukan' }}</td>
                            <td class="text-capitalize p-3">
                                {{ $item->stationery->nama_barang ?? 'Barang tidak ditemukan' }}
                            </td>
                            <td class="text-uppercase p-3">
                                {{ $item->user->division->division_name ?? 'Divisi tidak ditemukan' }}</td>
                            <td class="p-3">{{ $item->amount }}</td>
                            <td class="p-3">{{ date('d M Y', strtotime($item->dos)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('partials.pagination', ['data' => $approvedItems])
        </div>
    </div>

    {{-- Script
    <script>
        let selectedIds = JSON.parse(localStorage.getItem('selectedIds')) || [];
        let selectAllActive = false;

        $(document).ready(function() {
            const checkboxes = $('.item-checkbox');

            // Restore checked state
            checkboxes.each(function() {
                if (selectedIds.includes(this.value)) {
                    this.checked = true;
                }
            });

            // Individual checkbox
            checkboxes.change(function() {
                const val = this.value;
                if (this.checked) {
                    if (!selectedIds.includes(val)) selectedIds.push(val);
                } else {
                    selectedIds = selectedIds.filter(id => id !== val);
                    selectAllActive = false;
                    $('#select-all-state').val("0");
                }
                localStorage.setItem('selectedIds', JSON.stringify(selectedIds));
            });

            // Select All checkbox
            $('#select-all').change(function() {
                const isChecked = this.checked;
                checkboxes.prop('checked', isChecked);

                if (isChecked) {
                    selectAllActive = true;
                    $('#select-all-state').val("1");
                    checkboxes.each(function() {
                        const val = this.value;
                        if (!selectedIds.includes(val)) selectedIds.push(val);
                    });
                } else {
                    selectAllActive = false;
                    $('#select-all-state').val("0");
                    checkboxes.each(function() {
                        selectedIds = selectedIds.filter(id => id !== this.value);
                    });
                }

                localStorage.setItem('selectedIds', JSON.stringify(selectedIds));
            });

            // Submit
            $('#print-form').submit(function(e) {
                if (!selectAllActive && selectedIds.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Silakan pilih setidaknya satu item!',
                    });
                    return;
                }

                $('#selected-items').val(selectedIds.join(','));
            });

            // Clear localStorage saat reload
            window.addEventListener('unload', function() {
                localStorage.removeItem('selectedIds');
            });
        });
    </script> --}}
@endsection
