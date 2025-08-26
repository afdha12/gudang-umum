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
                <label for="password" class="block text-md font-medium mb-1">Password Baru</label>
                <input type="password" name="password" class="w-full px-3 py-1.5 border rounded" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-md font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full px-3 py-1.5 border rounded" required>
                @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                @if (auth()->user()->role !== 'user')
                    {{-- <input type="file" name="signature" class="form-control" accept="image/png"> --}}
                    <label for="signature" class="block text-md font-medium mb-1">Upload Tanda Tangan (PNG)</label>
                    <img id="imgPreview" class="w-40 mb-3 rounded border hidden" />
                    <input
                        class="w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        type="file" accept="image/png" id="signature" name="signature" onchange="previewImage()" />
                @endif
                @error('signature')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="px-4 py-2 mt-3 bg-emerald-500 text-white rounded hover:bg-emerald-600 text-sm">Simpan</button>
        </form>
    </div>

    <script>
        function previewImage() {
            const img = document.getElementById('signature');
            const imgPreview = document.getElementById('imgPreview');

            imgPreview.style.display = 'block';
            imgPreview.style.width = '200px'; // Ubah ukuran preview di sini
            imgPreview.style.height = '100px';
            imgPreview.style.objectFit = 'contain';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(img.files[0]);
            oFReader.onload = function (oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>

@endsection