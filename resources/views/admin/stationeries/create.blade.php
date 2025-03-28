@extends('layouts.main')

@section('title', 'Tambah Data Stok Barang')

@section('content')
    <div class="flex justify-center">
        <div class="w-full max-w-lg overflow-y-auto border shadow-lg rounded-lg p-5">
            <form action="{{ route('stationeries.store') }}" method="POST">
                @csrf
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label for="kode_barang" class="form-label">Kode Barang</label>
                    <input type="text" name="kode_barang" class="form-control" value="{{ $kode_barang }}" disabled>
                    <input type="hidden" name="kode_barang" value="{{ $kode_barang }}">
                    @error('kode_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="jenis_barang" value="1">


                <div class="mb-4">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control text-capitalize" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="harga_barang" class="form-label">Harga Barang</label>
                    <input type="text" name="harga_barang" id="harga" value="{{ old('harga_barang') }}" class="form-control" required>
                    @error('harga_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-4">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <input type="text" name="jenis_barang" value="{{ old('nama_barang') }}" class="form-control" required>
                    @error('jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
                <div class="mb-4">
                    <label for="stok" class="form-label">Jumlah</label>
                    <input type="number" name="stok" value="{{ old('stok') }}" class="form-control">
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" name="satuan" value="{{ old('satuan') }}" class="form-control">
                    @error('satuan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
