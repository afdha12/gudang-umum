@extends('layouts.main')

@section('title', 'Edit Data Stok Barang')

@section('content')
    <div class="flex justify-center">
        <div class="w-full max-w-lg overflow-y-auto border shadow-lg rounded-lg p-5">
            <form action="{{ route('stationeries.update', $stationery->id) }}" method="POST">
                @csrf
                @method('PUT')
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label for="kode_barang" class="block mb-2">Kode Barang</label>
                    <input type="text" name="kode_barang" class="w-full px-3 py-1.5 border rounded"
                        value="{{ old('kode_barang', $stationery->kode_barang) }}" disabled>
                    <input type="hidden" name="kode_barang" value="{{ $stationery->kode_barang }}">
                    @error('kode_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="jenis_barang" value="{{ old('jenis_barang', $stationery->jenis_barang) }}">

                <div class="mb-4">
                    <label for="nama_barang" class="block mb-2">Nama Barang</label>
                    <input type="text" name="nama_barang" class="w-full px-3 py-1.5 border rounded text-capitalize"
                        value="{{ old('nama_barang', $stationery->nama_barang) }}" required>
                    @error('nama_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="harga_barang" class="block mb-2">Harga Barang</label>
                    <input type="text" name="harga_barang" id="harga" class="w-full px-3 py-1.5 border rounded"
                        value="{{ old('harga_barang', $stationery->formatted_harga) }}" required>
                    @error('harga_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-4">
                    <label for="jenis_barang" class="block mb-2">Jenis Barang</label>
                    <input type="text" name="jenis_barang" class="w-full px-3 py-1.5 border rounded" value="{{ old('nama_barang', $stationery->nama_barang) }}" required>
                    @error('jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
                <div class="mb-4">
                    <label for="stok" class="block mb-2">Jumlah</label>
                    <input type="number" name="stok" class="w-full px-3 py-1.5 border rounded"
                        value="{{ old('stok', $stationery->stok) }}" disabled>
                    <input type="hidden" name="stok" value="{{ $stationery->stok }}">
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="block mb-2">Tambah Stok</label>
                    <input type="number" name="tambah" class="w-full px-3 py-1.5 border rounded" min="0"
                    >
                    {{-- <input type="hidden" name="masuk" class="w-full px-3 py-1.5 border rounded"
                        value="{{ old('masuk', $stationery->masuk) }}"> --}}
                    @error('masuk')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="kurang" class="block mb-2">Kurangi Stok</label>
                    <input type="number" name="kurang" id="kurang" class="w-full px-3 py-1.5 border rounded" min="0"
                        max="{{ $stationery->stok }}">
                    {{-- <small class="form-text text-muted">Masukkan jumlah stok yang ingin dikurangi (maks:
                        {{ $stationery->stok }})</small> --}}
                </div>

                <div class="mb-4">
                    <label for="satuan" class="block mb-2">Satuan</label>
                    <input type="text" name="satuan" class="w-full px-3 py-1.5 border rounded"
                        value="{{ old('satuan', $stationery->satuan) }}">
                    @error('satuan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
