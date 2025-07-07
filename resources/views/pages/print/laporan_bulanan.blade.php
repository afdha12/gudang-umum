{{-- filepath: d:\PROJECT\gudang-umum\resources\views\pages\print\laporan_bulanan_excel.blade.php --}}
<table>
    <tr>
        <td colspan="11" style="font-weight: bold; text-align: center;">
            REKAP PERMINTAAN GUDANG UMUM RSH LAMPUNG PERIODE
            {{ strtoupper(\Carbon\Carbon::parse($from)->translatedFormat('F Y')) }}
        </td>
    </tr>
    <tr>
        <td colspan="11" style="font-weight: bold; text-align: center;">
            {{ strtoupper($user->division->name ?? $user->name) }}
        </td>
    </tr>
    <tr></tr>
    <tr>
        <th rowspan="2" style="border:1px solid #000;">NO</th>
        <th rowspan="2" style="border:1px solid #000;">NAMA BARANG</th>
        <th colspan="{{ count($dates) }}" style="border:1px solid #000;">TANGGAL</th>
        <th rowspan="2" style="border:1px solid #000;">TOTAL</th>
        <th rowspan="2" style="border:1px solid #000;">SATUAN</th>
        <th rowspan="2" style="border:1px solid #000;">HARGA</th>
        <th rowspan="2" style="border:1px solid #000;">JUMLAH</th>
    </tr>
    <tr>
        @foreach ($dates as $date)
            <th style="border:1px solid #000;">{{ \Carbon\Carbon::parse($date)->format('j') }}</th>
        @endforeach
    </tr>
    @php $grandTotal = 0; @endphp
    @foreach ($items as $i => $item)
        <tr>
            <td style="border:1px solid #000;">{{ $i + 1 }}</td>
            <td style="border:1px solid #000;">{{ strtoupper($item['nama_barang']) }}</td>
            @foreach ($dates as $date)
                <td style="border:1px solid #000;">
                    {{ $item['tanggal'][$date] ?? '' }}
                </td>
            @endforeach
            <td style="border:1px solid #000;">{{ $item['total'] }}</td>
            <td style="border:1px solid #000;">{{ $item['satuan'] }}</td>
            <td style="border:1px solid #000;">{{ 'Rp ' . number_format($item['harga'], 0, ',', '.') }}</td>
            <td style="border:1px solid #000;">{{ 'Rp ' . number_format($item['jumlah'], 0, ',', '.') }}</td>
        </tr>
        @php $grandTotal += $item['jumlah']; @endphp
    @endforeach
    <tr>
        <td colspan="{{ 5 + count($dates) }}" style="text-align:right; font-weight:bold; border:1px solid #000;">TOTAL
        </td>
        {{-- <td style="font-weight:bold; border:1px solid #000;">Rp</td> --}}
        <td style="font-weight:bold; border:1px solid #000;">{{ 'Rp ' . number_format($grandTotal, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td colspan="{{ 7 + count($dates) }}"></td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000;">Yang Menerima,</td>
        <td colspan="4" style="border:1px solid #000;">Yang Menyerahkan,</td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000; height:40px;"></td>
        <td colspan="4" style="border:1px solid #000;"></td>
    </tr>
    <tr>
        <td colspan="4" style="border:1px solid #000;">{{ strtoupper($user->division->name ?? $user->name) }}</td>
        <td colspan="4" style="border:1px solid #000;">GUDANG UMUM</td>
    </tr>
</table>
