<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\ItemDemand;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf as Pdf;
use Barryvdh\DomPDF\Facade\Pdf as Dompdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PrintDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil hanya data yang sudah disetujui
        $approvedItems = ItemDemand::where('user_id', Auth::id())->where('status', '1')->paginate(10);
        return view('user.print.index', compact('approvedItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.print.cek');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Pastikan ada checkbox yang dipilih
        if (!$request->selected) {
            return redirect()->back()->with('error', 'Silakan pilih setidaknya satu item untuk dicetak!');
        }

        $selectedIds = explode(',', $request->selected);

        $approvedData = ItemDemand::whereIn('id', $selectedIds)
            ->where('manager_approval', 1)
            ->where('status', 1)
            ->get();

        // Jika tidak ada data yang memenuhi syarat
        if ($approvedData->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang dapat dicetak!');
        }

        $totalJumlah = $approvedData->sum('amount'); // Hitung total jumlah barang

        $division = optional($approvedData->first())->user->division_id;
        $manager = User::where('role', 'manager')->where('division_id', $division)->first();
        $admin = User::where('role', 'admin')->first();

        // Gunakan Spatie untuk generate PDF
        $pdf = Pdf::view('user.print.pengajuan_barang', compact('approvedData', 'manager', 'admin', 'totalJumlah'))
            ->format('A4')
            ->name('pengajuan_barang.pdf');

        return $pdf->inline(); // Tampilkan di browser
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
