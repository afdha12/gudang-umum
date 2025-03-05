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
                    <label for="kode_barang" class="form-label">Kode Barang</label>
                    <input type="text" name="kode_barang" class="form-control"
                        value="{{ old('kode_barang', $stationery->kode_barang) }}" disabled>
                    <input type="hidden" name="kode_barang" value="{{ $stationery->kode_barang }}">
                    @error('kode_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="jenis_barang" value="{{ old('jenis_barang', $stationery->jenis_barang) }}">

                <div class="mb-4">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control text-capitalize"
                        value="{{ old('nama_barang', $stationery->nama_barang) }}" required>
                    @error('nama_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="harga_barang" class="form-label">Harga Barang</label>
                    <input type="text" name="harga_barang" id="harga" class="form-control"
                        value="{{ old('harga_barang', $stationery->formatted_harga) }}" required>
                    @error('harga_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-4">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <input type="text" name="jenis_barang" class="form-control" value="{{ old('nama_barang', $stationery->nama_barang) }}" required>
                    @error('jenis_barang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
                <div class="mb-4">
                    <label for="stok" class="form-label">Jumlah</label>
                    <input type="number" name="stok" class="form-control" value="{{ old('stok', $stationery->stok) }}"
                        disabled>
                    <input type="hidden" name="stok" value="{{ $stationery->stok }}">
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="form-label">Tambah Stok</label>
                    <input type="number" name="tambah" class="form-control"
                        value="{{ old('tambah')}}">
                    <input type="hidden" name="masuk" class="form-control"
                        value="{{ old('masuk', $stationery->masuk) }}">
                    @error('masuk')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control"
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
