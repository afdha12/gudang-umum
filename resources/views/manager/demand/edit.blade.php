@extends('layouts.main')

@section('title', 'Tambah Data Stok Barang')

@section('content')
    <div class="flex justify-center">
        <div class="w-full max-w-lg overflow-y-auto border shadow-lg rounded-lg p-5">
            <form action="{{ route('item-demand.store') }}" method="POST">
                @csrf
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Input Tanggal (Hidden) -->
                <input type="hidden" name="dos" value="{{ date('Y-m-d') }}">

                <!-- Input User ID (Hidden) -->
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                <div class="mb-4">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <select id="jenis_barang" name="jenis_barang" class="form-control">
                        <option value="">Pilih Jenis Barang</option>
                        <option value="1">Alat Tulis</option>
                        <option value="2">Elektronik</option>
                    </select>
                    @error('jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <select id="stationery_id" name="stationery_id" class="form-control">
                        <option value="">Pilih Nama Barang</option>
                    </select>
                    @error('stationery_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" id="stok" class="form-control" readonly>
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="satuan" class="form-label">Jumlah</label>
                    <input type="number" name="amount" id="jumlah" class="form-control" required>
                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
