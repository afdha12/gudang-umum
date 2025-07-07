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
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="w-full border rounded px-3 py-2" required>
                    @error('username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" class="w-full border rounded px-3 py-2" required>
                        <option value="user">Staff</option>
                        <option value="coo">Wadirum</option>
                        <option value="manager">Manager</option>
                        {{-- <option value="admin">Admin</option> --}}
                    </select>
                    @error('role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="division" class="form-label">Divisi</label>

                    <div class="flex items-center">
                        <select name="division_id" id="division_id"
                            class="block w-full border rounded-l px-3 py-2 text-uppercase" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" name="price" id="price"
                            class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                            placeholder="0.00" /> --}}
                        <div class="grid rounded-r border shrink-0 grid-cols-1 focus-within:relative">
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#addDivisionModal">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            {{-- <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                                viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                    d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg> --}}
                        </div>
                    </div>

                    {{-- <div class="input-group">
                        <select name="division_id" id="division_id"
                            class="block w-full border rounded px-3 py-2 text-uppercase" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addDivisionModal">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div> --}}
                    @error('division')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create User</button>
            </form>
        </div>
    </div>

    {{-- <div id="addDivisionModal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDivisionForm">
                        @csrf
                        <input type="text" id="newDivisionName" name="division_name" class="w-full border rounded px-3 py-2"
                            placeholder="Nama Divisi" required>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="saveDivisionBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div> --}}

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
                        <input type="text" id="newDivisionName" name="division_name"
                            class="w-full border rounded px-3 py-2 text-uppercase" placeholder="Nama Divisi" required>
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
    </script>
@endsection
