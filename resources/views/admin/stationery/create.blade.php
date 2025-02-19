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

                <div class="mb-4">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" required>
                    @error('nama_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="haraga_barang" class="form-label">Harga Barang</label>
                    <input type="email" name="haraga_barang" class="form-control" required>
                    @error('haraga_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <select name="jenis_barang" class="form-control" required>
                        <option value="1">User</option>
                        <option value="2">Admin</option>
                    </select>
                    @error('jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control">
                    @error('satuan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
