@extends('layouts.main')

@section('title', 'Users Management')

@section('content')

    <div class="max-h-200 overflow-y-auto border shadow-lg rounded-lg">
        <div class="m-3">
            <a class="btn btn-primary" href="{{ route('users-management.create', ['type' => 'supplies']) }}">Tambah User</a>
            <a class="btn btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#selectDivisionsModal">Pengaturan Unit</a>
        </div>

        <div>
            @include('partials.division-manager')
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-gray-200 sticky top-0">
                    <tr class="border-b border-gray-300">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Username</th>
                        <th class="py-3 px-4 text-left">Email</th>
                        <th class="py-3 px-4 text-left">Divisi</th>
                        <th class="py-3 px-4 text-left">Role</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($data as $item)
                        <tr>
                            <td class="py-3 px-4">{{ $loop->iteration }}</td>
                            <td class="py-3 px-4">{{ $item->name }}</td>
                            <td class="py-3 px-4">{{ $item->username }}</td>
                            <td class="py-3 px-4">{{ $item->email }}</td>
                            <td class="py-3 px-4 text-capitalize">{{ $item->division->division_name ?? '' }}</td>
                            <td class="py-3 px-4">{{ $item->role }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('users-management.edit', $item->id) }}"
                                    class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-pencil"></i></i></a>
                                <a href="{{ route('users-management.destroy', $item->id) }}"
                                    class="btn btn-outline-danger btn-sm" data-confirm-delete="true"><i
                                        class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('partials.pagination', ['data' => $data])
        </div>
    </div>

@endsection
