@extends('layouts.main')

@section('title', 'Create User')

@section('content')
    <div class="flex justify-center">
        <div class="w-full max-w-lg overflow-y-auto border shadow-lg rounded-lg p-5">
            <form action="{{ route('users-management.store') }}" method="POST">
                @csrf
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                    @error('username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="division" class="form-label">Divisi</label>
                    <input type="text" name="division" class="form-control">
                    @error('division')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create User</button>
            </form>
        </div>
    </div>
@endsection
