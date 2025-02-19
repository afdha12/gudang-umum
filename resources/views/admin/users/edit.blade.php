@extends('layouts.main')

@section('title', 'Users Management')

@section('content')

    <div class="overflow-y-auto border shadow-lg rounded-lg p-5">
        <form action="{{ route('users-management.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row d-flex justify-content-evenly">
                <div class="col-5">
                    <div class="mb-4">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $data->name) }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ old('username', $data->username) }}">
                        @error('username')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="text" class="form-control" id="email" name="email"
                            value="{{ old('email', $data->email) }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="division" class="form-label">Divisi</label>
                        <input type="text" class="form-control" id="division" name="division"
                            value="{{ old('division', $data->division) }}">
                        @error('division')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-5">
                    <div class="mb-4">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="role" name="role"
                            value="{{ old('role', $data->role) }}">
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-around mt-5">
                <a class="btn btn-danger" href="{{ route('users-management.index') }}">Batal</a>
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
        </form>
    </div>

@endsection
