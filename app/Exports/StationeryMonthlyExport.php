<?php

namespace App\Exports;

use App\Models\Stationery;
use App\Models\BarangHistory;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StationeryMonthlyExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithColumnFormatting
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return Stationery::where('status_barang', true)
            ->orderBy('nama_barang')->get();
    }

    public function map($item): array
    {
        $startMonth = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endMonth = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | SALDO AWAL: Transaksi sebelum bulan berjalan
        |--------------------------------------------------------------------------
        */

        // Masuk = jenis 'masuk' + semua reversal
        $inBefore = BarangHistory::where('stationery_id', $item->id)
            ->where(function ($q) {
                $q->where('jenis', 'masuk')
                    ->orWhere('reference_type', 'reversal');
            })
            ->where('tanggal', '<', $startMonth)
            ->sum('jumlah');

        // Keluar = jenis 'keluar' yang bukan reversal
        $outBefore = BarangHistory::where('stationery_id', $item->id)
            ->where('jenis', 'keluar')
            ->where(function ($q) {
                $q->whereNull('reference_type')
                    ->orWhere('reference_type', '!=', 'reversal');
            })
            ->where('tanggal', '<', $startMonth)
            ->sum('jumlah');

        $saldoAwal = $inBefore - $outBefore;


        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI DALAM BULAN INI
        |--------------------------------------------------------------------------
        */

        // Barang masuk bulan ini (HANYA jenis 'masuk', TANPA reversal)
        $barangMasuk = BarangHistory::where('stationery_id', $item->id)
            ->where('jenis', 'masuk')
            ->where(function ($q) {
                $q->whereNull('reference_type')
                    ->orWhere('reference_type', '!=', 'reversal');
            })
            ->whereBetween('tanggal', [$startMonth, $endMonth])
            ->sum('jumlah');

        // Pengeluaran bulan ini (jenis 'keluar')
        $pengeluaranKotor = BarangHistory::where('stationery_id', $item->id)
            ->where('jenis', 'keluar')
            ->whereBetween('tanggal', [$startMonth, $endMonth])
            ->sum('jumlah');

        // Reversal/pembatalan bulan ini
        $reversal = BarangHistory::where('stationery_id', $item->id)
            ->where('reference_type', 'reversal')
            ->whereBetween('tanggal', [$startMonth, $endMonth])
            ->sum('jumlah');

        // Pengeluaran bersih = pengeluaran - reversal
        $pengeluaran = $pengeluaranKotor - $reversal;


        /*
        |--------------------------------------------------------------------------
        | SALDO AKHIR & NILAI
        |--------------------------------------------------------------------------
        */
        $saldoAkhir = $saldoAwal + $barangMasuk - $pengeluaran;

        // Harga satuan (ambil dari model Stationery)
        $hargaSatuan = $item->harga_barang ?? 0;

        // Total nilai = saldo akhir x harga satuan
        $totalNilai = $saldoAkhir * $hargaSatuan;


        return [
            strtoupper($item->nama_barang),
            $item->satuan,
            $saldoAwal,
            $barangMasuk,
            $pengeluaran,
            $saldoAkhir,
            $hargaSatuan,
            $totalNilai,
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Satuan',
            'Saldo Awal',
            'Barang Masuk',
            'Pengeluaran',
            'Saldo Akhir',
            'Harga Satuan',
            'Total Nilai'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => '"Rp "#,##0', // Format Rupiah dengan Rp di depan
            'H' => '"Rp "#,##0', // Format Rupiah dengan Rp di depan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        $highest = $sheet->getHighestRow();
        $sheet->getStyle("A1:H{$highest}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center align untuk kolom angka
        $sheet->getStyle("C2:F{$highest}")->getAlignment()->setHorizontal('center');

        // Right align untuk kolom harga
        $sheet->getStyle("G2:H{$highest}")->getAlignment()->setHorizontal('right');
    }
}