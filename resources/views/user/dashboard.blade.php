@extends('layouts.main')

@section('title', 'User Page')

@section('content')

    <h4>Ini adalah halaman USER</h4>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Pengajuan</h5>
                    <p>{{ $total }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Menunggu</h5>
                    <p>{{ $menunggu }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Menunggu</h5>
                    <p>{{ $disetujui }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Menunggu</h5>
                    <p>{{ $menunggu }}</p>
                </div>
            </div>
        </div>
        <!-- dan seterusnya -->
    </div>

@endsection
