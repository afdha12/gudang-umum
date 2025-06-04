<table>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stationeries as $stationery)
            <tr>
                <td>{{ strtoupper($stationery->nama_barang) }}</td>
                <td>{{ $stationery->satuan }}</td>
                <td>{{ number_format($stationery->harga_barang, 0, ',', '.') }}</td>
                <td>{{ $stationery->stok }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
