@extends('layouts.main')

@section('title', 'Data Pengajuan Barang')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Data Permintaan Barang</h3>
                </div>
                <div class="box-body">
                    <a href="{{ route('data-pengajuan.create') }}" class="btn btn-success mb-3 text-white">
                        <i class="bi bi-journal-plus"></i> Form Permintaan Barang
                    </a>
                    {{-- <a href="index.php?p=formpesan" class="btn btn-success mb-3"><i class="bi bi-journal-plus"></i> Form Permintaan Barang</a> --}}
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Permintaan</th>
                                    <th>Jumlah Permintaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
