<?php
namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ItemDemand;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserDemandSheetExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $user;
    protected $from;
    protected $to;

    public function __construct(User $user, $from, $to)
    {
        $this->user = $user;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        // Ambil data permintaan
        $rawItems = ItemDemand::with('stationery')
            ->where('user_id', $this->user->id)
            ->where('status', 1)
            ->when($this->from, fn($q) => $q->whereDate('dos', '>=', $this->from))
            ->when($this->to, fn($q) => $q->whereDate('dos', '<=', $this->to))
            ->get();

        // Ambil semua tanggal unik pada periode
        $dates = $rawItems->pluck('dos')->unique()->sort()->values()->all();

        // Susun data barang per tanggal
        $grouped = [];
        foreach ($rawItems as $item) {
            $kode = $item->stationery->kode_barang ?? $item->stationery_id;
            if (!isset($grouped[$kode])) {
                $grouped[$kode] = [
                    'nama_barang' => $item->stationery->nama_barang ?? '-',
                    'satuan' => $item->stationery->satuan ?? '-',
                    'harga' => $item->stationery->harga_barang ?? 0,
                    'tanggal' => [],
                ];
            }

            // jumlahkan jika ada item dengan tanggal sama
            $grouped[$kode]['tanggal'][$item->dos] =
                ($grouped[$kode]['tanggal'][$item->dos] ?? 0) + $item->amount;
        }

        // Hitung total & jumlah setelah grouping selesai
        foreach ($grouped as $kode => &$g) {
            $g['total'] = array_sum($g['tanggal']);
            $g['jumlah'] = $g['total'] * ($g['harga'] ?? 0);
        }

        // Ubah ke array numerik untuk foreach di blade
        $items = array_values($grouped);

        return view('pages.print.laporan_bulanan', [
            'user' => $this->user,
            'items' => $items,
            'from' => $this->from,
            'to' => $this->to,
            'dates' => $dates,
        ]);
    }

    public function title(): string
    {
        // Nama worksheet sesuai nama user (maks 31 karakter untuk Excel)
        return substr($this->user->name, 0, 31);
    }
}