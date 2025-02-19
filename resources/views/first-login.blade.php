@extends('layouts.main')

@section('title', 'Change Password')

@section('content')

    <div class="overflow-y-auto border shadow-lg rounded-lg p-5">
        <form action="{{ route('change-password.update', $data->id) }}" method="POST">
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

            <button type="submit" class="btn btn-primary">Ganti Password</button>
        </form>
    </div>

@endsection
