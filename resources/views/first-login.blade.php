@extends('layouts.main')

@section('title', 'Change Password')

@section('content')

    <div class="overflow-y-auto border shadow-lg rounded-lg p-5">
        <form action="{{ route('change-password.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
                @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                @if (auth()->user()->role !== 'user')
                    <label for="signature" class="form-label">Upload Tanda Tangan (PNG)</label>
                    {{-- <input type="file" name="signature" class="form-control" accept="image/png"> --}}
                    <img class="img-preview img-fluid mb-3 col-sm-5">
                    <input class="form-control" type="file" accept="image/png" id="signature" name="signature"
                        onchange="previewImage()">
                @endif
                @error('signature')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script>
        function previewImage() {
            const img = document.querySelector('#signature');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';
            imgPreview.style.width = '200px'; // Ubah ukuran preview di sini
            imgPreview.style.height = '100px';
            imgPreview.style.objectFit = 'contain';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(img.files[0]);
            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>

@endsection
