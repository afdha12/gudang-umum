@extends('layouts.main')

@section('title', 'Form Pengajuan Barang')

@section('content')

    <form action="{{ route('data-pengajuan.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col">
                <div class="card">
                    {{-- <div class="line" style="background-color: blue"></div> --}}
                    <div class="card-body m-3">
                        <div class="box-header with-border mb-5">
                            <h3 class="text-center">Form Permintaan Barang</h3>
                        </div>
                        {{-- <div class="row d-flex justify-content-start p-2 align-items-center">
                                    <div class="col">
                                        <label for="colFormLabel" class="col-form-label required">Tanggal Operasi</label>
                                    </div>
                                    <div class="col">
                                        <input type="date"
                                            class="form-control form-control-sm text-uppercase @error('tgl_operasi') is-invalid @enderror"
                                            id="tgl_operasi" name="tgl_operasi" required>
                                        @error('tgl_operasi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div> --}}
                        <div class="px-5">
                            <div class="row d-flex justify-content-start p-2 align-items-center">
                                <div class="col-sm-3">
                                    {{-- <label for="colFormLabel" class="col-form-label required">Tanggal Operasi</label> --}}
                                    <label for="nama_brg" class="acontrol-label">Nama</label>
                                </div>
                                <div class="col">
                                    <input type="text" readonly class="form-control" name="unit" value="{{ $currentUser->name }}">
                                    {{-- <input type="date"
                                        class="form-control form-control-sm text-uppercase @error('tgl_operasi') is-invalid @enderror"
                                        id="tgl_operasi" name="tgl_operasi" required>
                                    @error('tgl_operasi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror --}}
                                </div>
                            </div>

                            <div class="row d-flex justify-content-start p-2 align-items-center">
                                <div class="col-sm-3">
                                    {{-- <label for="colFormLabel" class="col-form-label required">Tanggal Operasi</label> --}}
                                    <label for="instansi" class="control-label">Struktural</label>
                                </div>
                                <div class="col">
                                    <input id="instansi" type="text" readonly class="form-control" name="instansi" value="{{ $currentUser->jabatan }}">
                                </div>
                            </div>

                            <div class="row d-flex justify-content-start p-2 align-items-center">
                                <div class="col-sm-3">
                                    <label for="colFormLabel" class="col-form-label required">Jenis Barang</label>
                                    {{-- <label for="jenis_brg" class="control-label">Jenis Barang</label> --}}
                                </div>
                                <div class="col">
                                    <select id="jenis_brg" required="isikan dulu" class="form-control" name="id_jenis">
                                        <option value="">--Pilih Jenis Barang--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-start p-2 align-items-center">
                                <div class="col-sm-3">
                                    <label for="colFormLabel" class="col-form-label required">Nama Barang</label>
                                    {{-- <label for="nama_brg" class=control-label">Nama Barang</label> --}}
                                </div>
                                <div class="col">
                                    <select id="nama_brg" required="isikan dulu" class="form-control" name="kode_brg">
                                        <option value="">--Pilih Nama Barang--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-start p-2 align-items-center">
                                <div class="col-sm-3">
                                    <label for="colFormLabel" class="col-form-label required">Stok Tersedia</label>
                                    {{-- <label for="stok" class=control-label">Stok Tersedia</label> --}}
                                </div>
                                <div class="col">
                                    <input id="stok" disabled value="----" type="text" class="form-control"
                                        name="stok">
                                </div>
                            </div>

                            <div class="row d-flex justify-content-start p-2 align-items-center">
                                <div class="col-sm-3">
                                    {{-- <label for="colFormLabel" class="col-form-label required">Tanggal Operasi</label> --}}
                                    <label for="stok" class="control-label">Jumlah</label>
                                </div>
                                <div class="col">
                                    <input id="jumlah" type="number" onkeyup="sendAjax()" class="form-control"
                                        name="jumlah" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-around mt-3">
                                <input type="submit" id="simpan" name="simpan" class="btn btn-primary"
                                    value="Simpan">
                                <input type="reset" class="btn btn-danger" value="Batal">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body m-3">
                        <div class="mb-5">
                            <h3 class="text-center">Data Permintaan Hari Ini</h3>
                        </div>

                        <table class="table table-responsive">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>


                            </tr>
                        </table>
                        <div class="box-body">
                            <a class="btn btn-success" href="pesan.php">Minta Barang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col col-xs-12">
            <div class="box box-primary">

                <form method="post" id="tes" action="add-proses.php" class="form-horizontal">

                </form>
            </div>
        </div>

        <div class="col col-xs-12">
            <div class="box box-info">

            </div>
        </div>
    </div>

@endsection
