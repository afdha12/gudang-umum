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
                        <div class="input-group">
                            <select name="division_id" id="division_id" class="form-control text-uppercase">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}" @if ($division->id == $data->division_id) selected @endif>
                                        {{ $division->division_name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addDivisionModal">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        @error('division')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-5">
                    <div class="mb-4">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control text-uppercase">
                            {{-- <option value="">-- Pilih Divisi --</option> --}}
                            @foreach ($roles as $role)
                                <option value="{{ $role ?? 'coo' }}" @if ($role == $data->role) selected @endif>
                                    {{ $role ?? 'Belum dipilih' }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" id="role" name="role"
                            value="{{ old('role', $data->role) }}"> --}}
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <input type="checkbox" id="reset_password" name="reset_password">
                        <label for="reset_password" class="form-label">Reset Password ke Default</label>
                    </div>
                </div>

            </div>
            <div class="d-flex justify-content-around mt-5">
                <a class="btn btn-danger" href="{{ route('users-management.index') }}">Batal</a>
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <!-- Modal Tambah Divisi -->
    <div id="addDivisionModal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDivisionForm">
                        @csrf
                        <input type="text" id="newDivisionName" name="division_name" class="form-control"
                            placeholder="Nama Divisi" required autofocus>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="saveDivisionBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("saveDivisionBtn").addEventListener("click", function() {
            let divisionName = document.getElementById("newDivisionName").value;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch("{{ route('divisions.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        division_name: divisionName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Response dari server:", data); // Debugging

                    if (data.success) {
                        let select = document.getElementById("division_id");
                        let option = document.createElement("option");
                        option.value = data.division.id;
                        option.textContent = data.division.division_name;
                        option.selected = true;
                        select.appendChild(option);

                        // **Simulasikan klik tombol close**
                        let closeButton = document.querySelector("#addDivisionModal .btn-close");
                        if (closeButton) {
                            closeButton.click();
                        }

                        // Reset input
                        document.getElementById("newDivisionName").value = "";

                        // **Tampilkan SweetAlert setelah modal tertutup**
                        setTimeout(() => {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: "Divisi berhasil ditambahkan.",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }, 500); // Delay agar modal benar-benar tertutup sebelum alert muncul
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: data.message || "Terjadi kesalahan, coba lagi.",
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops!",
                        text: "Terjadi kesalahan, coba lagi nanti."
                    });
                });
        });

        document.addEventListener("DOMContentLoaded", function() {
            let modal = document.getElementById("addDivisionModal");

            modal.addEventListener("shown.bs.modal", function() {
                document.getElementById("newDivisionName").focus();
            });
        });
    </script>

@endsection
