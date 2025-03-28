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
        $pengajuanDisetujui = ItemDemand::where('status', 1)->count();
        $pengajuanBelumDisetujui = ItemDemand::where('status', 0)->count();

        // Log Aktivitas Terbaru
        $logAktivitas = BarangHistory::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalStok', 
            'barangHampirHabis', 
            'barangMasuk', 
            'barangKeluar', 
            'pengajuanDisetujui', 
            'pengajuanBelumDisetujui', 
            'logAktivitas'
        ));
    }
}
