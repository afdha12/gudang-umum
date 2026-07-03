@extends('layouts.main')

@section('title', 'Users Management')

@section('content')

    <div class="max-h-auto overflow-y-auto border shadow-lg rounded-lg">
        <div class="flex m-3 gap-2">
            <x-primary-link href="{{ route('users-management.create', ['type' => 'supplies']) }}">
                <i class="bi bi-person-plus"></i>
                Tambah User
            </x-primary-link>
            <x-secondary-link href="#" data-bs-toggle="modal" data-bs-target="#selectDivisionsModal">
                <i class="bi bi-gear"></i>
                Pengaturan Unit
            </x-secondary-link>
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
                    @foreach ($data as $key => $item)
                        <tr>
                            <td class="py-3 px-4">{{ $data->firstItem() + $key }}</td>
                            <td class="py-3 px-4">{{ $item->name }}</td>
                            <td class="py-3 px-4">{{ $item->username }}</td>
                            <td class="py-3 px-4">{{ $item->email }}</td>
                            <td class="py-3 px-4 text-capitalize">{{ $item->division->division_name ?? '' }}</td>
                            <td class="py-3 px-4">{{ $item->role }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <x-primary-link href="{{ route('users-management.edit', $item->id) }}"
                                        class="!px-3 !py-1.5">
                                        <i class="bi bi-pencil"></i>
                                    </x-primary-link>
                                    <x-danger-link href="{{ route('users-management.destroy', $item->id) }}"
                                        class="!px-3 !py-1.5" data-confirm-delete="true">
                                        <i class="bi bi-trash"></i>
                                    </x-danger-link>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('partials.pagination', ['data' => $data])
        </div>
    </div>

@endsection
