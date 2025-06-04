<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangHistory;
use App\Models\ItemDemand;
use App\Models\Stationery;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan Stok
        $totalStok = Stationery::sum('stok');
        $barangHampirHabis = Stationery::where('stok', '<=', 10)->count(); // Bisa disesuaikan
        $barangMasuk = BarangHistory::where('jenis', 'masuk')->sum('jumlah');
        $barangKeluar = BarangHistory::where('jenis', 'keluar')->sum('jumlah');

        // Grafik Status Pengajuan Barang
        $pengajuanDisetujui = ItemDemand::where('coo_approval',1)->where('status', 1)->count();
        $pengajuanBelumDisetujui = ItemDemand::where('coo_approval',1)->whereNull('status')->count();

        // Ambil 10 aktivitas terbaru dari tabel history
        $logAktivitas = BarangHistory::with('stationery') // jika ada relasi barang
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($item) {
                $namaBarang = ucwords(strtolower($item->stationery->nama_barang ?? 'Barang ID : ' . $item->stationery_id));
                $satuan = $item->stationery->satuan ?? 'unit';
                $aksi = $item->jenis === 'masuk' ? 'Barang masuk' : 'Barang keluar';
                return (object) [
                    'message' => "$aksi: $item->jumlah $satuan $namaBarang",
                    'created_at' => $item->created_at
                ];
            });

        return view('admin.dashboard', compact(
            'pengajuanDisetujui',
            'pengajuanBelumDisetujui',
            'logAktivitas'
        ));
    }
}
