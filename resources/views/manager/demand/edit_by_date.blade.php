@extends('layouts.main')

@section('title', 'Edit Permintaan - ' . $user->name . ' (' . date('d-m-Y', strtotime($date)) . ')')

@section('content')
    <h3>Permintaan Barang dari {{ $user->name }} pada {{ date('d-m-Y', strtotime($date)) }}</h3>

    <form action="{{ route('item_demands.update_by_date', ['user' => $user->id, 'date' => $date]) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->stationery->nama_barang ?? '-' }}</td>
                        <td>
                            <input type="number" name="jumlah[{{ $item->id }}]" value="{{ $item->amount }}" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="catatan[{{ $item->id }}]" value="{{ $item->catatan }}" class="form-control">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
@endsection
