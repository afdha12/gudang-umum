<?php

namespace App\Exports;

use App\Models\Stationery;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class StationeryExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        // Mengambil semua data stationery dan mengurutkannya berdasarkan nama_barang
        return Stationery::orderBy('nama_barang', 'asc')->get();
    }

    public function map($stationery): array
    {
        return [
            strtoupper($stationery->nama_barang),  // kolom A
            strtolower($stationery->satuan),                   // kolom B
            $stationery->harga_barang,             // kolom C (angka, akan diformat jadi rupiah)
            $stationery->stok                      // kolom D
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Satuan',
            'Harga',
            'Stok'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '"Rp" #,##0', // kolom harga dalam format Rp
            'D' => NumberFormat::FORMAT_NUMBER               // stok sebagai angka biasa
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header row (row 1)
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Apply border to entire data range including headers
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:D{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }
}
